<?php
session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Cadastro de Moto - Rafa Motos</title>
    <style>
        .cadastro-section {
            padding: 60px 0;
            background: #f4f4f4;
        }

        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn-submit {
            width: 100%;
            background: #d32f2f;
            color: white;
            border: none;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }

        .btn-submit:hover {
            background: #b71c1c;
        }
    </style>
</head>

<body>
    <header>
    <div class="container">
        <div class="header-content">
            <div class="logo"><img src="img/logo.png" alt="Rafa Motos"></div>
            
            <div class="menu-toggle" id="mobile-menu">☰</div>

            <nav id="nav">
                <ul id="nav-list">
                    <li><a href="index.php">Início</a></li>
                </ul>
            </nav>
        </div>
    </div>
</header>

    <section class="cadastro-section">
        <div class="container">
            <h2 class="section-title" style="text-align: center; margin-bottom: 30px;">Cadastrar Nova Moto</h2>

            <div class="form-container">
                <form action="src/MotoDAO.php?acao=cadastrar" method="POST" enctype="multipart/form-data">

                    <div class="form-group">
                        <label for="titulo">Título do Anúncio (Ex: HONDA BROS 160 2022)</label>
                        <input type="text" id="titulo" name="titulo" placeholder="Marca, Modelo e Ano" required>
                    </div>

                    <div class="form-group">
                        <label for="preco">Preço (R$)</label>
                        <input type="text" id="preco" name="preco" placeholder="Ex: 19900.00" required>
                    </div>

                    <div class="form-group">
                        <label for="km">Kilometragem (km)</label>
                        <input type="text" id="km" name="km" placeholder="Ex: 30.000 km" required>
                    </div>

                    <div class="form-group" style="display: flex; gap: 10px;">
                        <div style="flex: 1;">
                            <label for="cilindrada">Cilindrada</label>
                            <input type="text" id="cilindrada" name="cilindrada" placeholder="Ex: 160 cc">
                        </div>
                        <div style="flex: 1;">
                            <label for="potencia">Potência</label>
                            <input type="text" id="potencia" name="potencia" placeholder="Ex: 14,3 cv">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="transmissao">Transmissão</label>
                        <input type="text" id="transmissao" name="transmissao" placeholder="Ex: 5 marchas">
                    </div>

                    <div class="form-group">
                        <label for="fotos">Fotos da Moto (Selecione várias de uma vez)</label>
                        <input type="file" id="fotos" name="fotos[]" accept="image/*" multiple required>
                        <small>A primeira imagem será a principal do card.</small>
                    </div>

                    <button type="submit" class="btn-submit">Publicar Anúncio</button>

                </form>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-bottom">
                <p>© 2025 Rafa Motos - Todos os direitos reservados | Desenvolvido por Nicolas Pereti</p>
            </div>
        </div>
    </footer>
</body>

</html>