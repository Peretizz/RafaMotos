<?php
require_once "ConexaoBD.php";

class MotoDAO
{

    public static function cadastrar($dados, $arquivos)
    {
        $conexao = ConexaoBD::conectar();

        try {
            $conexao->beginTransaction();

            $sql = "INSERT INTO motos (titulo, preco, km, cilindrada, potencia, transmissao) 
                    VALUES (:titulo, :preco, :km, :cilindrada, :potencia, :transmissao)";

            $stmt = $conexao->prepare($sql);
            $stmt->execute([
                ':titulo'      => $dados['titulo'],
                ':preco'       => $dados['preco'],
                ':km'          => $dados['km'],
                ':cilindrada'  => $dados['cilindrada'],
                ':potencia'    => $dados['potencia'],
                ':transmissao' => $dados['transmissao']
            ]);

            $idMotoGerado = $conexao->lastInsertId();

            if (isset($arquivos['fotos'])) {
                foreach ($arquivos['fotos']['tmp_name'] as $key => $tmpName) {
                    if ($arquivos['fotos']['error'][$key] === 0) {
                        $extensao = pathinfo($arquivos['fotos']['name'][$key], PATHINFO_EXTENSION);
                        $nomeArquivo = md5(uniqid()) . "." . $extensao;
                        $destino = "../img/" . $nomeArquivo;

                        if (move_uploaded_file($tmpName, $destino)) {
                            $sqlFoto = "INSERT INTO fotos_motos (idmoto, caminho_foto) VALUES (?, ?)";
                            $conexao->prepare($sqlFoto)->execute([$idMotoGerado, $nomeArquivo]);
                        }
                    }
                }
            }
            $conexao->commit();
            return true;
        } catch (Exception $e) {
            $conexao->rollBack();
            return false;
        }
    }


    public static function excluir($id)
    {
        $conexao = ConexaoBD::conectar();
        try {
            $stmt = $conexao->prepare("SELECT caminho_foto FROM fotos_motos WHERE idmoto = ?");
            $stmt->execute([$id]);
            $fotos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($fotos as $f) {
                $caminhoArquivo = "../img/" . $f['caminho_foto'];
                if (file_exists($caminhoArquivo)) {
                    unlink($caminhoArquivo);
                }
            }

            $sql = "DELETE FROM motos WHERE idmoto = :id";
            $stmt = $conexao->prepare($sql);
            $stmt->bindParam(':id', $id);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}


session_start();

if (isset($_GET['acao'])) {

    if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
        header("Location: ../login.php");
        exit();
    }

    if ($_GET['acao'] == 'cadastrar') {
        if (MotoDAO::cadastrar($_POST, $_FILES)) {
            header("Location: ../index.php?sucesso=cadastrado#motos");
        } else {
            echo "Erro ao cadastrar moto.";
        }
    }

    if ($_GET['acao'] == 'excluir' && isset($_GET['id'])) {
        if (MotoDAO::excluir($_GET['id'])) {
            header("Location: ../index.php?sucesso=excluido#motos");
        } else {
            echo "Erro ao excluir moto.";
        }
    }
}
