</main>

<footer class="rodape" id="contato-rodape">
    <div class="container rodape__in">
        <div>
            <img class="rodape__logo" src="img/logo-site.png" alt="<?= e(LOJA_NOME) ?>" width="383" height="214" loading="lazy">
        </div>
        <div>
            <h3>Navegue</h3>
            <ul class="rodape__lista">
                <li><a href="index.php#motos">Motos à venda</a></li>
                <li><a href="index.php#servicos">Serviços</a></li>
                <li><a href="index.php#sobre">Sobre a loja</a></li>
                <li><a href="index.php#contato">Como chegar</a></li>
                <?php if (!is_admin()): ?><li><a href="login.php">Área restrita</a></li><?php endif; ?>
            </ul>
        </div>
        <div>
            <h3>Fale com a gente</h3>
            <ul class="rodape__lista">
                <li><a href="<?= e(wa_link()) ?>" target="_blank" rel="noopener"><?= e(LOJA_TELEFONE) ?></a></li>
                <?php if (LOJA_TELEFONE_FIXO): ?><li><a href="tel:+55<?= e(preg_replace('/\D/', '', LOJA_TELEFONE_FIXO)) ?>"><?= e(LOJA_TELEFONE_FIXO) ?></a></li><?php endif; ?>
                <li><a href="mailto:<?= e(LOJA_EMAIL) ?>"><?= e(LOJA_EMAIL) ?></a></li>
                <li><?= e(LOJA_ENDERECO) ?></li>
                <?php if (LOJA_INSTAGRAM): ?><li><a href="<?= e(LOJA_INSTAGRAM) ?>" target="_blank" rel="noopener">Instagram</a></li><?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="container rodape__base">
        <p>© <?= date('Y') ?> <?= e(LOJA_NOME) ?>. Todos os direitos reservados.</p>
        <p>Desenvolvido por Nicolas Pereti</p>
    </div>
</footer>

<?php if ($pagina === 'home'): ?>
<a class="wa-flutuante" href="<?= e(wa_link('Olá! Vim pelo site da Rafa Motos.')) ?>" target="_blank" rel="noopener" aria-label="Chamar no WhatsApp">
    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 3.5A11 11 0 0 0 3.2 17.4L2 22l4.7-1.2A11 11 0 1 0 20.5 3.5Zm-8.5 17a9.100 9.100 0 0 1-4.600-1.300l-.3-.2-2.800.7.8-2.700-.2-.3a9.100 9.100 0 1 1 7.100 3.800Zm5-6.800c-.3-.1-1.600-.8-1.900-.9s-.4-.1-.6.100l-.8 1c-.1.200-.3.200-.6.100a7.400 7.400 0 0 1-3.700-3.200c-.3-.5.300-.5.800-1.500.1-.2 0-.3 0-.5l-.9-2.100c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.300 3 3 0 0 0-.9 2.200 5.200 5.200 0 0 0 1.100 2.800 11.900 11.900 0 0 0 4.600 4.100c1.700.7 2.400.8 3.200.7a2.700 2.700 0 0 0 1.800-1.300 2.200 2.200 0 0 0 .2-1.300c-.1-.1-.2-.2-.5-.3Z"/></svg>
</a>
<?php endif; ?>

<script src="js/app.js?v=<?= @filemtime(__DIR__ . '/../js/app.js') ?: 1 ?>" defer></script>
</body>
</html>
