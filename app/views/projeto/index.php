<?php 
include('./../app/core/config.php');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?= URL ?>/bootstrap/css/bootstrap.css">
    <style>
        /* Estilo geral */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }

        .project-card {
            width: 100%;
            max-width: 270px;
            height: 350px;
            margin-bottom: 20px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            border-radius: 10px;
        }

        .project-card img {
            object-fit: cover;
            height: 200px;
            border-radius: 10px;
        }

        .project-title,
        .project-description {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .project-description {
            max-width: 100%;
        }

        .list-container {
            width: 100%;
            max-width: 320px;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 15px;
            margin-top: 20px;
            background-color: #fff;
        }

        .list-header {
            cursor: pointer;
            background-color: #343a40; /* Fundo escuro */
            color: white;
            padding: 12px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 10px;
            font-size: 18px;
        }

        .list-content {
            display: none;
            padding: 10px;
            border-top: 1px solid #ccc;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .buttonDetalhes:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Botões */
        .btn-primary {
            background-color: #6c757d; /* Cinza escuro */
            border-color: #6c757d;
        }

        .btn-primary:hover {
            background-color: #5a6268; /* Tom mais escuro no hover */
            border-color: #545b62;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .project-card {
                max-width: 100%;
            }

            .list-container {
                margin: 10px;
                padding: 10px;
            }

            .list-header {
                font-size: 16px;
            }
        }
    </style>
    <title>Projetos - <?= APP_NOME ?></title>
</head>

<body class="d-flex flex-column min-vh-100">

    <?php include './../app/views/partial/header.php'; ?>

    <div class="container py-5 flex-grow-1">
        <h1 class="mb-4 text-center text-dark">Projetos</h1>

        <div class="text-center mb-4">
            <p class="text-muted">Projetos em Destaque</p>
            <div class="row justify-content-center gap-4">
                <?php
                $max = count($projetos);
                $min = 1;

                // Gerar IDs aleatórios para destacar projetos
                $destaque_ids = [
                    random_int($min, $max),
                    random_int($min, $max),
                    random_int($min, $max),
                    random_int($min, $max),
                    random_int($min, $max)
                ];

                // Armazenar projetos destacados
                $destaque_projects = [];
                foreach ($destaque_ids as $id) {
                    if (isset($projetos[$id - 1])) {
                        $destaque_projects[] = $projetos[$id - 1];
                    }
                }
                ?>

                <?php foreach ($destaque_projects as $projeto): ?>
                    <div class="col-12 col-md-4 col-lg-2">
                        <div class="card border-dark project-card shadow-sm">
                            <a href="<?= URL ?>projeto/<?= $projeto['id'] ?>" class="text-decoration-none">
                                <img src="<?= !empty($projeto['banner']) ? URL . 'public/' . $projeto['banner'] : 'https://placehold.co/100x100' ?>" class="card-img-top" alt="Imagem do Projeto">
                                <div class="card-body p-3">
                                    <h5 class="card-title text-truncate"><?= htmlspecialchars($projeto['title']) ?></h5>
                                    <p class="card-text text-truncate" title="<?= htmlspecialchars($projeto['description']) ?>"><?= htmlspecialchars($projeto['description']) ?></p>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <h2 class="cursor-pointer list-header" data-bs-toggle="collapse" data-bs-target="#allProjectsList" aria-expanded="false" aria-controls="allProjectsList">
            Todos os Projetos
        </h2>

        <div class="collapse" id="allProjectsList">
            <ul class="list-group">
                <?php foreach ($projetos as $projeto): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= htmlspecialchars($projeto['title']) ?> - <?= htmlspecialchars($projeto['description']) ?>
                        <a href="<?= URL ?>projeto/<?= $projeto['id'] ?>" class="buttonDetalhes btn btn-outline-secondary">
                            Ver Detalhes
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <?php if ($usuario_cargo == 1 || $usuario_cargo == 2): ?>
        <div class="container text-center my-5">
            <a href="<?= URL ?>projeto/criar" class="btn btn-primary btn-lg">Criar novo projeto</a>
        </div>
    <?php endif; ?>

    <?php include './../app/views/partial/footer.php'; ?>

    <script src="<?= URL ?>/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
