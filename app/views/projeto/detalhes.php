<?php 
include('./../app/core/config.php');

$statusMap = [
    'A' => 'Aprovado',
    'C' => 'Cancelado',
    'F' => 'Finalizado'
];

// Pegando o status do projeto e verificando se existe no array
$statusExibido = $statusMap[$projeto['status']] ?? 'Desconhecido';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Projeto - <?= htmlspecialchars($projeto['title']) ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="public/assets/img/projifMin.png">
    <style>
        body {
            background-color: #f4f7fc;
        }

        .project-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .project-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
        }

        .project-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
            margin-top: 20px;
        }

        .project-description {
            font-size: 1.2rem;
            margin-top: 10px;
            font-style: italic;
            color: #666;
        }
        

        .section-title {
            margin-top: 30px;
            margin-bottom: 15px;
            font-size: 1.8rem;
            font-weight: bold;
            color: #007bff;
        }

        .project-details p {
            font-size: 1.1rem;
            margin-bottom: 10px;
            color: #555;
        }

        .btn-project {
            background: linear-gradient(to right, #28a745, #218838);
            color: #fff;
            padding: 12px 20px;
            font-size: 1.2rem;
            border-radius: 30px;
            transition: 0.3s;
        }

        .btn-project:hover {
            background: linear-gradient(to right, #218838, #28a745);
            transform: scale(1.05);
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .report, .activity {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .report h5, .activity h5 {
            font-weight: bold;
            font-size: 1.2rem;
            color: #007bff;
        }

        .form-control, .btn {
            border-radius: 30px;
        }

        .text-center {
            font-size: 1.2rem;
        }

        .form-group label {
            font-weight: bold;
            color: #333;
        }

        .info-box {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .info-box p {
            font-size: 1.1rem;
            margin-bottom: 10px;
            color: #555;
        }
    </style>
</head>

<body>

    <?php include './../app/views/partial/header.php'; ?>

    <div class="container py-5">

        <div class="project-header">
            <img src="<?= !empty($projeto['banner']) ? URL . 'public/' . $projeto['banner'] : 'https://placehold.co/800x400' ?>" alt="Banner do Projeto" class="project-image">

            <h1 class="project-title"><?= htmlspecialchars($projeto['title']) ?></h1>
            <p class="project-description"><?= htmlspecialchars($projeto['description']) ?></p>
        </div>

        <div class="info-box">
            <h3 class="section-title">Informações do Projeto</h3>
            <p><strong>Data de Início:</strong> <?= htmlspecialchars($projeto['data_inicio']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($statusExibido) ?></p>
            <p><strong>Criador:</strong> <?= htmlspecialchars($usuarioP['nome']) ?></p>
            <p><strong>Objetivo:</strong> <?= htmlspecialchars($projeto['texto']) ?></p>
            <p><strong>Tecnologias:</strong> <?= htmlspecialchars($projeto['Tecnologias']) ?></p>
        </div>

        <!-- Verifica se o usuário logado é o criador do projeto ou se tem cargo de administrador -->
        <?php if (isset($_SESSION['usuario_id']) && ($usuario_cargo == 1 || $_SESSION['usuario_id'] == $projeto['usuario_id'])): ?>
            <div class="text-center mt-4">
                <a href="<?= URL ?>projeto/editar/<?= $projeto['id'] ?>" class="btn btn-warning">Editar Projeto</a>
            </div>
        <?php endif; ?>

        <!-- Relatórios -->
        <?php if (isset($relatorios) && !empty($relatorios)): ?>
            <div class="container mt-5">
                <h3 class="text-primary mb-4">Relatórios</h3>
                <div class="row">
                    <?php foreach ($relatorios as $relatorio): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title"><?= htmlspecialchars($relatorio['titulo']) ?></h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text"><?= htmlspecialchars($relatorio['conteudo']) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <p class="text-center mt-3">Nenhum relatório disponível.</p>
        <?php endif; ?>

        <!-- Atividades -->
        <?php if (isset($atividades) && !empty($atividades)): ?>
            <div class="container mt-5">
                <h3 class="text-success mb-4">Atividades</h3>
                <div class="row">
                    <?php foreach ($atividades as $atividade): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title"><?= htmlspecialchars($atividade['titulo']) ?></h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text"><?= htmlspecialchars($atividade['conteudo']) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <p class="text-center mt-3">Nenhuma atividade disponível.</p>
        <?php endif; ?>

        <!-- Formulário de Adicionar Relatório -->
        <?php if (isset($_SESSION['usuario_id']) && $usuarioParticipa): ?>
            <div class="report">
                <h5>Adicionar Relatório ou Atividade</h5>
                <form action="<?= URL ?>projeto/adicionar_relatorio/<?= $projeto['id'] ?>" method="post">
                    <div class="form-group">
                        <label for="titulo">Título</label>
                        <input type="text" id="titulo" name="titulo" class="form-control" placeholder="Digite o título" required>
                    </div>
                    <div class="form-group">
                        <label for="conteudo">Conteúdo</label>
                        <textarea id="conteudo" name="conteudo" class="form-control" rows="4" placeholder="Digite o conteúdo" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="tipo">Tipo</label>
                        <select id="tipo" name="tipo" class="form-control" required>
                            <option value="relatorio">Relatório</option>
                            <option value="atividade">Atividade</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Adicionar</button>
                </form>
            </div>
        <?php else: ?>
            <button class="btn btn-primary" disabled>Você não pode adicionar relatórios ou atividades</button>
        <?php endif; ?>
        <form method="POST" action="<?= URL ?>projeto/participar/<?= $projeto['id'] ?>">
            <button type="submit" class="btn btn-success">Participar do Projeto</button>
        </form>


        <div class="text-center mt-5">
            <a href="#" class="btn-project">Acompanhar Projeto</a>
        </div>
    </div>

    <?php include './../app/views/partial/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
