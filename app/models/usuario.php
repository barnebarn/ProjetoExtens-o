<?php

namespace App\Models;

use Core\Database;
use PDO;

class Usuario
{
    public static function buscarPorEmail($email)
    {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function cadastrar($dados)
{
    $db = Conexao::getConexao();

    // Defina um cargo_id padrão se não for enviado
    $cargo_id = isset($dados['cargo_id']) ? $dados['cargo_id'] : 1;

    $senha_hash = password_hash($dados['senha'], PASSWORD_BCRYPT);

    $stmt = $db->prepare("INSERT INTO usuarios (nome, email, senha, cargo_id) VALUES (?, ?, ?, ?)");
    return $stmt->execute([
        $dados['nome'],
        $dados['email'],
        $senha_hash,
        $cargo_id
    ]);
}

public static function buscarPorId($id)
{
    $pdo = Conexao::getConexao();
    $sql = "SELECT * FROM usuarios WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC); // Retorna o usuário com o id correspondente
}
public static function atualizarCargo($usuario_id, $novo_cargo)
    {
        $pdo = Conexao::getConexao();
        $sql = "UPDATE usuarios SET cargo_id = :cargo WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':cargo' => $novo_cargo,
            ':id' => $usuario_id
        ]);
    }
    public static function listarTodosUsuarios()
    {
        $pdo = Conexao::getConexao();
        $stmt = $pdo->query("SELECT * FROM usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



}
