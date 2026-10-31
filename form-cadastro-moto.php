<?php
require_once __DIR__ . '/includes/helpers.php';
exigir_admin();
$titulo_pagina = 'Cadastrar moto | ' . LOJA_NOME;
$pagina = 'admin';
$moto = null;
include __DIR__ . '/includes/topo.php';
?>
<section class="painel">
    <div class="container">
        <form class="form-cartao" action="src/MotoDAO.php?acao=cadastrar" method="post" enctype="multipart/form-data">
            <h1>Cadastrar nova moto</h1>
            <p class="form-cartao__sub">Preencha os dados e envie as fotos. Elas são reduzidas automaticamente para o site carregar rápido e ocupar pouco espaço.</p>
            <?= csrf_campo() ?>
            <?php include __DIR__ . '/includes/form-moto.php'; ?>

            <div class="campo">
                <label for="fotos">Fotos da moto</label>
                <input type="file" id="fotos" name="fotos[]" accept="image/jpeg,image/png,image/webp" multiple required data-previa="#previa">
                <small>Selecione várias de uma vez (até <?= FOTO_MAX_POR_MOTO ?>). A primeira será a capa do anúncio.</small>
                <div class="previa" id="previa"></div>
            </div>

            <div class="form-cartao__botoes">
                <button type="submit" class="btn btn--verde">Publicar anúncio</button>
                <a class="btn btn--contorno" href="index.php#motos">Cancelar</a>
            </div>
        </form>
    </div>
</section>
<?php include __DIR__ . '/includes/rodape.php'; ?>
