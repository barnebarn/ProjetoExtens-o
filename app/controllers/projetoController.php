<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;
use App\Models\Projeto;
use App\Models\Usuario;

class ProjetoController extends Controller
{
    public function __construct()
    {
        // Iniciar a sessão caso ainda não tenha sido iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function getUsuarioCargo()
    {
        // Verifica se o usuário está logado
        if (isset($_SESSION['usuario_id'])) {
            $usuario = Usuario::buscarPorId($_SESSION['usuario_id']);
            return $usuario['cargo_id'] ?? 0; // Retorna o cargo ou 0 se não existir
        }
        return 0;
    }

    public function index()
    {
        $projetos = Projeto::listarTodos();
        View::render('projeto/index', ['projetos' => $projetos]);
    }

    public function show($id)
    {
        // Busca o projeto pelo ID
        $projeto = Projeto::buscarPorId($id);

        if (!$projeto) {
            echo "Projeto não encontrado!";
            return;
        }
        $usuarioParticipa = false;
    
    if (isset($_SESSION['usuario_id'])) {
        $usuarioParticipa = Projeto::verificarParticipacao($id, $_SESSION['usuario_id']);
    }
        // Busca o usuário que é o criador do projeto, usando o usuario_id do projeto
        $usuarioP = Usuario::buscarPorId($projeto['usuario_id']);
        $relatorios = Projeto::buscarRelatorios($id);

    // Recupera as atividades (caso haja)
    $atividades = Projeto::buscarAtividades($id);

    // Passa os dados para a view


        // Passa os dados do projeto e do usuário para a view
        View::render('projeto/detalhes', [
            'projeto' => $projeto,
            'usuarioP' => $usuarioP,
            'usuarioParticipa' => $usuarioParticipa,
            'relatorios' => $relatorios,
            'atividades' => $atividades
        ]);
    }

    public function criar()
    {
        // Verifica se o usuário tem permissão para criar projetos (cargo 1 = Admin, cargo 2 = Usuário Especial)
        if ($this->getUsuarioCargo() == 1 or $this->getUsuarioCargo() == 2) {
            View::render('projeto/criar');
        } else {
            $_SESSION['erro'] = "Você não tem permissão para criar um projeto.";
            header('Location: ' . URL . 'erro');
            exit();
        }
    }

    public function salvar()
{
    // Verifica se o usuário tem permissão para criar ou editar projetos
    if ($this->getUsuarioCargo() == 1 || $this->getUsuarioCargo() == 2) {
        $bannerPath = null; // Inicializa o caminho do banner como nulo

        // Obtém o ID do projeto (se existir) e o caminho do banner existente
        $id = $_POST['id'] ?? null;
        $bannerExistente = $_POST['banner_existente'] ?? null;

        // Diretório onde as imagens serão armazenadas
        $targetDir = __DIR__ . "/../../public/uploads/banners/";

        // Se for edição e existir um banner antigo
        if (!empty($id) && !empty($bannerExistente)) {
            $bannerPath = $bannerExistente;
        }

        // Verifica se um novo arquivo foi enviado
        if (isset($_FILES['banner']) && $_FILES['banner']['error'] == 0) {
            $imageFileType = strtolower(pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION));
            $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            // Valida o tipo de arquivo
            if (!in_array($imageFileType, $validExtensions)) {
                $_SESSION['erro'] = "Formato de arquivo inválido. Use JPG, JPEG, PNG ou GIF.";
                header('Location: ' . URL . 'projeto/editar/' . $id);
                exit();
            }

            // Gera um nome único para evitar conflitos
            $novoNome = uniqid() . "_" . basename($_FILES['banner']['name']);
            $targetFile = $targetDir . $novoNome;

            // Se houver um banner antigo, exclui antes de salvar o novo
            if (!empty($bannerExistente) && file_exists($targetDir . $bannerExistente)) {
                unlink($targetDir . $bannerExistente);
            }

            // Faz o upload do novo arquivo
            if (move_uploaded_file($_FILES['banner']['tmp_name'], $targetFile)) {
                $bannerPath = "uploads/banners/" . $novoNome;
            } else {
                $_SESSION['erro'] = "Erro no upload da imagem de banner.";
                header('Location: ' . URL . 'projeto/editar/' . $id);
                exit();
            }
        }

        // Verifica se os outros campos foram preenchidos
        $nome = $_POST['title'] ?? null;
        $descricao = $_POST['description'] ?? null;
        $tecnologias = $_POST['technologies'] ?? null;
        $objetivo = $_POST['objective'] ?? null;
        $status = $_POST['status'] ?? null; 
        $usuario_id = $_SESSION['usuario_id'] ?? null;

        // Verifica se todos os campos obrigatórios foram preenchidos
        if ($nome && $descricao && $tecnologias && $objetivo && $status && $usuario_id) {
            if (!empty($id)) { // Edição do projeto
                Projeto::editarProjeto($id, $nome, $descricao, $tecnologias, $objetivo, $status, $bannerPath);
            } else { // Criação do projeto
                Projeto::criarProjeto($nome, $descricao, $tecnologias, $objetivo, $usuario_id, $status, $bannerPath);
            }

            // Redireciona para a página de projetos
            header('Location: ' . URL . 'projetos');
            exit();
        } else {
            $_SESSION['erro'] = "Preencha todos os campos obrigatórios.";
            header('Location: ' . URL . 'projeto/criar');
            exit();
        }
    } else {
        $_SESSION['erro'] = "Você não tem permissão para criar ou editar um projeto.";
        header('Location: ' . URL . 'erro');
        exit();
    }
}




    public function editar($id)
    { 
        // Verifica se o usuário está logado
        if (!isset($_SESSION['usuario_id'])) {
            $_SESSION['erro'] = "Você precisa estar logado para editar o projeto.";
            header('Location: ' . URL . 'login');
            exit();
        }

        // Recupera o projeto com base no ID
        $projeto = Projeto::buscarPorId($id);

        if (!$projeto) {
            $_SESSION['erro'] = "Projeto não encontrado.";
            header('Location: ' . URL . 'projetos');
            exit();
        }

        // Recupera o cargo do usuário logado
        $usuarioCargo = $this->getUsuarioCargo();

        // Verifica se o usuário é administrador ou o criador do projeto
        if ($usuarioCargo != 1 && $_SESSION['usuario_id'] != $projeto['usuario_id']) {
            $_SESSION['erro'] = "Você não tem permissão para editar este projeto.";
            header('Location: ' . URL . 'erro');
            exit();
        }

        // Renderiza a página de edição do projeto
        View::render('projeto/editar', ['projeto' => $projeto]);
    }
    public function apagar($id)
{
    // Verifica se o usuário está logado
    if (!isset($_SESSION['usuario_id'])) {
        $_SESSION['erro'] = "Você precisa estar logado para excluir o projeto.";
        header('Location: ' . URL . 'login');
        exit();
    }

    // Verifica se o projeto existe
    $projeto = Projeto::buscarPorId($id);

    if (!$projeto) {
        $_SESSION['erro'] = "Projeto não encontrado.";
        header('Location: ' . URL . 'projetos');
        exit();
    }

    // Verifica se o usuário é o criador do projeto ou administrador
    if ($_SESSION['usuario_id'] != $projeto['usuario_id'] && $this->getUsuarioCargo() != 1) {
        $_SESSION['erro'] = "Você não tem permissão para excluir este projeto.";
        header('Location: ' . URL . 'erro');
        exit();
    }

    // Deleta o projeto
    if (Projeto::deletarProjeto($id)) {
        $_SESSION['sucesso'] = "Projeto excluído com sucesso.";
    } else {
        $_SESSION['erro'] = "Erro ao excluir o projeto.";
    }

    // Redireciona para a página de projetos
    header('Location: ' . URL . 'projetos');
    exit();
}
    public function participar($id)
{
    if (!isset($_SESSION['usuario_id'])) {
        $_SESSION['erro'] = "Você precisa estar logado para participar de um projeto.";
        header("Location: " . URL . "login");
        exit();
    }

    $usuario_id = $_SESSION['usuario_id'];

    // Adicionar usuário ao projeto
    if (Projeto::adicionarParticipante($id, $usuario_id)) {
        $_SESSION['sucesso'] = "Você agora participa deste projeto!";
    } else {
        $_SESSION['erro'] = "Erro ao tentar participar do projeto.";
    }

    header("Location: " . URL . "projeto/" . $id);
    exit();
}
public function aprovarParticipante($projeto_id, $usuario_id)
{
    $pdo = Conexao::getConexao();
    $sql = "UPDATE projeto_participantes SET status = 'aprovado' WHERE projeto_id = :projeto_id AND usuario_id = :usuario_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':projeto_id', $projeto_id, PDO::PARAM_INT);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: " . URL . "projeto/editar/" . $projeto_id);
    exit;
}

public function removerParticipante($projeto_id, $usuario_id)
{
    $pdo = Conexao::getConexao();
    $sql = "DELETE FROM projeto_participantes WHERE projeto_id = :projeto_id AND usuario_id = :usuario_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':projeto_id', $projeto_id, PDO::PARAM_INT);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: " . URL . "projeto/editar/" . $projeto_id);
    exit;
}
    // Método para alterar o status do participante
public function alterar_status()
{
    if (isset($_POST['status']) && isset($_POST['id'])) {
        $statusData = $_POST['status'];
        $projetoId = $_POST['id']; // Obtém o ID do projeto

        // Itera sobre os participantes e atualiza o status de cada um
        foreach ($statusData as $participanteId => $status) {
            // Passa o projetoId e o participanteId para a função de alteração
            Projeto::alterarStatusParticipante($projetoId, $participanteId, $status);
        }

        // Redireciona de volta para a página de edição do projeto
        header('Location: ' . URL . 'projeto/editar/' . $projetoId); // A URL correta inclui o ID do projeto
        exit;
    } else {
        // Se não encontrar o parâmetro 'status' ou 'id', retorna erro
        echo "Nenhuma alteração de status ou projeto não encontrado!";
    }
}




    public function adicionarRelatorio($id)
    {
        // Verifica se o usuário está logado
        if (!isset($_SESSION['usuario_id'])) {
            $_SESSION['erro'] = "Você precisa estar logado para adicionar um relatório.";
            header('Location: ' . URL . 'login');
            exit();
        }

        // Recupera o projeto com base no ID
        $projeto = Projeto::buscarPorId($id);

        if (!$projeto) {
            $_SESSION['erro'] = "Projeto não encontrado.";
            header('Location: ' . URL . 'projetos');
            exit();
        }

        // Recupera o cargo do usuário logado
        $usuarioCargo = $this->getUsuarioCargo();

        // Verifica se o usuário está participando do projeto
        $usuarioParticipa = Projeto::verificarParticipacao($id, $_SESSION['usuario_id']); // Chama a função no Model

        // O usuário só pode adicionar relatórios se ele estiver participando do projeto
        if (!$usuarioParticipa) {
            $_SESSION['erro'] = "Você não pode adicionar um relatório ou atividade porque não participa deste projeto.";
            header('Location: ' . URL . 'projeto/' . $id);
            exit();
        }

        // Verifica se o formulário foi enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recebe os dados do formulário
            $titulo = $_POST['titulo'];
            $conteudo = $_POST['conteudo'];
            $tipo = $_POST['tipo']; // Pode ser 'relatorio' ou 'atividade'

            // Chama o modelo para adicionar o relatório ao banco de dados
            Projeto::adicionarRelatorio($id, $_SESSION['usuario_id'], $titulo, $conteudo, $tipo);

            // Redireciona para a página de detalhes do projeto após a adição
            header('Location: ' . URL . 'projeto/' . $id);
            exit();
        }

        // Caso o formulário não tenha sido enviado, carrega a página de detalhes do projeto
        View::render('projeto/', ['projeto' => $projeto]);
    }



}
