<?php
/**
 * Entrega fotos redimensionadas. Cria a miniatura na primeira vez e guarda em img/cache/.
 * Uso: foto.php?f=nome-do-arquivo.jpg&w=720
 */
require_once __DIR__ . '/includes/helpers.php';

$arquivo = nome_arquivo_seguro((string)($_GET['f'] ?? ''));
$w = (int)($_GET['w'] ?? 720);
$origem = IMG_DIR . $arquivo;

if ($arquivo === '' || !in_array($w, LARGURAS_FOTO, true) || !is_file($origem)
    || !preg_match('/\.(jpe?g|png|webp)$/i', $arquivo)) {
    http_response_code(404);
    exit;
}

$destino = caminho_cache($arquivo, $w);
if (!is_file($destino)) {
    if (!is_dir(CACHE_DIR)) @mkdir(CACHE_DIR, 0755, true);
    if (!function_exists('imagecreatetruecolor') || !redimensionar($origem, $destino, $w, 80)) {
        header('Location: img/' . rawurlencode($arquivo));   // não deu para reduzir: manda a original
        exit;
    }
}

header('Content-Type: image/jpeg');
header('Content-Length: ' . filesize($destino));
header('Cache-Control: public, max-age=31536000, immutable');
readfile($destino);
