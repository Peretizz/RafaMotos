<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Rafa Motos </title>
</head>

<body> 
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
    
    <section class="hero" id="inicio">
        <div class="container">
            <h1>Encontre a Moto dos Seus Sonhos</h1>
            <p>As melhores motos com qualidade garantida e preços imbatíveis na Rafa Motos</p>
            <a href="#motos" class="btn">Ver Motos</a>
        </div>
    </section>

    <section class="sobre-empresa" id="sobre">
        <div class="container">
            <div class="sobre-content">
                <div class="sobre-texto">
                    <h2>Sobre Nós</h2>
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

    <section class="produtos" id="motos">
        <div class="container">
            <h2 class="section-title">Nossas Motos</h2>
            <div class="produtos-grid">
                <?php
                $motos = array(
                    array(
                        'imagens' => array(
                            'img/KAWAZAKI.jpg',
                            'img/KAWAZAKI-2.jpg',
                            'img/KAWAZAKI-3.jpg',
                            'img/KAWAZAKI-4.jpg'
                        ),
                        'nome' => 'KAWAZAKI NINJA 300 2015',
                        'preco' => 'R$ 19.500,00',
                        'especificacoes' => array(
                            'Kilometragem' => '17.000 km',
                            'Cilindrada' => '296 cc',
                            'Potência' => '39 cv',
                            'Transmissão' => '6 marchas',
                            'Peso' => '172 kg'
                        )
                    ),
                    array(
                        'imagens' => array(
                            'img/HONDA-CBR.jpg',
                            'img/HONDA-CBR-2.jpg',
                            'img/HONDA-CBR-3.jpg'
                        ),
                        'nome' => 'HONDA CBR 600RR 2018',
                        'preco' => 'R$ 32.900,00',
                        'especificacoes' => array(
                            'Kilometragem' => '12.500 km',
                            'Cilindrada' => '599 cc',
                            'Potência' => '118 cv',
                            'Transmissão' => '6 marchas',
                            'Peso' => '194 kg'
                        )
                    ),
                    array(
                        'imagens' => array(
                            'img/YAMAHA-R3.jpg',
                            'img/YAMAHA-R3-2.jpg',
                            'img/YAMAHA-R3-3.jpg',
                            'img/YAMAHA-R3-4.jpg',
                            'img/YAMAHA-R3-5.jpg'
                        ),
                        'nome' => 'YAMAHA YZF-R3 2020',
                        'preco' => 'R$ 24.800,00',
                        'especificacoes' => array(
                            'Kilometragem' => '8.200 km',
                            'Cilindrada' => '321 cc',
                            'Potência' => '42 cv',
                            'Transmissão' => '6 marchas',
                            'Peso' => '169 kg'
                        )
                    ),
                );

                foreach ($motos as $index => $moto) {
                    $whatsapp_link = "https://wa.me/554998112215?text=Boa%20tarde,%20estou%20interessado%20na%20" . urlencode($moto['nome']);
                    echo '
                <div class="produto-card">
                    <div class="produto-image">
                        <div class="carousel-container" data-carousel="' . $index . '">
                            <div class="carousel-track">';
                    
                    foreach ($moto['imagens'] as $imgIndex => $imagem) {
                        $activeClass = $imgIndex === 0 ? ' active' : '';
                        echo '<img src="' . $imagem . '" class="carousel-image' . $activeClass . '" alt="' . $moto['nome'] . '" />';
                    }
                    
                    echo '
                            </div>
                            <button class="carousel-btn carousel-prev" onclick="changeSlide(' . $index . ', -1)">‹</button>
                            <button class="carousel-btn carousel-next" onclick="changeSlide(' . $index . ', 1)">›</button>
                            <div class="carousel-indicators">';
                    
                    foreach ($moto['imagens'] as $imgIndex => $imagem) {
                        $activeClass = $imgIndex === 0 ? ' active' : '';
                        echo '<span class="indicator' . $activeClass . '" onclick="goToSlide(' . $index . ', ' . $imgIndex . ')"></span>';
                    }
                    
                    echo '
                            </div>
                        </div>
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
                <p>© 2025 Rafa Motos - Todos os direitos reservados | Criado por Nicolas Pereti</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            const produtoCards = document.querySelectorAll('.produto-card');
            produtoCards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.boxShadow = '0 12px 35px rgba(0,0,0,0.2)';
                });
                card.addEventListener('mouseleave', () => {
                    card.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
                });
            });

            document.querySelectorAll('.carousel-container').forEach(container => {
                const carouselId = container.getAttribute('data-carousel');
                currentSlides[carouselId] = 0;
            });
        });

        let currentSlides = {};

        function changeSlide(carouselId, direction) {
            const container = document.querySelector(`[data-carousel="${carouselId}"]`);
            const images = container.querySelectorAll('.carousel-image');
            const indicators = container.querySelectorAll('.indicator');
            
            images[currentSlides[carouselId]].classList.remove('active');
            indicators[currentSlides[carouselId]].classList.remove('active');
            
            currentSlides[carouselId] += direction;
            
            if (currentSlides[carouselId] >= images.length) {
                currentSlides[carouselId] = 0;
            } else if (currentSlides[carouselId] < 0) {
                currentSlides[carouselId] = images.length - 1;
            }
            
            images[currentSlides[carouselId]].classList.add('active');
            indicators[currentSlides[carouselId]].classList.add('active');
        }

        function goToSlide(carouselId, slideIndex) {
            const container = document.querySelector(`[data-carousel="${carouselId}"]`);
            const images = container.querySelectorAll('.carousel-image');
            const indicators = container.querySelectorAll('.indicator');
            
            images[currentSlides[carouselId]].classList.remove('active');
            indicators[currentSlides[carouselId]].classList.remove('active');
            
            currentSlides[carouselId] = slideIndex;
            
            images[currentSlides[carouselId]].classList.add('active');
            indicators[currentSlides[carouselId]].classList.add('active');
        }

        setInterval(() => {
            Object.keys(currentSlides).forEach(carouselId => {
                changeSlide(parseInt(carouselId), 1);
            });
        }, 5000); 
    </script>
</body>

</html>
