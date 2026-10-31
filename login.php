<?php
require_once __DIR__ . '/includes/helpers.php';
if (is_admin()) { header('Location: index.php'); exit; }
$titulo_pagina = 'Entrar | ' . LOJA_NOME;
$pagina = 'login';
include __DIR__ . '/includes/topo.php';
?>
<section class="painel">
    <div class="container">
        <form class="form-cartao form-cartao--estreito" action="efetua-login.php" method="post">
            <h1>Área restrita</h1>
            <p class="form-cartao__sub">Acesso do administrador para cadastrar e editar motos.</p>
            <?= csrf_campo() ?>
            <div class="campo">
                <label for="idusuario">Número do usuário</label>
                <input type="number" id="idusuario" name="idusuario" inputmode="numeric" min="1" required autofocus>
            </div>
            <div class="campo">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" autocomplete="current-password" required>
            </div>
            <button type="submit" class="btn btn--verde btn--cheio">Entrar</button>
            <p class="form-cartao__rodape"><a href="index.php">Voltar ao site</a></p>
        </form>
    </div>
</section>
<?php include __DIR__ . '/includes/rodape.php'; ?>
