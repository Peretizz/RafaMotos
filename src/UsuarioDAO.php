<?php
require_once "ConexaoBD.php";

class UsuarioDAO
{
    public static function cadastrarUsuario($dados)
    {
        $conexao = ConexaoBD::conectar();
        
        $senhaCriptografada = password_hash($dados['senha'], PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO usuarios (senha) VALUES (:senha)";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':senha', $senhaCriptografada);
        
        return $stmt->execute();
    }

    public static function validarUsuario($dados)
    {
        $conexao = ConexaoBD::conectar();
        $id = $dados['idusuario'];
        $senha = $dados['senha'];

        $sql = "SELECT * FROM usuarios WHERE idusuario = :id";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && $senha == $usuario['senha']) {
            return $usuario;
        } else {
            return false;
        }
    }
}