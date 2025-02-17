<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Criar Projeto</title>
    <link rel="stylesheet" href="<?= URL ?>/bootstrap/css/bootstrap.min.css">
    <link rel="icon" href="public/assets/img/projifMin.png">
</head>
<body>
    <?php include './../app/views/partial/header.php'; ?>

    <div class="container my-5 d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-8 col-lg-6">
            <h2 class="text-center mb-4">Criar Projeto</h2>
            <form action="<?= URL ?>projeto/salvar" method="POST">

                <div class="mb-3">
                    <label for="title" class="form-label">Nome do Projeto</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descrição do Projeto</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="technologies" class="form-label">Tecnologias Utilizadas</label>
                    <textarea class="form-control" id="technologies" name="technologies" placeholder="Ex: PHP, JavaScript, MySQL..." rows="4" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="objective" class="form-label">Objetivo do Projeto</label>
                    <textarea class="form-control" id="objective" name="objective" placeholder="Descreva o objetivo do projeto..." rows="4" required></textarea>
                </div>

                <input type="hidden" name="status" value="ativo">

                <button type="submit" class="btn btn-success w-100">Criar Projeto</button>
            </form>
        </div>
    </div>

    <?php include './../app/views/partial/footer.php'; ?>

    <script src="<?= URL ?>/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
