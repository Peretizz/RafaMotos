<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/src/MotoDAO.php';
exigir_admin();

$a = MotoDAO::analisarImagens(ConexaoBD::conectar());
$titulo_pagina = 'Limpar imagens | ' . LOJA_NOME;
$pagina = 'admin';
include __DIR__ . '/includes/topo.php';
?>
<section class="painel">
    <div class="container">
        <div class="form-cartao">
            <h1>Limpar imagens não usadas</h1>
            <p class="form-cartao__sub">Procura na pasta <code>img/</code> as fotos que não pertencem a nenhuma moto cadastrada, ou seja, sobras de motos já excluídas.</p>

            <div class="resumo">
                <div><strong><?= $a['total'] ?></strong><span>imagens na pasta</span></div>
                <div><strong><?= $a['usadas'] ?></strong><span>em uso pelas motos</span></div>
                <div class="<?= $a['orfas'] ? 'resumo--alerta' : '' ?>"><strong><?= count($a['orfas']) ?></strong><span>sem uso (<?= e(fmt_bytes($a['bytes'])) ?>)</span></div>
            </div>

            <?php if ($a['orfas']): ?>
                <ul class="orfas">
                    <?php foreach ($a['orfas'] as $o): ?>
                        <li><span><?= e($o['nome']) ?></span><em><?= e(fmt_bytes($o['bytes'])) ?></em></li>
                    <?php endforeach; ?>
                </ul>
                <p class="form-cartao__sub">Fotos da logo, do fundo e da seção "Sobre" nunca são apagadas. Confira a lista acima: depois de apagar, não dá para recuperar.</p>
                <form method="post" action="src/MotoDAO.php?acao=limpar" onsubmit="return confirm('Apagar <?= count($a['orfas']) ?> arquivo(s) definitivamente?')">
                    <?= csrf_campo() ?>
                    <div class="form-cartao__botoes">
                        <button type="submit" class="btn btn--perigo">Apagar <?= count($a['orfas']) ?> arquivo(s) sem uso</button>
                        <a class="btn btn--contorno" href="index.php">Voltar</a>
                    </div>
                </form>
            <?php else: ?>
                <p class="ok-msg">Tudo limpo: não há nenhuma imagem sobrando no servidor.</p>
                <div class="form-cartao__botoes"><a class="btn btn--contorno" href="index.php">Voltar ao site</a></div>
            <?php endif; ?>

            <?php if ($a['quebradas']): ?>
                <p class="aviso-inline">Atenção: <?= count($a['quebradas']) ?> foto(s) está(ão) cadastrada(s) no banco, mas o arquivo não existe na pasta. Esses anúncios aparecem sem foto.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php include __DIR__ . '/includes/rodape.php'; ?>
