<?php
/**
 * Cabeçalho comum a todas as páginas.
 * Variáveis opcionais: $titulo_pagina, $descricao_pagina, $pagina ('home' | 'admin' | 'login'), $og_imagem
 */
require_once __DIR__ . '/helpers.php';
$titulo_pagina    = $titulo_pagina    ?? LOJA_NOME . ' | Motos em Videira - SC';
$descricao_pagina = $descricao_pagina ?? 'Motos selecionadas, mecânica e peças em Videira - SC. Veja fotos, preços e fale direto com a Rafa Motos pelo WhatsApp.';
$pagina           = $pagina ?? 'home';
$ancora = $pagina === 'home' ? '' : 'index.php';
$flash = flash_pegar();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo_pagina) ?></title>
    <meta name="description" content="<?= e($descricao_pagina) ?>">
    <meta name="theme-color" content="#04122b">
    <?php if ($pagina !== 'home'): ?><meta name="robots" content="noindex"><?php endif; ?>
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link rel="apple-touch-icon" href="img/favicon.png">

    <meta property="og:type" content="website">
    <meta property="og:locale" content="pt_BR">
    <meta property="og:site_name" content="<?= e(LOJA_NOME) ?>">
    <meta property="og:title" content="<?= e($titulo_pagina) ?>">
    <meta property="og:description" content="<?= e($descricao_pagina) ?>">
    <meta property="og:image" content="<?= e(url_base()) ?>/img/og-image.jpg">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,500;0,600;0,700;1,700&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?v=<?= @filemtime(__DIR__ . '/../css/style.css') ?: 1 ?>">
</head>
<body class="pg-<?= e($pagina) ?>">
<a class="pular" href="#conteudo">Pular para o conteúdo</a>

<?php if (is_admin()): ?>
<div class="admin-bar" role="navigation" aria-label="Painel do administrador">
    <div class="container admin-bar__in">
        <strong>Modo administrador</strong>
        <span class="admin-bar__links">
            <a href="form-cadastro-moto.php">+ Cadastrar moto</a>
            <a href="limpar-imagens.php">Limpar imagens</a>
            <a href="logout.php">Sair</a>
        </span>
    </div>
</div>
<?php endif; ?>

<header class="topo" id="topo">
    <div class="container topo__in">
        <a class="topo__logo" href="index.php" aria-label="<?= e(LOJA_NOME) ?> — página inicial">
            <img src="img/logo-site.png" alt="<?= e(LOJA_NOME) ?> — Mecânica, Peças, Vendas" width="383" height="214">
        </a>
        <button class="menu-btn" id="menu-btn" type="button" aria-expanded="false" aria-controls="menu">
            <span class="menu-btn__barras" aria-hidden="true"></span>
            <span class="sr">Menu</span>
        </button>
        <nav class="menu" id="menu" aria-label="Principal">
            <ul>
                <li><a href="<?= $ancora ?>#motos">Motos</a></li>
                <li><a href="<?= $ancora ?>#servicos">Serviços</a></li>
                <li><a href="<?= $ancora ?>#sobre">Sobre</a></li>
                <li><a href="<?= $ancora ?>#contato">Contato</a></li>
            </ul>
            <a class="btn btn--verde btn--pequeno" href="<?= e(wa_link('Olá! Vim pelo site da Rafa Motos.')) ?>" target="_blank" rel="noopener">
                <?= '<svg class="ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 3.5A11 11 0 0 0 3.2 17.4L2 22l4.7-1.2A11 11 0 1 0 20.5 3.5Zm-8.5 17a9.100 9.100 0 0 1-4.600-1.300l-.3-.2-2.800.7.8-2.700-.2-.3a9.100 9.100 0 1 1 7.100 3.800Zm5-6.800c-.3-.1-1.600-.8-1.900-.9s-.4-.1-.6.100l-.8 1c-.1.200-.3.200-.6.100a7.400 7.400 0 0 1-3.700-3.200c-.3-.5.300-.5.800-1.500.1-.2 0-.3 0-.5l-.9-2.100c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.300 3 3 0 0 0-.9 2.200 5.200 5.200 0 0 0 1.100 2.800 11.900 11.900 0 0 0 4.600 4.100c1.700.7 2.400.8 3.200.7a2.700 2.700 0 0 0 1.800-1.300 2.200 2.200 0 0 0 .2-1.300c-.1-.1-.2-.2-.5-.3Z"/></svg>' ?>
                WhatsApp
            </a>
        </nav>
    </div>
</header>

<?php if ($flash): ?>
<div class="aviso aviso--<?= e($flash['tipo']) ?>" role="status">
    <div class="container"><?= e($flash['msg']) ?></div>
</div>
<?php endif; ?>

<main id="conteudo">
