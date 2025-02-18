<?php
namespace App\Models;

use Core\Model;
use PDO;

class Projeto extends Model
{
    protected $table = 'projetos'; // Nome da tabela no banco
    protected $primaryKey = 'id';
    public $timestamps = false;

    public static function listarTodos()
    {
        $db = self::getDB();
        $stmt = $db->query("SELECT * FROM projetos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function buscarPorId($id)
    {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM projetos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
     public static function buscarPorUsuario($usuario_id) {
        $db = Conexao::getConexao();
        $stmt = $db->prepare("SELECT * FROM projetos WHERE usuario_id = :usuario_id");
        $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();

    }

    public static function criarProjeto($nome, $descricao, $tecnologias, $objetivo, $usuario_id, $status, $bannerPath = null)
{
    $pdo = Conexao::getConexao();
    $sql = "INSERT INTO projetos (title, description, tecnologias, texto, usuario_id, status, banner) VALUES (:title, :description, :tecnologias, :texto, :usuario_id, :status, :banner)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':title', $nome);
    $stmt->bindParam(':description', $descricao);
    $stmt->bindParam(':tecnologias', $tecnologias);
    $stmt->bindParam(':texto', $objetivo);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':banner', $bannerPath); // Salva o caminho do banner

    $stmt->execute();
}

    
    public static function editarProjeto($id, $nome, $descricao, $tecnologias, $objetivo, $status, $bannerPath = null)
{
    $pdo = Conexao::getConexao();
    $sql = "UPDATE projetos SET title = :title, description = :description, tecnologias = :tecnologias, texto = :texto, status = :status, banner = :banner WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':title', $nome);
    $stmt->bindParam(':description', $descricao);
    $stmt->bindParam(':tecnologias', $tecnologias);
    $stmt->bindParam(':texto', $objetivo);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id', $id);
    
    // Atualiza o banner apenas se foi enviado um novo
    if ($bannerPath) {
        $stmt->bindParam(':banner', $bannerPath);
    } else {
        $stmt->bindParam(':banner', $projeto['banner']); // Mantém o banner atual caso não haja novo
    }

    $stmt->execute();
}
public static function deletarProjeto($id)
    {
        $db = Conexao::getConexao();
        $query = "DELETE FROM projetos WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
    public static function adicionarParticipante($projeto_id, $usuario_id)
{
    $pdo = Conexao::getConexao();

    // Verifica se o usuário já participa do projeto
    $sqlCheck = "SELECT COUNT(*) FROM projeto_participantes WHERE projeto_id = :projeto_id AND usuario_id = :usuario_id";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute([
        ':projeto_id' => $projeto_id,
        ':usuario_id' => $usuario_id
    ]);

    if ($stmtCheck->fetchColumn() > 0) {
        return false; // Usuário já está no projeto
    }

    // Insere o usuário como participante
    $sqlInsert = "INSERT INTO projeto_participantes (projeto_id, usuario_id) VALUES (:projeto_id, :usuario_id)";
    $stmtInsert = $pdo->prepare($sqlInsert);
    return $stmtInsert->execute([
        ':projeto_id' => $projeto_id,
        ':usuario_id' => $usuario_id
    ]);
}

public static function verificarParticipacao($projeto_id, $usuario_id)
{
    $pdo = Conexao::getConexao();
    $sql = "SELECT COUNT(*) FROM projeto_participantes WHERE projeto_id = :projeto_id AND usuario_id = :usuario_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':projeto_id' => $projeto_id,
        ':usuario_id' => $usuario_id
    ]);
    return $stmt->fetchColumn() > 0;
}
    public static function buscarProjetosPorParticipacao($usuario_id)
{
    $pdo = Conexao::getConexao();
    $sql = "SELECT p.*, pp.status 
            FROM projetos p
            INNER JOIN projeto_participantes pp ON p.id = pp.projeto_id
            WHERE pp.usuario_id = :usuario_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public static function buscarParticipantes($projeto_id)
{
    $pdo = Conexao::getConexao();
    $sql = "SELECT u.id, u.nome, u.email, pp.status
            FROM projeto_participantes pp
            INNER JOIN usuarios u ON pp.usuario_id = u.id
            WHERE pp.projeto_id = :projeto_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':projeto_id', $projeto_id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    public static function adicionarRelatorio($projeto_id, $usuario_id, $titulo, $conteudo, $tipo)
    {
        $pdo = Conexao::getConexao();

        $sql = "INSERT INTO relatorios (projeto_id, usuario_id, titulo, conteudo, tipo, data_criacao) 
                VALUES (:projeto_id, :usuario_id, :titulo, :conteudo, :tipo, NOW())";

        $stmt = $pdo->prepare($sql);

        // Executa a consulta
        $stmt->execute([
            ':projeto_id' => $projeto_id,
            ':usuario_id' => $usuario_id,
            ':titulo' => $titulo,
            ':conteudo' => $conteudo,
            ':tipo' => $tipo
        ]);
    }
    // No Model Projeto

public static function buscarRelatorios($projetoId)
{
    $pdo = Conexao::getConexao();
    $sql = "SELECT * FROM relatorios WHERE projeto_id = :projeto_id AND tipo = 'relatorio'";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':projeto_id', $projetoId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Retorna todos os relatórios
}

public static function buscarAtividades($projetoId)
{
    $pdo = Conexao::getConexao();
    $sql = "SELECT * FROM relatorios WHERE projeto_id = :projeto_id AND tipo = 'atividade'";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':projeto_id', $projetoId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Retorna todas as atividades
}
public static function alterarStatusParticipante($projetoId, $participanteId, $status)
{
    $pdo = Conexao::getConexao();
    $sql = "UPDATE projeto_participantes SET status = :status WHERE projeto_id = :projetoId AND usuario_id = :participanteId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':projetoId', $projetoId);
    $stmt->bindParam(':participanteId', $participanteId);
    $stmt->execute();
}




}