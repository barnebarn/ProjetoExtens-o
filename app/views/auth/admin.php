<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Usuários</title>
    <link rel="stylesheet" href="<?= URL ?>/bootstrap/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 1200px;
        }
        .search-bar {
            max-width: 500px;
            margin: 0 auto 20px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .cargo-select {
            max-width: 200px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .table-container {
            max-height: 500px;
            overflow-y: auto;
        }
        .search-input {
            width: 100%;
            border-radius: 8px;
        }
        .footer-link {
            text-decoration: none;
            color: #007bff;
        }
        .footer-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php include './../app/views/partial/header.php'; ?>

<div class="container mt-4">
    <h2 class="mb-4 text-center">Gerenciar Usuários</h2>

    <!-- Formulário de atualização de usuários -->
    <form action="<?= URL ?>admin/salvarAlteracoes" method="POST">
        <!-- Barra de pesquisa e botão Salvar -->
        <div class="d-flex justify-content-between mb-3">
            <input type="text" id="searchInput" class="form-control search-input" placeholder="Pesquisar usuário...">
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </div>

        <!-- Tabela de usuários -->
        <div class="table-container">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Cargo</th>
                    </tr>
                </thead>
                <tbody id="userTable">
                    <?php foreach ($usuariosA as $usuario): ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario['nome']) ?></td>
                            <td><?= htmlspecialchars($usuario['email']) ?></td>
                            <td>
                                <select class="form-select cargo-select" name="cargo[<?= $usuario['id'] ?>]">
                                    <option value="3" <?= $usuario['cargo_id'] == 3 ? 'selected' : '' ?>>Usuário</option>
                                    <option value="2" <?= $usuario['cargo_id'] == 2 ? 'selected' : '' ?>>Gerente</option>
                                    <option value="1" <?= $usuario['cargo_id'] == 1 ? 'selected' : '' ?>>Administrador</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </form>
</div>

<?php include './../app/views/partial/footer.php'; ?>

<script src="<?= URL ?>/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>
