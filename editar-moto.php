<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/src/MotoDAO.php';
exigir_admin();

$c = ConexaoBD::conectar();
$id = (int)($_GET['id'] ?? 0);
$moto = MotoDAO::buscar($c, $id);
if (!$moto) {
    flash('erro', 'Moto não encontrada.');
    header('Location: index.php#motos');
    exit;
}
$fotos = MotoDAO::fotosDaMoto($c, $id);

$titulo_pagina = 'Editar moto | ' . LOJA_NOME;
$pagina = 'admin';
include __DIR__ . '/includes/topo.php';
?>
<section class="painel">
    <div class="container">
        <form class="form-cartao" action="src/MotoDAO.php?acao=editar" method="post" enctype="multipart/form-data">
            <h1>Editar moto</h1>
            <p class="form-cartao__sub"><?= e(titulo_bonito($moto['titulo'])) ?></p>
            <?= csrf_campo() ?>
            <input type="hidden" name="idmoto" value="<?= $id ?>">
            <?php include __DIR__ . '/includes/form-moto.php'; ?>

            <fieldset class="campo fotos-atuais">
                <legend>Fotos atuais</legend>
                <small>Escolha a capa ou marque as fotos que quer remover. Elas são apagadas do servidor ao salvar.</small>
                <div class="fotos-grade">
                    <?php foreach ($fotos as $i => $f): ?>
                    <div class="foto-item">
                        <img src="<?= e(foto_url($f['caminho_foto'], 160)) ?>" alt="Foto <?= $i + 1 ?>" width="160" height="120" loading="lazy">
                        <label class="foto-item__capa"><input type="radio" name="capa" value="<?= (int)$f['idfoto'] ?>" <?= $i === 0 ? 'checked' : '' ?>> Capa</label>
                        <label class="foto-item__remover"><input type="checkbox" name="remover[]" value="<?= (int)$f['idfoto'] ?>"> Remover</label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </fieldset>

            <div class="campo">
                <label for="fotos">Adicionar mais fotos</label>
                <input type="file" id="fotos" name="fotos[]" accept="image/jpeg,image/png,image/webp" multiple data-previa="#previa">
                <small>Cada moto pode ter até <?= FOTO_MAX_POR_MOTO ?> fotos.</small>
                <div class="previa" id="previa"></div>
            </div>

            <div class="form-cartao__botoes">
                <button type="submit" class="btn btn--verde">Salvar alterações</button>
                <a class="btn btn--contorno" href="index.php#motos">Cancelar</a>
            </div>
        </form>
    </div>
</section>
<?php include __DIR__ . '/includes/rodape.php'; ?>
