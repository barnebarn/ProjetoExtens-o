<?php

namespace App\Models;

use PDO;
use PDOException;

class Conexao
{
    private static $pdo;

    public static function getConexao()
    {
        if (!self::$pdo) {
            $config = require __DIR__ . "/../../config/config.php";

            try {
                self::$pdo = new PDO(
                    "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8",
                    $config['db_user'],
                    $config['db_pass']
                );
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erro ao conectar ao banco de dados: " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
