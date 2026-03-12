<?php
// 1. SEMPRE inicie a sessão na primeira linha para o login funcionar
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
                <div class="menu-toggle" id="menu-toggle">
                    ☰
                </div>
                <nav id="nav">
                    <ul>
                        <li><a href="#inicio">Início</a></li>
                        <li><a href="#sobre">Sobre</a></li>
                        <li><a href="#motos">Motos</a></li>
                        <li><a href="#contato">Contato</a></li>
                        <?php if (isset($_SESSION['logado'])): ?>
                            <li><a href="logout.php" style="color: #ff4d4d;">Sair</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

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
                                    <div class="carousel-indicators">
                                        <?php foreach ($fotos as $index => $foto): ?>
                                            <span class="indicator <?php echo $index === 0 ? 'active' : ''; ?>"
                                                onclick="goToSlide(<?php echo $idM; ?>, <?php echo $index; ?>)"></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="produto-info">
                            <h3 class="produto-titulo"><?php echo $moto['titulo']; ?></h3><br>
                            <p class="produto-preco">R$ <?php echo number_format($moto['preco'], 2, ',', '.'); ?></p>
                            <div class="produto-especificacoes">
                                <div class="especificacao"><span>Kilometragem:</span><span><?php echo $moto['km']; ?></span></div>
                                <div class="especificacao"><span>Cilindrada:</span><span><?php echo $moto['cilindrada']; ?></span></div>
                                <div class="especificacao"><span>Potência:</span><span><?php echo $moto['potencia']; ?></span></div>
                                <div class="especificacao"><span>Transmissão:</span><span><?php echo $moto['transmissao']; ?></span></div>
                            </div>

                            <a href="https://wa.me/554998112215?text=Olá,%20tenho%20interesse%20na%20<?php echo urlencode($moto['titulo']); ?>"
                                class="btn btn-whatsapp" target="_blank">Consultar no WhatsApp</a>

                            <?php if (isset($_SESSION['logado']) && $_SESSION['logado'] === true): ?>
                                <div style="margin-top: 15px; border-top: 1px dashed #ccc; padding-top: 10px;">
                                    <a href="src/MotoDAO.php?acao=excluir&id=<?php echo $idM; ?>"
                                        class="btn"
                                        style="background: #000; color: #fff; width: 100%; display: block; text-align: center; text-decoration: none;"
                                        onclick="return confirm('Tem certeza que deseja excluir esta moto?')">
                                        🗑️ Excluir Anúncio
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </section>
</body>