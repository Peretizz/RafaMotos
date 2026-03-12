<?php
session_start();
require "src/UsuarioDAO.php";

if (isset($_POST['idusuario']) && isset($_POST['senha'])) {
    
    $dados['idusuario'] = $_POST['idusuario'];
    $dados['senha'] = $_POST['senha'];

    $usuario = UsuarioDAO::validarUsuario($dados);

    if ($usuario) {
        $_SESSION['usuario_id'] = $usuario['idusuario'];
        $_SESSION['logado'] = true;

        header("Location: painel.php");
        exit();
    } else {
        header("Location: login.php?erro=1");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}