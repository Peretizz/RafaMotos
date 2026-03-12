<?php
session_start();
require_once "src/ConexaoBD.php";
$conexao = ConexaoBD::conectar();

// Busca as motos cadastradas no banco
$sql = "SELECT * FROM motos ORDER BY idmoto DESC";
$stmt = $conexao->query($sql);
$motos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Rafa Motos</title>
</head>

<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo"><img src="img/logo.png" alt="Rafa Motos"></div>
                <div class="menu-toggle" id="menu-toggle">☰</div>
                <nav id="nav">
                    <ul>
                        <li><a href="#inicio">Início</a></li>
                        <li><a href="#sobre">Sobre</a></li>
                        <li><a href="#motos">Motos</a></li>
                        <li><a href="#contato">Contato</a></li>
                        <?php if (isset($_SESSION['logado'])): ?>
                            <li><a href="cadastro_moto.php">Cadastrar</a></li>
                            <li><a href="logout.php" style="color: #ff4d4d;">Sair</a></li>
                        <?php endif; ?>
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
                    <p>Há 2 anos no mercado, a Rafa Motos é referência em qualidade e confiança quando o assunto é motocicletas. Nossa missão é proporcionar aos clientes as melhores experiências sobre duas rodas.</p>
                    <p>Contamos com uma equipe especializada e apaixonada por motos, pronta para oferecer o melhor aconselhamento na escolha da moto ideal. Trabalhamos apenas com as melhores marcas e modelos.</p>
                </div>
                <div class="sobre-imagem">
                    <img src="img/sobre.jpg" alt="Sobre a Rafa Motos" />
                </div>
            </div>
        </div>
    </section>

    <section class="produtos" id="motos">
        <div class="container">
            <h2 class="section-title">Nossas Motos</h2>
            <div class="produtos-grid">
                <?php foreach ($motos as $moto):
                    $idM = $moto['idmoto'];
                    $stmtFoto = $conexao->prepare("SELECT caminho_foto FROM fotos_motos WHERE idmoto = ?");
                    $stmtFoto->execute([$idM]);
                    $fotos = $stmtFoto->fetchAll(PDO::FETCH_ASSOC);
                ?>
                    <div class="produto-card">
                        <div class="produto-image">
                            <div class="carousel-container" data-carousel="<?php echo $idM; ?>">
                                <div class="carousel-track">
                                    <?php foreach ($fotos as $index => $foto): ?>
                                        <img src="img/<?php echo $foto['caminho_foto']; ?>"
                                            class="carousel-image <?php echo $index === 0 ? 'active' : ''; ?>"
                                            alt="<?php echo $moto['titulo']; ?>" />
                                    <?php endforeach; ?>
                                </div>
                                <?php if (count($fotos) > 1): ?>
                                    <button class="carousel-btn carousel-prev" onclick="changeSlide(<?php echo $idM; ?>, -1)">‹</button>
                                    <button class="carousel-btn carousel-next" onclick="changeSlide(<?php echo $idM; ?>, 1)">›</button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="produto-info">
                            <h3 class="produto-titulo"><?php echo $moto['titulo']; ?></h3>
                            <p class="produto-preco">R$ <?php echo number_format($moto['preco'], 2, ',', '.'); ?></p>
                            <div class="produto-especificacoes">
                                <div class="especificacao"><span>Kilometragem:</span><span><?php echo $moto['km']; ?></span></div>
                                <div class="especificacao"><span>Cilindrada:</span><span><?php echo $moto['cilindrada']; ?></span></div>
                                <div class="especificacao"><span>Potência:</span><span><?php echo $moto['potencia']; ?></span></div>
                                <div class="especificacao"><span>Transmissão:</span><span><?php echo $moto['transmissao']; ?></span></div>
                            </div>
                            <a href="https://wa.me/554998112215?text=Olá,%20interesse%20na%20<?php echo urlencode($moto['titulo']); ?>"
                                class="btn btn-whatsapp" target="_blank">WhatsApp</a>

                            <?php if (isset($_SESSION['logado'])): ?>
                                <a href="src/MotoDAO.php?acao=excluir&id=<?php echo $idM; ?>"
                                    style="background: #000; color: #fff; display: block; text-align: center; padding: 10px; margin-top: 10px; border-radius: 5px;"
                                    onclick="return confirm('Excluir?')">🗑️ Excluir</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
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
                    <p>Email: rafamotosvendas@gmail.com</p>
                    <p>Telefone: (49) 99811-2215</p>
                    <p>Endereço: R. Bulcão Viana, 695 - Videira - SC</p>
                </div>
                <div class="footer-section">
                    <h3>Atendimento</h3>
                    <a href="https://wa.me/554998112215" class="btn btn-whatsapp" target="_blank">WhatsApp Direto</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2025 Rafa Motos - Desenvolvido por Nicolas Pereti</p>
            </div>
        </div>
    </footer>

    <script>
        let currentSlides = {};
        document.addEventListener('DOMContentLoaded', function() {
            const carouselContainers = document.querySelectorAll('.carousel-container');
            carouselContainers.forEach(container => {
                currentSlides[container.getAttribute('data-carousel')] = 0;
            });
        });

        function changeSlide(id, dir) {
            const container = document.querySelector(`[data-carousel="${id}"]`);
            const images = container.querySelectorAll('.carousel-image');
            images[currentSlides[id]].classList.remove('active');
            currentSlides[id] = (currentSlides[id] + dir + images.length) % images.length;
            images[currentSlides[id]].classList.add('active');
        }

        const menuToggle = document.getElementById('menu-toggle');
        const nav = document.querySelector('#nav ul');
        menuToggle.addEventListener('click', () => nav.classList.toggle('active'));
    </script>
</body>

</html>