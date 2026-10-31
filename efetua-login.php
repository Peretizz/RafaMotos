<?php
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/src/UsuarioDAO.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_valido()) {
    header('Location: login.php');
    exit;
}

// Freio simples contra tentativas em sequência
$_SESSION['tentativas'] = $_SESSION['tentativas'] ?? ['n' => 0, 't' => time()];
if (time() - $_SESSION['tentativas']['t'] > 300) $_SESSION['tentativas'] = ['n' => 0, 't' => time()];
if ($_SESSION['tentativas']['n'] >= 5) {
    flash('erro', 'Muitas tentativas. Aguarde alguns minutos e tente de novo.');
    header('Location: login.php');
    exit;
}

if (isset($_POST['idusuario'], $_POST['senha'])) {
    $usuario = UsuarioDAO::validarUsuario(['idusuario' => $_POST['idusuario'], 'senha' => $_POST['senha']]);
    if ($usuario) {
        session_regenerate_id(true);
        $_SESSION['usuario_id'] = $usuario['idusuario'];
        $_SESSION['logado'] = true;
        unset($_SESSION['tentativas']);
        header('Location: index.php');
        exit;
    }
}
$_SESSION['tentativas']['n']++;
flash('erro', 'Usuário ou senha incorretos.');
header('Location: login.php');
exit;
