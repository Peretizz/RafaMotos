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
            <div class="header-content"> <a href="#" class="logo"><img src="img/logo.png" style="width: 100px;"></a>
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
                    <p>Há 2 anos no mercado, a Rafa Motos é referência em qualidade e confiança quando o assunto é motocicletas. Nossa missão é proporcionar aos clientes as melhores experiências sobre duas rodas, com produtos de alta qualidade e um atendimento personalizado.</p>
                    <p>Contamos com uma equipe especializada e apaixonada por motos, pronta para oferecer o melhor aconselhamento na escolha da moto ideal para cada perfil de piloto. Trabalhamos apenas com as melhores marcas e modelos, garantindo procedência e satisfação total.</p>
                    <p>Venha nos fazer uma visita e descubra por que somos a escolha certa para quem busca qualidade, confiança e preço justo no mundo das motos.</p>
                </div>
                <div class="sobre-imagem">
                    <img src="img/sobre.jpg" />
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
                        'imagem' => 'img/KAWAZAKI.jpg',
                        'nome' => 'KAWAZAKI NINJA 300 2015',
                        'preco' => 'R$ 19.500,00',
                        'especificacoes' => array(
                            'Cilindrada' => '296 cc',
                            'Potência' => '39 cv',
                            'Transmissão' => '6 marchas',
                            'Peso' => '172 kg'
                        )
                    ),
                    array(
                        'imagem' => 'img/KAWAZAKI.jpg',
                        'nome' => 'KAWAZAKI NINJA 300 2015',
                        'preco' => 'R$ 19.500,00',
                        'especificacoes' => array(
                            'Cilindrada' => '296 cc',
                            'Potência' => '39 cv',
                            'Transmissão' => '6 marchas',
                            'Peso' => '172 kg'
                        )
                    ),
                    array(
                        'imagem' => 'img/KAWAZAKI.jpg',
                        'nome' => 'KAWAZAKI NINJA 300 2015',
                        'preco' => 'R$ 19.500,00',
                        'especificacoes' => array(
                            'Cilindrada' => '296 cc',
                            'Potência' => '39 cv',
                            'Transmissão' => '6 marchas',
                            'Peso' => '172 kg'
                        )
                    ),
                );

                // Loop através das motos para gerar os cards
                foreach ($motos as $moto) {
                    $whatsapp_link = "https://wa.me/554998112215?text=Boa%20tarde,%20estou%20interessado%20na%20" . urlencode($moto['nome']);
                    echo '
                <div class="produto-card">
                    <div class="produto-image">
                        <img src="' . $moto['imagem'] . '" />
                    </div>
                    <div class="produto-info">
                        <h3 class="produto-titulo">' . $moto['nome'] . '</h3>
                        <p class="produto-preco">' . $moto['preco'] . '</p>
                        
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

    <!-- Footer -->
    <footer id="contato">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Rafa Motos</h3>
                    <p>Sua loja especializada em motos de qualidade.</p>
                </div>
                <div class="footer-section">
                    <h3>Contato</h3>
                    <p>Email: nickperetivendas@gmail.com</p>
                    <p>Telefone: (49) 99811-2215</p>
                    <p>Endereço: R. Bulcão Viana, 695 - Floresta, Videira - SC, 89560-051</p>
                </div>
                <div class="footer-section">
                    <h3>Horário de Funcionamento</h3>
                    <p>Segunda a Sexta: 8h às 12h 13:30h às 18:30h</p>
                    <p>Sábado: 8h às 12h</p>
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
                <p>© 2024 Rafa Motos - Todos os direitos reservados | Criado por Nicolas Pereti</p>
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