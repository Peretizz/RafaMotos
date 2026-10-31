<?php
require_once __DIR__ . '/config.php';

/* Alguns servidores não trazem a extensão mbstring: estas versões simples evitam erro. */
if (!function_exists('mb_strtoupper')) { function mb_strtoupper($s, $e = null) { return strtoupper($s); } }
if (!function_exists('mb_strtolower')) { function mb_strtolower($s, $e = null) { return strtolower($s); } }
if (!function_exists('mb_strlen'))     { function mb_strlen($s, $e = null) { return strlen($s); } }
if (!function_exists('mb_substr'))     { function mb_substr($s, $a, $l = null, $e = null) { return substr($s, $a, $l); } }
if (!function_exists('mb_convert_case')) { function mb_convert_case($s, $m, $e = null) { return ucwords(strtolower($s)); } }
if (!defined('MB_CASE_TITLE')) { define('MB_CASE_TITLE', 2); }

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

const IMG_DIR   = __DIR__ . '/../img/';
const CACHE_DIR = __DIR__ . '/../img/cache/';
/** Arquivos do próprio site que nunca devem ser apagados da pasta img/ */
const IMG_PROTEGIDAS = ['logo.png', 'logo-site.png', 'favicon.png', 'og-image.jpg', 'fundo.jpg', 'sobre.jpg'];

/* ---------- Segurança / sessão ---------- */

function e($v): string
{
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function is_admin(): bool
{
    return isset($_SESSION['logado']) && $_SESSION['logado'] === true;
}

function exigir_admin(): void
{
    if (!is_admin()) {
        header('Location: login.php');
        exit;
    }
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_campo(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_valido(): bool
{
    $enviado = $_POST['csrf'] ?? '';
    return is_string($enviado) && isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $enviado);
}

function flash(string $tipo, string $msg): void
{
    $_SESSION['flash'] = ['tipo' => $tipo, 'msg' => $msg];
}

function flash_pegar(): ?array
{
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

/* ---------- Formatação ---------- */

const SIGLAS = ['CB', 'CBR', 'MT', 'GS', 'NC', 'XRE', 'GK', 'BMW', 'ES', 'UBS', 'CVT', 'RR', 'XT', 'CG', 'PCX', 'NXR', 'ABS', 'SE', 'GT'];

/** "Honda Cb 650f 2015" -> "Honda CB 650F 2015" */
function titulo_bonito(string $t): string
{
    $palavras = preg_split('/\s+/u', trim($t)) ?: [];
    foreach ($palavras as &$p) {
        $u = mb_strtoupper($p, 'UTF-8');
        if (in_array($u, SIGLAS, true) || preg_match('/\d/', $p)) {
            $p = $u;
        } else {
            $p = mb_convert_case(mb_strtolower($p, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
        }
    }
    return implode(' ', $palavras);
}

function moto_ano(string $t): ?int
{
    return preg_match('/\b(19[89]\d|20[0-4]\d)\s*$/', trim($t), $m) ? (int)$m[1] : null;
}

/** Título sem o ano no final */
function moto_modelo(string $t): string
{
    $b = titulo_bonito($t);
    return trim(preg_replace('/\s*\b(19[89]\d|20[0-4]\d)\s*$/', '', $b));
}

function moto_marca(string $t): string
{
    $p = preg_split('/\s+/u', trim($t))[0] ?? '';
    $u = mb_strtoupper($p, 'UTF-8');
    return in_array($u, SIGLAS, true) ? $u : mb_convert_case(mb_strtolower($p, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
}

function so_numero($s): int
{
    return (int)preg_replace('/\D/', '', (string)$s);
}

function fmt_km($s): string
{
    $n = so_numero($s);
    return number_format($n, 0, ',', '.') . ' km';
}

function fmt_cc($s): string
{
    $n = so_numero($s);
    return $n > 0 ? $n . ' cc' : '—';
}

function fmt_cv($s): string
{
    $s = trim((string)$s);
    if ($s === '') return '—';
    return preg_match('/cv/i', $s) ? $s : $s . ' cv';
}

function fmt_preco($v): string
{
    return 'R$ ' . number_format((float)$v, 0, ',', '.');
}

/** Aceita "19900", "19900.00", "19.900,00", "R$ 19.900" */
function parse_preco(string $s): ?float
{
    $s = trim(preg_replace('/[^\d.,]/', '', $s));
    if ($s === '') return null;
    if (strpos($s, ',') !== false) {
        $s = str_replace('.', '', $s);
        $s = str_replace(',', '.', $s);
    } elseif (preg_match('/^\d{1,3}(\.\d{3})+$/', $s)) {
        $s = str_replace('.', '', $s);
    }
    return is_numeric($s) ? round((float)$s, 2) : null;
}

function wa_link(string $msg = ''): string
{
    return 'https://wa.me/' . LOJA_WHATSAPP . ($msg !== '' ? '?text=' . rawurlencode($msg) : '');
}

function url_base(): string
{
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    $dir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    return ($https ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . $dir;
}

/* ---------- Imagens ---------- */

function nome_arquivo_seguro(string $f): string
{
    return basename(str_replace("\0", '', $f));
}

/** Larguras de miniatura permitidas (evita que alguém peça milhares de tamanhos diferentes) */
const LARGURAS_FOTO = [160, 720, 1100];

function caminho_cache(string $arquivo, int $largura): string
{
    return CACHE_DIR . 'w' . $largura . '_' . pathinfo(nome_arquivo_seguro($arquivo), PATHINFO_FILENAME) . '.jpg';
}

/**
 * Endereço de uma versão reduzida da foto (carrega mais rápido, gasta menos internet do cliente).
 * Se a miniatura já existe, aponta direto para ela; senão, foto.php cria na hora e guarda em img/cache/.
 * Sem a extensão GD no servidor, usa a foto original — o site nunca quebra.
 */
function foto_url(string $arquivo, int $largura = 720): string
{
    $arquivo = nome_arquivo_seguro($arquivo);
    if (!function_exists('imagecreatetruecolor')) {
        return 'img/' . rawurlencode($arquivo);
    }
    $cache = caminho_cache($arquivo, $largura);
    if (is_file($cache)) {
        return 'img/cache/' . basename($cache);
    }
    return 'foto.php?f=' . rawurlencode($arquivo) . '&w=' . $largura;
}

/** Redimensiona (lado maior = $max) e salva como JPEG. Respeita a rotação do celular. */
function redimensionar(string $origem, string $destino, int $max, int $qualidade): bool
{
    $info = @getimagesize($origem);
    if (!$info) return false;
    switch ($info[2]) {
        case IMAGETYPE_JPEG: $im = @imagecreatefromjpeg($origem); break;
        case IMAGETYPE_PNG:  $im = @imagecreatefrompng($origem);  break;
        case IMAGETYPE_WEBP: $im = function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($origem) : false; break;
        default: return false;
    }
    if (!$im) return false;

    if ($info[2] === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
        $exif = @exif_read_data($origem);
        $o = $exif['Orientation'] ?? 1;
        $ang = [3 => 180, 6 => -90, 8 => 90][$o] ?? 0;
        if ($ang) {
            $rot = imagerotate($im, $ang, 0);
            if ($rot) { imagedestroy($im); $im = $rot; }
        }
    }

    $w = imagesx($im);
    $h = imagesy($im);
    $esc = min(1, $max / max($w, $h));
    $nw = max(1, (int)round($w * $esc));
    $nh = max(1, (int)round($h * $esc));

    $out = imagecreatetruecolor($nw, $nh);
    imagefill($out, 0, 0, imagecolorallocate($out, 255, 255, 255)); // fundo branco p/ PNG transparente
    imagecopyresampled($out, $im, 0, 0, 0, 0, $nw, $nh, $w, $h);
    $ok = imagejpeg($out, $destino, $qualidade);
    imagedestroy($im);
    imagedestroy($out);
    return $ok;
}

/** Apaga a foto original e as miniaturas dela. */
function apagar_foto_arquivo(string $arquivo): void
{
    $arquivo = nome_arquivo_seguro($arquivo);
    if ($arquivo === '' || in_array($arquivo, IMG_PROTEGIDAS, true)) return;
    $orig = IMG_DIR . $arquivo;
    if (is_file($orig)) @unlink($orig);
    $base = pathinfo($arquivo, PATHINFO_FILENAME);
    foreach (glob(CACHE_DIR . 'w*_' . $base . '.jpg') ?: [] as $c) {
        @unlink($c);
    }
}

function fmt_bytes(int $b): string
{
    if ($b >= 1048576) return number_format($b / 1048576, 1, ',', '.') . ' MB';
    if ($b >= 1024) return number_format($b / 1024, 0, ',', '.') . ' KB';
    return $b . ' B';
}
