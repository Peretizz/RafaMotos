<?php
require_once __DIR__ . '/ConexaoBD.php';
require_once __DIR__ . '/../includes/helpers.php';

class MotoDAO
{
    /* ------------------------------------------------------------------
     * Leitura
     * ----------------------------------------------------------------*/

    public static function listar(PDO $c): array
    {
        return $c->query("SELECT * FROM motos ORDER BY idmoto DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Todas as fotos de uma vez, agrupadas por moto (capa = menor idfoto). */
    public static function fotosPorMoto(PDO $c): array
    {
        $out = [];
        $q = $c->query("SELECT idfoto, idmoto, caminho_foto FROM fotos_motos ORDER BY idmoto, idfoto");
        foreach ($q->fetchAll(PDO::FETCH_ASSOC) as $f) {
            $out[$f['idmoto']][] = $f;
        }
        return $out;
    }

    public static function buscar(PDO $c, int $id): ?array
    {
        $s = $c->prepare("SELECT * FROM motos WHERE idmoto = ?");
        $s->execute([$id]);
        $m = $s->fetch(PDO::FETCH_ASSOC);
        return $m ?: null;
    }

    public static function fotosDaMoto(PDO $c, int $id): array
    {
        $s = $c->prepare("SELECT idfoto, caminho_foto FROM fotos_motos WHERE idmoto = ? ORDER BY idfoto");
        $s->execute([$id]);
        return $s->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ------------------------------------------------------------------
     * Validação dos campos do formulário
     * ----------------------------------------------------------------*/

    /** @return array [dados limpos, erro|null] */
    public static function validar(array $p): array
    {
        $titulo = trim((string)($p['titulo'] ?? ''));
        $preco  = parse_preco((string)($p['preco'] ?? ''));
        if ($titulo === '' || mb_strlen($titulo) > 150) return [null, 'Informe o título (até 150 caracteres).'];
        if ($preco === null || $preco <= 0 || $preco > 99999999) return [null, 'Informe um preço válido. Exemplo: 19900 ou 19.900,00'];

        $lim = function ($k, $n = 50) use ($p) { return mb_substr(trim((string)($p[$k] ?? '')), 0, $n); };
        return [[
            'titulo'      => $titulo,
            'preco'       => $preco,
            'km'          => $lim('km'),
            'cilindrada'  => $lim('cilindrada'),
            'potencia'    => $lim('potencia'),
            'transmissao' => $lim('transmissao'),
        ], null];
    }

    /* ------------------------------------------------------------------
     * Upload de fotos (valida, reduz e salva)
     * ----------------------------------------------------------------*/

    /** @return string[] nomes dos arquivos gravados */
    private static function salvarFotos(array $arquivos, int $limite): array
    {
        $salvos = [];
        if (empty($arquivos['fotos']['tmp_name'])) return $salvos;
        if (!is_dir(IMG_DIR)) @mkdir(IMG_DIR, 0755, true);

        $tmps = (array)$arquivos['fotos']['tmp_name'];
        foreach ($tmps as $i => $tmp) {
            if (count($salvos) >= $limite) break;
            if (($arquivos['fotos']['error'][$i] ?? 1) !== UPLOAD_ERR_OK || !is_uploaded_file($tmp)) continue;
            if (($arquivos['fotos']['size'][$i] ?? 0) > FOTO_MAX_MB * 1048576) continue;

            $info = @getimagesize($tmp);               // confirma que é imagem de verdade
            if (!$info || !in_array($info[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) continue;

            $nome = bin2hex(random_bytes(16)) . '.jpg'; // extensão sempre controlada por nós
            $dest = IMG_DIR . $nome;

            $ok = function_exists('imagecreatetruecolor')
                ? redimensionar($tmp, $dest, FOTO_MAX_LADO, FOTO_QUALIDADE)
                : false;
            if (!$ok && $info[2] === IMAGETYPE_JPEG) {  // sem GD: guarda o JPEG como veio
                $ok = move_uploaded_file($tmp, $dest);
            }
            if ($ok) $salvos[] = $nome;
        }
        return $salvos;
    }

    /* ------------------------------------------------------------------
     * Cadastrar / editar / excluir
     * ----------------------------------------------------------------*/

    public static function cadastrar(array $post, array $arquivos): array
    {
        [$d, $erro] = self::validar($post);
        if ($erro) return [false, $erro];

        $c = ConexaoBD::conectar();
        $novos = self::salvarFotos($arquivos, FOTO_MAX_POR_MOTO);
        if (!$novos) return [false, 'Envie ao menos uma foto válida (JPG, PNG ou WebP).'];

        try {
            $c->beginTransaction();
            $c->prepare("INSERT INTO motos (titulo, preco, km, cilindrada, potencia, transmissao)
                         VALUES (:titulo, :preco, :km, :cilindrada, :potencia, :transmissao)")->execute($d);
            $id = (int)$c->lastInsertId();
            $ins = $c->prepare("INSERT INTO fotos_motos (idmoto, caminho_foto) VALUES (?, ?)");
            foreach ($novos as $n) $ins->execute([$id, $n]);
            $c->commit();
            return [true, 'Moto publicada com sucesso.'];
        } catch (Throwable $e) {
            if ($c->inTransaction()) $c->rollBack();
            foreach ($novos as $n) apagar_foto_arquivo($n);   // não deixa foto órfã se deu erro
            return [false, 'Não foi possível salvar a moto. Tente novamente.'];
        }
    }

    public static function editar(int $id, array $post, array $arquivos): array
    {
        [$d, $erro] = self::validar($post);
        if ($erro) return [false, $erro];

        $c = ConexaoBD::conectar();
        if (!self::buscar($c, $id)) return [false, 'Moto não encontrada.'];

        $atuais   = self::fotosDaMoto($c, $id);
        $remover  = array_map('intval', (array)($post['remover'] ?? []));
        $restantes = array_filter($atuais, function ($f) use ($remover) { return !in_array((int)$f['idfoto'], $remover, true); });
        $espaco   = max(0, FOTO_MAX_POR_MOTO - count($restantes));
        $novos    = self::salvarFotos($arquivos, $espaco);

        if (count($restantes) + count($novos) < 1) {
            foreach ($novos as $n) apagar_foto_arquivo($n);
            return [false, 'A moto precisa ter pelo menos uma foto.'];
        }

        $apagarArquivos = [];
        try {
            $c->beginTransaction();
            $d['id'] = $id;
            $c->prepare("UPDATE motos SET titulo=:titulo, preco=:preco, km=:km, cilindrada=:cilindrada,
                         potencia=:potencia, transmissao=:transmissao WHERE idmoto=:id")->execute($d);

            $del = $c->prepare("DELETE FROM fotos_motos WHERE idfoto = ? AND idmoto = ?");
            foreach ($atuais as $f) {
                if (in_array((int)$f['idfoto'], $remover, true)) {
                    $del->execute([$f['idfoto'], $id]);
                    $apagarArquivos[] = $f['caminho_foto'];
                }
            }
            $ins = $c->prepare("INSERT INTO fotos_motos (idmoto, caminho_foto) VALUES (?, ?)");
            foreach ($novos as $n) $ins->execute([$id, $n]);

            // Trocar a capa: a foto escolhida passa a ser a primeira (menor idfoto)
            $capa = (int)($post['capa'] ?? 0);
            if ($capa) {
                $lista = self::fotosDaMoto($c, $id);
                if ($lista && (int)$lista[0]['idfoto'] !== $capa) {
                    $alvo = null;
                    foreach ($lista as $f) if ((int)$f['idfoto'] === $capa) $alvo = $f;
                    if ($alvo) {
                        $up = $c->prepare("UPDATE fotos_motos SET caminho_foto = ? WHERE idfoto = ?");
                        $up->execute([$alvo['caminho_foto'], $lista[0]['idfoto']]);
                        $up->execute([$lista[0]['caminho_foto'], $alvo['idfoto']]);
                    }
                }
            }
            $c->commit();
        } catch (Throwable $e) {
            if ($c->inTransaction()) $c->rollBack();
            foreach ($novos as $n) apagar_foto_arquivo($n);
            return [false, 'Não foi possível salvar as alterações.'];
        }

        foreach ($apagarArquivos as $a) apagar_foto_arquivo($a);   // só apaga do disco depois de salvar no banco
        return [true, 'Moto atualizada.'];
    }

    public static function excluir(int $id): array
    {
        $c = ConexaoBD::conectar();
        try {
            $fotos = self::fotosDaMoto($c, $id);
            $ok = $c->prepare("DELETE FROM motos WHERE idmoto = ?")->execute([$id]);   // fotos_motos sai junto (ON DELETE CASCADE)
            if (!$ok) return [false, 'Não foi possível excluir.'];
            foreach ($fotos as $f) apagar_foto_arquivo($f['caminho_foto']);
            return [true, 'Moto excluída e fotos removidas do servidor.'];
        } catch (Throwable $e) {
            return [false, 'Não foi possível excluir.'];
        }
    }

    /* ------------------------------------------------------------------
     * Limpeza de imagens que não são usadas por nenhuma moto
     * ----------------------------------------------------------------*/

    /** @return array{orfas: array, bytes: int, quebradas: array, total: int, usadas: int} */
    public static function analisarImagens(PDO $c): array
    {
        $usadas = [];
        foreach ($c->query("SELECT caminho_foto FROM fotos_motos")->fetchAll(PDO::FETCH_COLUMN) as $f) $usadas[nome_arquivo_seguro($f)] = true;
        foreach ($c->query("SELECT foto FROM motos WHERE foto IS NOT NULL AND foto <> ''")->fetchAll(PDO::FETCH_COLUMN) as $f) $usadas[nome_arquivo_seguro($f)] = true;

        $orfas = [];
        $bytes = 0;
        $total = 0;
        foreach (glob(IMG_DIR . '*') ?: [] as $path) {
            if (!is_file($path)) continue;
            $nome = basename($path);
            if (!preg_match('/\.(jpe?g|png|webp|gif)$/i', $nome)) continue;
            $total++;
            if (in_array($nome, IMG_PROTEGIDAS, true) || isset($usadas[$nome])) continue;
            $tam = filesize($path);
            $orfas[] = ['nome' => $nome, 'bytes' => $tam];
            $bytes += $tam;
        }
        // miniaturas (img/cache) cuja foto original não existe mais
        foreach (glob(CACHE_DIR . 'w*_*.jpg') ?: [] as $path) {
            if (!preg_match('/^w\d+_(.+)\.jpg$/', basename($path), $m)) continue;
            $temUso = false;
            foreach (array_keys($usadas) as $u) if (pathinfo($u, PATHINFO_FILENAME) === $m[1]) { $temUso = true; break; }
            if (!$temUso) {
                $tam = filesize($path);
                $orfas[] = ['nome' => 'cache/' . basename($path), 'bytes' => $tam];
                $bytes += $tam;
            }
        }
        $quebradas = [];
        foreach (array_keys($usadas) as $u) if (!is_file(IMG_DIR . $u)) $quebradas[] = $u;

        return ['orfas' => $orfas, 'bytes' => $bytes, 'quebradas' => $quebradas, 'total' => $total, 'usadas' => count($usadas)];
    }

    public static function apagarOrfas(PDO $c): array
    {
        $a = self::analisarImagens($c);   // recalcula na hora: nunca confia em lista vinda do navegador
        $n = 0; $b = 0;
        foreach ($a['orfas'] as $o) {
            $path = IMG_DIR . $o['nome'];
            if (strpos($o['nome'], 'cache/') === 0) $path = CACHE_DIR . basename($o['nome']);
            if (is_file($path) && @unlink($path)) { $n++; $b += $o['bytes']; }
        }
        return [$n, $b];
    }
}

/* ----------------------------------------------------------------------
 * Receptor de formulários (só quando este arquivo é chamado diretamente)
 * --------------------------------------------------------------------*/
if (basename($_SERVER['SCRIPT_FILENAME'] ?? '') === 'MotoDAO.php') {
    if (!is_admin()) { header('Location: ../login.php'); exit; }

    $acao = $_GET['acao'] ?? '';
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valido()) {
        flash('erro', 'Sessão expirada. Tente de novo.');
        header('Location: ../index.php#motos'); exit;
    }

    if ($acao === 'cadastrar') {
        [$ok, $msg] = MotoDAO::cadastrar($_POST, $_FILES);
        flash($ok ? 'ok' : 'erro', $msg);
        header('Location: ' . ($ok ? '../index.php#motos' : '../form-cadastro-moto.php')); exit;
    }
    if ($acao === 'editar') {
        $id = (int)($_POST['idmoto'] ?? 0);
        [$ok, $msg] = MotoDAO::editar($id, $_POST, $_FILES);
        flash($ok ? 'ok' : 'erro', $msg);
        header('Location: ' . ($ok ? '../index.php#motos' : '../editar-moto.php?id=' . $id)); exit;
    }
    if ($acao === 'excluir') {
        [$ok, $msg] = MotoDAO::excluir((int)($_POST['id'] ?? 0));
        flash($ok ? 'ok' : 'erro', $msg);
        header('Location: ../index.php#motos'); exit;
    }
    if ($acao === 'limpar') {
        [$n, $b] = MotoDAO::apagarOrfas(ConexaoBD::conectar());
        flash('ok', $n . ' arquivo(s) apagado(s) — ' . fmt_bytes($b) . ' liberados.');
        header('Location: ../limpar-imagens.php'); exit;
    }
    header('Location: ../index.php');
}
