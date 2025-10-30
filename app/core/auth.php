<?php
namespace Core;

class Auth {
    public static function login($usuario) {
        session_start();
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['cargo_id'] = $usuario['cargo_id'];
    }

    public static function logout() {
        session_start();
        session_destroy();
        header("Location: /ProjetoExtens-o/login");
        exit;
    }

    public static function usuarioLogado() {
        return isset($_SESSION['usuario_id']);
    }
}

// Arquivo: app/models/Usuario.