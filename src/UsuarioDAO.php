<?php
require_once __DIR__ . '/ConexaoBD.php';

class UsuarioDAO
{
    public static function cadastrarUsuario($dados)
    {
        $conexao = ConexaoBD::conectar();
        $hash = password_hash($dados['senha'], PASSWORD_DEFAULT);
        $stmt = $conexao->prepare("INSERT INTO usuarios (senha) VALUES (:senha)");
        $stmt->bindParam(':senha', $hash);
        return $stmt->execute();
    }

    /**
     * Aceita senha criptografada (recomendado) e também a senha antiga em texto puro.
     * Quando entra com a senha antiga e a coluna já comporta o hash (veja sql/seguranca-senha.sql),
     * a senha é convertida automaticamente para hash no primeiro login.
     */
    public static function validarUsuario($dados)
    {
        $conexao = ConexaoBD::conectar();
        $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE idusuario = :id");
        $stmt->bindValue(':id', (int)$dados['idusuario'], PDO::PARAM_INT);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$usuario) return false;

        $guardada = (string)$usuario['senha'];
        $informada = (string)$dados['senha'];

        if (preg_match('/^\$2[ayb]\$|^\$argon2/', $guardada)) {
            return password_verify($informada, $guardada) ? $usuario : false;
        }
        if (hash_equals($guardada, $informada)) {
            try {
                $novo = password_hash($informada, PASSWORD_DEFAULT);
                $up = $conexao->prepare("UPDATE usuarios SET senha = :s WHERE idusuario = :id");
                $up->execute([':s' => $novo, ':id' => $usuario['idusuario']]);
            } catch (Throwable $e) { /* coluna ainda curta: segue com a senha antiga */ }
            return $usuario;
        }
        return false;
    }
}
