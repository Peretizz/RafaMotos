<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Rafa Motos </title>
</head>

<body> <!-- Header -->
    <header>
        <div class="container">
            <div class="header-content"> <a href="#" class="logo">Rafa<span>Motos</span></a>
                <nav>
                    <ul>
                        <li><a href="#inicio">Início</a></li>
                        <li><a href="#sobre">Sobre</a></li>
                        <li><a href="#motos">Motos</a></li>
                        <li><a href="#contato">Contato</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <!-- Hero Section -->
    <section class="hero" id="inicio">
        <div class="container">
            <h1>Encontre a Moto dos Seus Sonhos</h1>
            <p>As melhores motos com qualidade garantida e preços imbatíveis na Rafa Motos</p>
            <a href="#motos" class="btn">Ver Motos</a>
        </div>
    </section>

    <!-- Sobre a Empresa -->
    <section class="sobre-empresa" id="sobre">
        <div class="container">
            <div class="sobre-content">
                <div class="sobre-texto">
                    <h2>Sobre a Rafa Motos</h2>
                    <p>Há mais de 10 anos no mercado, a Rafa Motos é referência em qualidade e confiança quando o assunto é motocicletas. Nossa missão é proporcionar aos clientes as melhores experiências sobre duas rodas, com produtos de alta qualidade e um atendimento personalizado.</p>
                    <p>Contamos com uma equipe especializada e apaixonada por motos, pronta para oferecer o melhor aconselhamento na escolha da moto ideal para cada perfil de piloto. Trabalhamos apenas com as melhores marcas e modelos, garantindo procedência e satisfação total.</p>
                    <p>Venha nos fazer uma visita e descubra por que somos a escolha certa para quem busca qualidade, confiança e preço justo no mundo das motos.</p>
                </div>
                <div class="sobre-imagem">
                    <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/7c5668f2-b0eb-4ca2-9aad-ef01129b4e20.png" alt="Fachada da loja Rafa Motos com diversas motos em exposição" />
                </div>
            </div>
        </div>
    </section>

    <!-- Produtos - Motos -->
    <section class="produtos" id="motos">
        <div class="container">
            <h2 class="section-title">Nossas Motos</h2>
            <div class="produtos-grid">
                <?php
                // Matriz com as informações das motos
                $motos = array(
                    array(
                        'imagem' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/1c408920-6f1b-4aca-b08a-193af77a4f14.png',
                        'alt' => 'Honda CB 650R vermelha com detalhes em preto, estilo naked sport',
                        'nome' => 'Honda CB 650R',
                        'preco' => 'R$ 42.990,00',
                        'descricao' => 'Moto naked sport com design agressivo e performance excepcional para o dia a dia e diversão nas estradas.',
                        'especificacoes' => array(
                            'Cilindrada' => '649cc',
                            'Potência' => '95 cv',
                            'Transmissão' => '6 marchas',
                            'Peso' => '202 kg'
                        )
                    ),
                    array(
                        'imagem' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/481e022c-b7f0-4220-b884-add0922a8e13.png',
                        'alt' => 'Yamaha MT-09 preta com detalhes em azul, estilo hyper naked',
                        'nome' => 'Yamaha MT-09',
                        'preco' => 'R$ 56.800,00',
                        'descricao' => 'Hyper naked com motor CP3 de 3 cilindros que oferece torque impressionante em baixas rotações.',
                        'especificacoes' => array(
                            'Cilindrada' => '890cc',
                            'Potência' => '119 cv',
                            'Transmissão' => '6 marchas',
                            'Peso' => '189 kg'
                        )
                    ),
                    array(
                        'imagem' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/94309f95-4210-4063-9bfd-de425c1b7946.png',
                        'alt' => 'BMW R 1250 GS Adventure azul com alforges, estilo adventure tourer',
                        'nome' => 'BMW R 1250 GS Adventure',
                        'preco' => 'R$ 89.900,00',
                        'descricao' => 'Aventureira premium com motor boxer de 1254cc, ideal para viagens longas e terrenos desafiadores.',
                        'especificacoes' => array(
                            'Cilindrada' => '1254cc',
                            'Potência' => '136 cv',
                            'Transmissão' => '6 marchas',
                            'Peso' => '268 kg'
                        )
                    ),
                    array(
                        'imagem' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/2e5b7b5f-5d3a-4d9e-9a4a-5c5b5a5a5a5a.png',
                        'alt' => 'Kawasaki Ninja ZX-6R verde com detalhes em preto, estilo supersport',
                        'nome' => 'Kawasaki Ninja ZX-6R',
                        'preco' => 'R$ 68.500,00',
                        'descricao' => 'Supersport de alta performance com aerodinâmica agressiva e tecnologia de ponta para pista e estrada.',
                        'especificacoes' => array(
                            'Cilindrada' => '636cc',
                            'Potência' => '130 cv',
                            'Transmissão' => '6 marchas',
                            'Peso' => '196 kg'
                        )
                    ),
                    array(
                        'imagem' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/3e5b7b5f-5d3a-4d9e-9a4a-5c5b5a5a5a5a.png',
                        'alt' => 'Ducati Monster Plus vermelha com detalhes em preto, estilo naked',
                        'nome' => 'Ducati Monster Plus',
                        'preco' => 'R$ 52.900,00',
                        'descricao' => 'Ícone italiano do segmento naked com design minimalista e desempenho esportivo característico da Ducati.',
                        'especificacoes' => array(
                            'Cilindrada' => '937cc',
                            'Potência' => '111 cv',
                            'Transmissão' => '6 marchas',
                            'Peso' => '188 kg'
                        )
                    ),
                    array(
                        'imagem' => 'https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/4e5b7b5f-5d3a-4d9e-9a4a-5c5b5a5a5a5a.png',
                        'alt' => 'Harley-Davidson Sportster S preta com detalhes cromados, estilo cruiser',
                        'nome' => 'Harley-Davidson Sportster S',
                        'preco' => 'R$ 75.800,00',
                        'descricao' => 'Cruiser moderna com motor Revolution Max 1250T que combina tradição Harley com tecnologia contemporânea.',
                        'especificacoes' => array(
                            'Cilindrada' => '1252cc',
                            'Potência' => '121 cv',
                            'Transmissão' => '6 marchas',
                            'Peso' => '228 kg'
                        )
                    )
                );

                // Loop através das motos para gerar os cards
                foreach ($motos as $moto) {
                    $whatsapp_link = "https://wa.me/554998112215?text=Boa%20tarde,%20estou%20interessado%20na%20" . urlencode($moto['nome']);
                    echo '
                <div class="produto-card">
                    <div class="produto-image">
                        <img src="' . $moto['imagem'] . '" alt="' . $moto['alt'] . '" />
                    </div>
                    <div class="produto-info">
                        <h3 class="produto-titulo">' . $moto['nome'] . '</h3>
                        <p class="produto-preco">' . $moto['preco'] . '</p>
                        <p class="produto-descricao">' . $moto['descricao'] . '</p>
                        
                        <div class="produto-especificacoes">';

                    foreach ($moto['especificacoes'] as $chave => $valor) {
                        echo '
                            <div class="especificacao">
                                <span>' . $chave . ':</span>
                                <span>' . $valor . '</span>
                            </div>';
                    }

                    echo '
                        </div>
                        
                        <a href="' . $whatsapp_link . '" class="btn btn-whatsapp" target="_blank">
                            Consultar no WhatsApp
                        </a>
                    </div>
                </div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Destaque Especial -->
    <section class="destaques">
        <div class="container">
            <div class="destaque-card">
                <h2>Oferta Especial do Mês</h2>
                <p>Financiamento em até 60x sem entrada</p>
                <p>Taxas especiais para clientes Rafa Motos</p>
                <a href="https://wa.me/554998112215?text=Boa%20tarde,%20gostaria%20de%20saber%20mais%20sobre%20o%20financiamento" class="btn btn-whatsapp" target="_blank">
                    Solicitar Condições
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contato">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Rafa Motos</h3>
                    <p>Sua loja especializada em motos de alta qualidade.</p>
                </div>
                <div class="footer-section">
                    <h3>Contato</h3>
                    <p>Email: contato@rafamotos.com.br</p>
                    <p>Telefone: (49) 98112-2155</p>
                    <p>Endereço: Rua das Motos, 123 - Sua Cidade</p>
                </div>
                <div class="footer-section">
                    <h3>Horário de Funcionamento</h3>
                    <p>Segunda a Sexta: 8h às 12h e 13:30h às 18:30h</p>
                    <p>Sábado: 9h às 16h</p>
                    <p>Domingo: Fechado</p>
                </div>
                <div class="footer-section">
                    <h3>Atendimento</h3>
                    <a href="https://wa.me/554998112215" class="btn btn-whatsapp" target="_blank" style="margin-top: 10px;">
                        WhatsApp Direto
                    </a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2024 Rafa Motos - Todos os direitos reservados</p>
            </div>
        </div>
    </footer>

    <script>
        // JavaScript para interações
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scroll para links internos
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Efeito de hover nos produtos
            const produtoCards = document.querySelectorAll('.produto-card');
            produtoCards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.boxShadow = '0 12px 35px rgba(0,0,0,0.2)';
                });
                card.addEventListener('mouseleave', () => {
                    card.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
                });
            });
        });
    </script>
</body>

</html>