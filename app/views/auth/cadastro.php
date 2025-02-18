<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <link rel="stylesheet" href="<?= URL ?>/bootstrap/css/bootstrap.min.css">
    <link rel="icon" href="public/assets/img/projifMin.png">
    <style>
        .signup-container {
            min-height: 80vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .signup-card {
            max-width: 500px;
            width: 100%;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }
        .form-control {
            border-radius: 8px;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
        .footer-link {
            color: #007bff;
            text-decoration: none;
        }
        .footer-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <?php include './../app/views/partial/header.php'; ?>
    <?php include './../app/views/partial/vlibras.php'; ?>
    <?php if (!empty($erro)): ?>
        <div class="error-message"><?= $erro ?></div>
    <?php endif; ?>

    <div class="container signup-container">
        <div class="signup-card bg-light">
            <h2 class="text-center mb-4">Cadastro de Usuário</h2>
            <form action="/ProjetoExtensão/cadastro" method="POST">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" required placeholder="Digite seu nome">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required placeholder="Digite seu email">
                </div>

                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" required placeholder="Digite sua senha">
                </div>

                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirmar Senha</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="Confirme sua senha">
                </div>

                <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
            </form>
            <div class="text-center mt-3">
                <p>Já tem uma conta? <a href="<?= URL ?>login" class="footer-link">Login</a></p>
            </div>
        </div>
    </div>

</body>
</html>
