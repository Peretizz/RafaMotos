<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Minha Página</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <?php session_start(); ?>

    <main class="container d-flex justify-content-center align-items-center my-5 flex-grow-1">
        <div class="row w-100 align-items-center justify-content-center g-3">
            <form action="efetua-login.php" method="post" class="login-container rounded-4 p-4 py-5 col-12 col-md-5">
             <?php   if (isset($_SESSION['msg'])) {
            echo '<div class="alert" style="color:#FFFFFF">' . $_SESSION['msg'] . '</div>';
            unset($_SESSION['msg']);
        } else {
            echo '<div class="alert" style="color:#FFFFFF">Informe seu email e senha para entrar.</div>';
        }
      ?>
                <div class="mb-4">
                    <label for="idusuario" class="form-label">idusuario</label>
                    <input type="number" id="idusuario" name="idusuario" placeholder="Digite seu idusuario"
                        class="form-control" required>
                </div>

                <div class="mb-4">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="Digite sua Senha" class="form-control"
                        required>
                </div>

                <div class="d-grid gap-2 mt-5">
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </div>

            </form>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

</body>
</html>