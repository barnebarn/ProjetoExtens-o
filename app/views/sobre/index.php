<?php 
include('./../app/core/config.php');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= URL ?>/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Sobre Nós - <?= APP_NOME ?></title>
    <link rel="icon" href="public/assets/img/projifMin.png">
    <style>
        .team-photo {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 15px;
        }

        .team-member-icon {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 1.5rem;
            color: white;
            background-color: #28a745;
            border-radius: 50%;
            padding: 8px;
        }

        .team-member-name {
            font-weight: bold;
            font-size: 1.2rem;
            color: #333;
        }

        .team-member-role {
            font-size: 1rem;
            color: #555;
        }

        .team-card {
            position: relative;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            border-radius: 15px;
            overflow: hidden;
        }

        .team-card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .contact-btn {
            background: linear-gradient(to right, #28a745, #218838);
            color: #fff;
            border-radius: 30px;
            padding: 12px 30px;
            font-size: 1.2rem;
            text-transform: uppercase;
            transition: 0.3s;
        }

        .contact-btn:hover {
            background: linear-gradient(to right, #218838, #28a745);
            transform: scale(1.05);
        }

        .section-title {
            font-size: 2rem;
            font-weight: bold;
            color: #007bff;
            margin-top: 30px;
            margin-bottom: 20px;
        }

        .lead-text {
            font-size: 1.1rem;
            color: #555;
        }
    </style>
</head>

<body>

    <?php include './../app/views/partial/header.php'; ?>

    <div class="container my-5">

        <div class="text-center">
            <h1 class="pt-5 section-title">Sobre a Equipe</h1>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <p class="lead-text text-center mb-4">
                    A equipe por trás do <?= APP_NOME ?> é formada por um grupo de alunos do Instituto Federal de Rondônia - Campus Guajará-Mirim, que se uniram com o objetivo de contribuir no gerenciamento e desenvolvimento de projetos acadêmicos e culturais. 
                    Motivados pela vontade de melhorar a experiência educacional e proporcionar soluções eficientes, decidimos criar uma plataforma que seja útil tanto para alunos quanto para professores, com foco na organização e no fácil acesso às informações.
                </p>
                <p class="lead-text text-center mb-4">
                    Nosso trabalho é guiado pela paixão pela educação e pelo desejo de facilitar a gestão de projetos, além de promover a troca de conhecimentos entre a comunidade acadêmica. 
                    Cada membro da nossa equipe traz uma experiência única, e juntos buscamos inovar e agregar valor ao ambiente escolar.
                </p>
                <p class="text-center mb-4">
                    <i class="fas fa-users fa-3x text-success"></i>
                </p>
                <p class="text-center">
                    Estamos sempre abertos a novas ideias e colaborações, com o intuito de melhorar a plataforma e atender ainda mais às necessidades de todos. <br>
                    Juntos, somos mais fortes!
                </p>
            </div>
        </div>

        <!-- Imagens da equipe -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-2 col-sm-4 mb-4">
                <div class="team-card text-center">
                    <img src="./public/assets/img/foto1.jpg" class="team-photo" alt="Função 1">
                    <i class="fas fa-code team-member-icon"></i>
                    <div class="mt-2">
                        <p class="team-member-name">NOME MOMENTANEO</p>
                        <h5 class="team-member-role">Desenvolvedor</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-2 col-sm-4 mb-4">
                <div class="team-card text-center">
                    <img src="./public/assets/img/foto2.jpg" class="team-photo" alt="Função 2">
                    <i class="fas fa-paint-brush team-member-icon"></i>
                    <div class="mt-2">
                        <p class="team-member-name">NOME MOMENTANEO</p>
                        <h5 class="team-member-role">Designer</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-2 col-sm-4 mb-4">
                <div class="team-card text-center">
                    <img src="./public/assets/img/foto3.jpg" class="team-photo" alt="Função 3">
                    <i class="fas fa-lightbulb team-member-icon"></i>
                    <div class="mt-2">
                        <p class="team-member-name">NOME MOMENTANEO</p>
                        <h5 class="team-member-role">Idealizador</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-2 col-sm-4 mb-4">
                <div class="team-card text-center">
                    <img src="./public/assets/img/foto4.jpg" class="team-photo" alt="Função 4">
                    <i class="fas fa-project-diagram team-member-icon"></i>
                    <div class="mt-2">
                        <p class="team-member-name">NOME MOMENTANEO</p>
                        <h5 class="team-member-role">Gerente do Projeto</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-2 col-sm-4 mb-4">
                <div class="team-card text-center">
                    <img src="./public/assets/img/foto5.jpg" class="team-photo" alt="Função 5">
                    <i class="fas fa-address-book team-member-icon"></i>
                    <div class="mt-2">
                        <p class="team-member-name">NOME MOMENTANEO</p>
                        <h5 class="team-member-role">Analista</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botão de Contato -->
        <div class="text-center mt-4">
            <a href="mailto:contato@empresa.com?subject=Contato sobre o <?= APP_NOME ?>&body=Olá, gostaria de entrar em contato com a equipe sobre" class="contact-btn">
                Entrar em Contato
            </a>
        </div>

    </div>

    <?php include './../app/views/partial/footer.php'; ?>

    <script src="<?= URL ?>/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
