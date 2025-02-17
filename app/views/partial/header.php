<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
use App\Models\Usuario;

include('./../routes/web.php');

$usuarioLogado = isset($_SESSION['usuario_id']);
if($usuarioLogado){
    $usuario = Usuario::buscarPorId($_SESSION['usuario_id']);
}
$usuario_cargo = $usuario['cargo_id'] ?? 0;
?>

<header class="bg-dark text-white shadow-sm">
    <div class="container d-flex justify-content-between align-items-center py-3">
        <h1 class="mb-0">
            <img src="<?= URL ?>public/assets/img/logoprojif.png" whidt="20px" height="60px">
            <?php if ($usuario_cargo == 1): ?>
                <span class="text-warning">★★★</span>
            <?php elseif ($usuario_cargo == 2): ?>
                <span class="text-info">★★</span>
            <?php elseif ($usuario_cargo == 3): ?>
                <span class="text-secondary">★</span>
            <?php endif; ?>
        </h1>
        <nav>
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?= URL ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?= URL ?>projetos">Projetos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?= URL ?>sobre">Sobre Nós</a>
                </li>

                <?php if ($usuarioLogado): ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= URL ?>usuario">Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= URL ?>logout">Sair</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-light btn-sm px-3" href="<?= URL ?>login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-light btn-sm px-3" href="<?= URL ?>cadastro">Cadastro</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
