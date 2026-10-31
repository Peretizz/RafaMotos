<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/src/MotoDAO.php';

$conexao = ConexaoBD::conectar();
$motos   = MotoDAO::listar($conexao);
$fotos   = MotoDAO::fotosPorMoto($conexao);
$admin   = is_admin();

// Prepara os dados de cada moto uma única vez
$lista = [];
$marcas = [];
foreach ($motos as $m) {
    $id   = (int)$m['idmoto'];
    $km   = so_numero($m['km']);
    $cc   = so_numero($m['cilindrada']);
    $marca = moto_marca($m['titulo']);
    $marcas[$marca] = ($marcas[$marca] ?? 0) + 1;
    $fs = array_map(function ($f) { return $f['caminho_foto']; }, $fotos[$id] ?? []);
    $lista[] = [
        'id' => $id, 'marca' => $marca,
        'modelo' => moto_modelo($m['titulo']), 'titulo' => titulo_bonito($m['titulo']),
        'ano' => moto_ano($m['titulo']), 'preco' => (float)$m['preco'],
        'km' => $km, 'cc' => $cc,
        'cat' => $cc <= 200 ? 'pequena' : ($cc <= 500 ? 'media' : 'grande'),
        'km_txt' => fmt_km($m['km']), 'cc_txt' => fmt_cc($m['cilindrada']),
        'cv_txt' => fmt_cv($m['potencia']), 'trans' => trim((string)$m['transmissao']) ?: '—',
        'fotos' => $fs,
    ];
}
arsort($marcas);
$total = count($lista);

$titulo_pagina = LOJA_NOME . ' | Motos usadas e seminovas em Videira - SC';
$descricao_pagina = "Confira {$total} motos disponíveis na Rafa Motos, em Videira - SC: fotos, preço, km e ficha técnica. Atendimento direto pelo WhatsApp.";
$pagina = 'home';
include __DIR__ . '/includes/topo.php';

$ico_zap = '<svg class="ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 3.5A11 11 0 0 0 3.2 17.4L2 22l4.7-1.2A11 11 0 1 0 20.5 3.5Zm-8.5 17a9.100 9.100 0 0 1-4.600-1.300l-.3-.2-2.800.7.8-2.700-.2-.3a9.100 9.100 0 1 1 7.100 3.800Zm5-6.800c-.3-.1-1.600-.8-1.900-.9s-.4-.1-.6.100l-.8 1c-.1.200-.3.200-.6.100a7.400 7.400 0 0 1-3.700-3.200c-.3-.5.300-.5.800-1.500.1-.2 0-.3 0-.5l-.9-2.100c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.300 3 3 0 0 0-.9 2.200 5.200 5.200 0 0 0 1.100 2.800 11.900 11.900 0 0 0 4.600 4.100c1.700.7 2.400.8 3.200.7a2.700 2.700 0 0 0 1.800-1.300 2.200 2.200 0 0 0 .2-1.300c-.1-.1-.2-.2-.5-.3Z"/></svg>';
?>

<!-- ============ HERO ============ -->
<section class="hero" id="inicio">
    <div class="container hero__in">
        <div class="hero__texto">
            <h1>Encontre a moto dos seus sonhos</h1>
            <p class="hero__sub">Motos selecionadas, fotos reais e atendimento direto pelo WhatsApp, aqui em Videira - SC.</p>

            <form class="busca" id="busca-hero" action="#motos" role="search" aria-label="Buscar moto">
                <label class="busca__campo">
                    <span>Marca</span>
                    <select id="hero-marca">
                        <option value="">Todas</option>
                        <?php foreach ($marcas as $mk => $n): ?><option value="<?= e($mk) ?>"><?= e($mk) ?></option><?php endforeach; ?>
                    </select>
                </label>
                <label class="busca__campo">
                    <span>Até quanto quer pagar?</span>
                    <select id="hero-preco">
                        <option value="">Qualquer valor</option>
                        <option value="15000">Até R$ 15 mil</option>
                        <option value="25000">Até R$ 25 mil</option>
                        <option value="40000">Até R$ 40 mil</option>
                    </select>
                </label>
                <button class="btn btn--verde" type="submit">Ver motos</button>
            </form>
            <p class="hero__estoque"><span class="ponto" aria-hidden="true"></span><?= $total ?> motos disponíveis agora</p>
        </div>
    </div>
</section>

<!-- ============ VITRINE ============ -->
<section class="vitrine" id="motos" data-total="<?= $total ?>">
    <div class="container">
        <header class="secao-cab">
            <h2>Motos à venda</h2>
            <p>Toque em uma moto para ver todas as fotos e a ficha completa.</p>
        </header>

        <div class="filtros" id="filtros">
            <div class="filtros__linha">
                <label class="filtro filtro--busca">
                    <span class="sr">Buscar por modelo</span>
                    <svg class="ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M10.500 3a7.500 7.500 0 0 1 5.900 12.100l4.300 4.300-1.400 1.400-4.300-4.300A7.500 7.500 0 1 1 10.500 3Zm0 2a5.500 5.500 0 1 0 0 11 5.500 5.500 0 0 0 0-11Z"/></svg>
                    <input type="search" id="f-busca" placeholder="Buscar modelo, ex.: Biz, CB 300, MT-03" autocomplete="off">
                </label>
                <label class="filtro">
                    <span class="sr">Ordenar por</span>
                    <select id="f-ordem">
                        <option value="recentes">Mais recentes no site</option>
                        <option value="preco-asc">Menor preço</option>
                        <option value="preco-desc">Maior preço</option>
                        <option value="km-asc">Menor quilometragem</option>
                        <option value="ano-desc">Mais novas</option>
                    </select>
                </label>
                <label class="filtro">
                    <span class="sr">Faixa de preço</span>
                    <select id="f-preco">
                        <option value="">Qualquer preço</option>
                        <option value="0-15000">Até R$ 15 mil</option>
                        <option value="0-25000">Até R$ 25 mil</option>
                        <option value="0-40000">Até R$ 40 mil</option>
                        <option value="40000-999999">Acima de R$ 40 mil</option>
                    </select>
                </label>
            </div>

            <div class="chips" role="group" aria-label="Marca">
                <button type="button" class="chip is-on" data-marca="">Todas <small><?= $total ?></small></button>
                <?php foreach ($marcas as $mk => $n): ?>
                    <button type="button" class="chip" data-marca="<?= e($mk) ?>"><?= e($mk) ?> <small><?= $n ?></small></button>
                <?php endforeach; ?>
            </div>
            <div class="chips" role="group" aria-label="Cilindrada">
                <button type="button" class="chip is-on" data-cat="">Todas as cilindradas</button>
                <button type="button" class="chip" data-cat="pequena">Até 200 cc</button>
                <button type="button" class="chip" data-cat="media">250 a 500 cc</button>
                <button type="button" class="chip" data-cat="grande">Acima de 500 cc</button>
                <button type="button" class="chip chip--fav" id="chip-fav" aria-pressed="false">
                    <svg class="ico" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-7.500-4.700-9.600-9.300C.9 8.200 2.800 4.500 6.400 4.500c2.100 0 3.800 1.100 5.600 3 1.800-1.900 3.500-3 5.600-3 3.600 0 5.500 3.700 4 7.200C19.500 16.300 12 21 12 21Z"/></svg>
                    Favoritas <small id="fav-count">0</small>
                </button>
            </div>
        </div>

        <p class="resultado" id="resultado" aria-live="polite"></p>

        <div class="grade" id="grade">
            <?php foreach ($lista as $i => $m):
                $capa = $m['fotos'][0] ?? null;
                $thumb = $capa ? foto_url($capa, 720) : '';
                $msg = "Olá! Tenho interesse na {$m['titulo']} que vi no site.";
                $baixa = $m['km'] > 0 && $m['km'] <= 5000;
            ?>
            <article class="card" id="moto-<?= $m['id'] ?>"
                data-id="<?= $m['id'] ?>" data-ordem="<?= $i ?>"
                data-marca="<?= e($m['marca']) ?>" data-cat="<?= $m['cat'] ?>"
                data-preco="<?= (int)$m['preco'] ?>" data-km="<?= $m['km'] ?>" data-ano="<?= (int)$m['ano'] ?>"
                data-busca="<?= e(mb_strtolower($m['titulo'] . ' ' . $m['marca'], 'UTF-8')) ?>">

                <div class="card__midia">
                    <?php if ($thumb): ?>
                        <span class="card__fundo" style="background-image:url('<?= e($thumb) ?>')" aria-hidden="true"></span>
                        <button type="button" class="card__abrir" data-abrir="<?= $m['id'] ?>" aria-label="Ver detalhes da <?= e($m['titulo']) ?>">
                            <img src="<?= e($thumb) ?>" alt="<?= e($m['titulo']) ?>" width="720" height="540" <?= $i < 4 ? '' : 'loading="lazy"' ?> decoding="async">
                        </button>
                    <?php else: ?>
                        <div class="card__sem-foto">Sem foto</div>
                    <?php endif; ?>
                    <?php if ($m['ano']): ?><span class="selo selo--ano"><?= $m['ano'] ?></span><?php endif; ?>
                    <?php if ($baixa): ?><span class="selo selo--baixa">Baixa km</span><?php endif; ?>
                    <?php if (count($m['fotos']) > 1): ?><span class="selo selo--fotos"><?= count($m['fotos']) ?> fotos</span><?php endif; ?>
                    <button type="button" class="fav" data-fav="<?= $m['id'] ?>" aria-pressed="false" aria-label="Favoritar <?= e($m['titulo']) ?>">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-7.500-4.700-9.600-9.300C.9 8.200 2.800 4.500 6.400 4.500c2.100 0 3.800 1.100 5.600 3 1.800-1.900 3.500-3 5.600-3 3.600 0 5.500 3.700 4 7.200C19.500 16.300 12 21 12 21Z"/></svg>
                    </button>
                </div>

                <div class="card__corpo">
                    <h3 class="card__titulo"><?= e($m['modelo']) ?></h3>
                    <p class="card__preco"><?= fmt_preco($m['preco']) ?></p>
                    <ul class="specs">
                        <li><span>Km</span><?= e($m['km_txt']) ?></li>
                        <li><span>Cilindrada</span><?= e($m['cc_txt']) ?></li>
                        <li><span>Potência</span><?= e($m['cv_txt']) ?></li>
                        <li><span>Câmbio</span><?= e($m['trans']) ?></li>
                    </ul>
                    <div class="card__acoes">
                        <button type="button" class="btn btn--contorno" data-abrir="<?= $m['id'] ?>">Ver detalhes</button>
                        <a class="btn btn--verde btn--icone" href="<?= e(wa_link($msg)) ?>" target="_blank" rel="noopener" aria-label="Chamar no WhatsApp sobre a <?= e($m['titulo']) ?>"><?= $ico_zap ?></a>
                    </div>
                    <?php if ($admin): ?>
                    <div class="card__admin">
                        <a href="editar-moto.php?id=<?= $m['id'] ?>">Editar</a>
                        <form method="post" action="src/MotoDAO.php?acao=excluir" onsubmit="return confirm('Excluir a <?= e(addslashes($m['titulo'])) ?>? As fotos também serão apagadas do servidor.')">
                            <?= csrf_campo() ?>
                            <input type="hidden" name="id" value="<?= $m['id'] ?>">
                            <button type="submit" class="link-perigo">Excluir</button>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <div class="vazio" id="vazio" hidden>
            <h3>Nenhuma moto com esses filtros</h3>
            <p>Tente outra faixa de preço ou limpe os filtros. Se não achou o que procura, chame no WhatsApp: a gente avisa quando chegar.</p>
            <div class="vazio__acoes">
                <button type="button" class="btn btn--contorno" id="limpar-filtros">Limpar filtros</button>
                <a class="btn btn--verde" href="<?= e(wa_link('Olá! Estou procurando uma moto e não encontrei no site.')) ?>" target="_blank" rel="noopener">Falar no WhatsApp</a>
            </div>
        </div>
    </div>
</section>

<!-- ============ SERVIÇOS (os três itens da logo) ============ -->
<section class="servicos" id="servicos">
    <div class="container">
        <header class="secao-cab secao-cab--claro">
            <h2>Mecânica, peças e vendas</h2>
            <p>Tudo o que a sua moto precisa em um só lugar.</p>
        </header>
        <div class="servicos__grade">
            <article class="servico">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.700 18.600 14.300 11.200a5.500 5.500 0 0 0-6.900-6.900l3.500 3.500-2.800 2.800L4.600 6.100a5.500 5.500 0 0 0 6.900 6.900l7.400 7.400a1.900 1.900 0 0 0 2.800-2.800Z"/></svg>
                <h3>Mecânica</h3>
                <p>Revisão, manutenção e reparos para manter a sua moto sempre pronta para rodar.</p>
                <a href="<?= "https://api.whatsapp.com/send/?phone=554935655098&text=Ol%C3%A1%21+Quero+saber+sobre+servi%C3%A7os+de+mec%C3%A2nica.&type=phone_number&app_absent=0"?>" target="_blank" rel="noopener">Falar sobre mecânica</a>
            </article>
            <article class="servico">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m12 2 2.400 2.300 3.300-.5.8 3.200 3 1.500-1.200 3.100 1.200 3.100-3 1.500-.8 3.200-3.300-.5L12 22l-2.400-2.300-3.300.5-.8-3.200-3-1.500L3.700 12 2.500 8.900l3-1.500.8-3.200 3.300.5L12 2Zm0 6.500a3.500 3.500 0 1 0 0 7 3.500 3.500 0 0 0 0-7Z"/></svg>
                <h3>Peças</h3>
                <p>Peças e acessórios para a sua moto. Diga o modelo e o ano e a gente consulta para você.</p>
                <a href="<?= "https://api.whatsapp.com/send/?phone=554935655098&text=Ol%C3%A1%21+Preciso+de+uma+pe%C3%A7a+para+a+minha+moto.&type=phone_number&app_absent=0"?>" target="_blank" rel="noopener">Pedir uma peça</a>
            </article>
            <article class="servico">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.400 11.600 12.400 2.600A2 2 0 0 0 11 2H4a2 2 0 0 0-2 2v7c0 .6.2 1.100.6 1.400l9 9c.4.4.9.600 1.400.600s1.100-.2 1.400-.6l7-7c.4-.4.600-.9.600-1.400s-.2-1.100-.6-1.400ZM5.500 7a1.500 1.500 0 1 1 0-3 1.500 1.500 0 0 1 0 3Z"/></svg>
                <h3>Vendas</h3>
                <p>Motos com fotos reais e ficha técnica completa. Escolha, converse e venha conhecer pessoalmente.</p>
                <a href="#motos">Ver motos à venda</a>
            </article>
        </div>
    </div>
</section>

<!-- ============ SOBRE ============ -->
<section class="sobre" id="sobre">
    <div class="container sobre__in">
        <div class="sobre__foto">
            <img src="img/sobre.jpg" alt="Equipe e loja da Rafa Motos" width="682" height="851" loading="lazy">
        </div>
        <div class="sobre__texto">
            <h2>Sobre a <?= e(LOJA_NOME) ?></h2>
            <p>Há <?= LOJA_ANOS ?> anos no mercado, a Rafa Motos é referência em qualidade e confiança quando o assunto é motocicleta. Nossa missão é proporcionar as melhores experiências sobre duas rodas.</p>
            <p>Contamos com uma equipe especializada e apaixonada por motos, pronta para ajudar você a escolher a moto ideal. Trabalhamos apenas com marcas e modelos de confiança.</p>
            <ul class="sobre__fatos">
                <li><strong><?= LOJA_ANOS ?> anos</strong> de mercado</li>
                <li><strong><?= $total ?> motos</strong> em estoque hoje</li>
                <li><strong>Videira - SC</strong> venha nos visitar</li>
            </ul>
            <a class="btn btn--verde" href="<?= e(wa_link('Olá! Gostaria de conversar com a equipe da Rafa Motos.')) ?>" target="_blank" rel="noopener"><?= $ico_zap ?> Conversar com a equipe</a>
        </div>
    </div>
</section>

<!-- ============ CONTATO ============ -->
<section class="contato" id="contato">
    <div class="container">
        <header class="secao-cab secao-cab--claro">
            <h2>Venha conhecer a loja</h2>
            <p><?= e(LOJA_ENDERECO) ?></p>
        </header>
        <div class="contato__grade">
            <div class="contato__cartoes">
                <a class="cartao" href="<?= e(wa_link('Olá! Vim pelo site da Rafa Motos.')) ?>" target="_blank" rel="noopener">
                    <strong>WhatsApp</strong><span><?= e(LOJA_TELEFONE) ?></span>
                </a>
                <?php if (LOJA_TELEFONE_FIXO): ?>
                <a class="cartao" href="tel:+55<?= e(preg_replace('/\D/', '', LOJA_TELEFONE_FIXO)) ?>">
                    <strong>Telefone fixo</strong><span><?= e(LOJA_TELEFONE_FIXO) ?></span>
                </a>
                <?php endif; ?>
                <a class="cartao" href="mailto:<?= e(LOJA_EMAIL) ?>">
                    <strong>E-mail</strong><span><?= e(LOJA_EMAIL) ?></span>
                </a>
                <a class="cartao" href="https://www.google.com/maps/search/?api=1&query=<?= rawurlencode(LOJA_ENDERECO) ?>" target="_blank" rel="noopener">
                    <strong>Endereço</strong><span><?= e(LOJA_ENDERECO) ?></span>
                </a>
                <a class="btn btn--verde btn--cheio" href="https://www.google.com/maps/dir/?api=1&destination=<?= rawurlencode(LOJA_ENDERECO) ?>" target="_blank" rel="noopener">Como chegar</a>
            </div>
            <div class="mapa">
                <iframe title="Mapa da Rafa Motos" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                    src="https://maps.google.com/maps?q=<?= rawurlencode(LOJA_ENDERECO) ?>&output=embed"></iframe>
            </div>
        </div>
    </div>
</section>

<!-- ============ DETALHES DA MOTO (janela) ============ -->
<div class="modal" id="modal" role="dialog" aria-modal="true" aria-labelledby="m-titulo" hidden>
    <div class="modal__fundo" data-fechar></div>
    <div class="modal__caixa" tabindex="-1">
        <button type="button" class="modal__fechar" data-fechar aria-label="Fechar">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m5.400 4 6.600 6.600L18.600 4 20 5.400 13.400 12l6.600 6.600-1.400 1.400-6.600-6.600L5.400 20 4 18.600l6.600-6.600L4 5.400 5.400 4Z"/></svg>
        </button>
        <div class="galeria">
            <div class="galeria__palco" id="m-palco">
                <img id="m-img" alt="">
                <button type="button" class="seta seta--ant" id="m-ant" aria-label="Foto anterior">‹</button>
                <button type="button" class="seta seta--prox" id="m-prox" aria-label="Próxima foto">›</button>
                <span class="galeria__contador" id="m-contador"></span>
            </div>
            <div class="galeria__mini" id="m-mini"></div>
        </div>
        <div class="detalhe">
            <h3 id="m-titulo"></h3>
            <p class="detalhe__preco" id="m-preco"></p>
            <dl class="detalhe__ficha" id="m-ficha"></dl>

            <?php if (SIMULADOR_ATIVO): ?>
            <details class="sim" id="m-sim">
                <summary>Simular parcelas</summary>
                <div class="sim__corpo">
                    <label>Entrada
                        <input type="text" inputmode="numeric" id="sim-entrada" value="0" autocomplete="off">
                    </label>
                    <label>Prazo
                        <select id="sim-prazo">
                            <?php foreach (SIMULADOR_PRAZOS as $p): ?><option value="<?= $p ?>"<?= $p === 48 ? ' selected' : '' ?>><?= $p ?>x</option><?php endforeach; ?>
                        </select>
                    </label>
                    <p class="sim__res" id="sim-res" aria-live="polite"></p>
                    <p class="sim__aviso">Simulação aproximada, sem compromisso. Taxa e aprovação dependem da análise de crédito.</p>
                </div>
            </details>
            <?php endif; ?>

            <div class="detalhe__acoes">
                <a class="btn btn--verde btn--cheio" id="m-zap" href="#" target="_blank" rel="noopener"><?= $ico_zap ?> Tenho interesse</a>
                <button type="button" class="btn btn--contorno" id="m-compartilhar">Compartilhar</button>
            </div>
        </div>
    </div>
</div>

<script>
window.RAFA = {
    zap: <?= json_encode(LOJA_WHATSAPP) ?>,
    taxa: <?= json_encode(SIMULADOR_TAXA_MENSAL) ?>,
    motos: <?= json_encode(array_map(function ($m) {
        $m['thumbs'] = array_map(function ($f) { return foto_url($f, 1100); }, $m['fotos']);
        $m['mini']   = array_map(function ($f) { return foto_url($f, 160); }, $m['fotos']);
        unset($m['fotos']);
        return $m;
    }, $lista), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?>
};
</script>

<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org', '@type' => 'AutoDealer',
    'name' => LOJA_NOME, 'telephone' => LOJA_TELEFONE, 'email' => LOJA_EMAIL,
    'image' => url_base() . '/img/og-image.jpg', 'url' => url_base() . '/',
    'address' => ['@type' => 'PostalAddress', 'streetAddress' => 'R. Bulcão Viana, 695', 'addressLocality' => 'Videira', 'addressRegion' => 'SC', 'addressCountry' => 'BR'],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) ?>
</script>

<?php include __DIR__ . '/includes/rodape.php'; ?>
