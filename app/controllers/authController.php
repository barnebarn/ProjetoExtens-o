<?php
namespace App\Controllers;

use Core\Controller;
use Core\View;
use App\Models\Usuario;
use Core\Auth;

class AuthController extends Controller {
    public function showLoginForm() {
        View::render('auth/login');
    }

    public function login()
{
    session_start();

    $email = $_POST['email'] ?? '';
    $senhaP = $_POST['senha'] ?? '';

    $usuario = Usuario::buscarPorEmail($email);

    if (!$usuario) {
        View::render('auth/login', ['erro' => 'Usuário não encontrado']);
        return;
    }

    echo "<script>alert('Email ou Senha esta incorreta!);</script>";

    if (password_verify($senhaP, $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['cargo_id'] = $usuario['cargo_id'];

        header("Location: usuario");
        exit;
    } else {
        View::render('auth/login', ['erro' => 'Senha incorreta']);
    }
}



    public function showRegisterForm() {
        View::render('auth/cadastro');
    }

    public function register() {
        $dados = [
            'nome' => $_POST['nome'] ?? '',
            'email' => $_POST['email'] ?? '',
            'senha' => $_POST['senha'] ?? ''
        ];

        if (Usuario::cadastrar($dados)) {
            header("Location: /ProjetoExtensão/login");
            exit;
        } else {
            View::render('auth/cadastro', ['erro' => 'Erro ao cadastrar']);
        }
    }

    public function logout() {
        Auth::logout();
    }
}