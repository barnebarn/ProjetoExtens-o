<?php
 
namespace App\Controllers;

use Core\Controller;
use Core\View;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    public function __construct()
    {
        // Iniciar a sessão caso ainda não tenha sido iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    public function perfil()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario_id'])) {
            header("Location: /login");
            exit;
        }

        $usuario = Usuario::buscarPorId($_SESSION['usuario_id']);
        if (!$usuario) {
            die("Usuário não encontrado!");
        }

        View::render('auth/usuario', ['usuario_nome' => $usuario['nome']]);
    }
    public function salvarUsuarios()
    {

        $usuarios = Usuario::listarTodosUsuarios();

        View::render('auth/admin', ['usuariosA' => $usuarios]);
    }
    public function salvarAlteracoes()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Verifica se o campo 'cargo' está presente no POST
        if (isset($_POST['cargo'])) {
            // Itera sobre cada usuário e atualiza seu cargo
            foreach ($_POST['cargo'] as $usuario_id => $novo_cargo) {
                Usuario::atualizarCargo($usuario_id, $novo_cargo);
            }
            // Redireciona de volta para a página de gerenciamento de usuários com uma mensagem de sucesso
            $_SESSION['sucesso'] = "Cargos atualizados com sucesso!";
            header('Location: ' . URL . 'admin/usuarios');
            exit();
        } else {
            // Se não houver cargos no POST, exibe erro
            $_SESSION['erro'] = "Erro ao atualizar cargos.";
            header('Location: ' . URL . 'admin/usuarios');
            exit();
        }
    }
}

}



