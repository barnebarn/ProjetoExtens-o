<?php 

// Inicia a sessão de forma segura
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /login");
    exit;
}

// Carrega os dados do usuário
use App\Models\Usuario;
$usuario = Usuario::buscarPorId($_SESSION['usuario_id']);

if (!$usuario) {
    die("Erro: Usuário não encontrado.");
}

// Variáveis do usuário
$usuario_nome = $usuario['nome'] ?? 'Usuário';
$usuario_email = $usuario['email'] ?? 'E-mail não disponível';
$usuario_cargo = $usuario['cargo_id'] ?? 0;

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?= URL ?>/bootstrap/css/bootstrap.min.css">
    <title>Perfil do Usuário</title>
    <style>
        .profile-header {
            max-width: 800px;
            margin: 0 auto;
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            object-fit: cover;
        }
        .card-title {
            font-size: 1.2rem;
        }
        .hover-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
        }
        .badge-custom {
            font-size: 0.9rem;
        }
        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>

    <?php include './../app/views/partial/header.php'; ?>

    <div class="container-fluid py-5">
        <!-- Perfil do Usuário -->
        <div class="profile-header d-flex flex-column flex-md-row align-items-center gap-4 p-4 bg-light rounded shadow-sm">
            <img src="https://placehold.co/120" alt="Avatar do Usuário" class="profile-avatar rounded-circle border border-secondary mb-3 mb-md-0">
            <div>
                <h1 class="fw-bold text-dark mb-1"><?= htmlspecialchars($usuario_nome) ?></h1>
                <p class="text-muted mb-1"><?= htmlspecialchars($usuario_email) ?></p>
                
                <?php if ($usuario_cargo == 1): ?>
                    <p class="badge bg-success text-white badge-custom mt-2">Administrador</p>
                <?php elseif ($usuario_cargo == 2): ?>
                    <p class="badge bg-primary text-white badge-custom mt-2">Gerente</p>
                <?php else: ?>
                    <p class="badge bg-secondary text-white badge-custom mt-2">Usuário</p>
                <?php endif; ?>
                
                <?php if ($usuario_cargo == 1): ?>
                    <div class="mt-3">
                        <a href="<?= URL ?>admin/usuarios" class="btn btn-danger btn-sm">Gerenciar Usuários</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Projetos do Usuário -->
        <h2 class="mt-5 mb-4 text-center">Meus Projetos</h2>

        <div class="row gx-4">
            <?php 
            use App\Models\Projeto;

            $projetosCriados = Projeto::buscarPorUsuario($_SESSION['usuario_id']);
            $projetosParticipando = Projeto::buscarProjetosPorParticipacao($_SESSION['usuario_id']);
            $projetos = array_merge($projetosCriados, $projetosParticipando);
            $projetos = array_map("unserialize", array_unique(array_map("serialize", $projetos)));

            if (!empty($projetos)): 
                foreach ($projetos as $projeto):
                    $status = "";
                    if ($projeto['usuario_id'] == $_SESSION['usuario_id']) {
                        $status = "<span class='badge bg-success'>Seu Projeto</span>";
                    } else {
                        if ($projeto['status'] == 'pendente') {
                            $status = "<span class='badge bg-warning text-dark'>Pendente</span>";
                        } elseif ($projeto['status'] == 'aprovado') {
                            $status = "<span class='badge bg-primary'>Participando</span>";
                        } elseif ($projeto['status'] == 'removido') {
                            $status = "<span class='badge bg-danger'>Removido</span>";
                        }
                    }
            ?>

            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <a href="<?= URL ?>projeto/<?= $projeto['id'] ?>" class="text-decoration-none">
                    <div class="card shadow-sm border-0 h-100 hover-card">
                        <div class="card-body">
                            <h6 class="card-title text-primary fw-bold">
                                <?= htmlspecialchars($projeto['title']) ?> <?= $status ?>
                            </h6>
                            <p class="card-text text-muted small">
                                <?= nl2br(htmlspecialchars($projeto['description'])) ?>
                            </p>
                        </div>
                    </div>
                </a>
            </div>

            <?php endforeach; 
            else: ?>
                <p class='text-muted text-center'>Você ainda não participa de nenhum projeto.</p>
                <div class='text-center mt-4'>
                    <a href='projetos' class='btn btn-primary btn-lg'>Explorar Projetos</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include './../app/views/partial/footer.php'; ?>

    <script src="<?= URL ?>/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
