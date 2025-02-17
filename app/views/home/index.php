<?php 
include('./../app/core/config.php');
?>

<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="public/bootstrap/css/bootstrap.min.css">
    <title>Home - <?= APP_NOME ?></title>
    <style>
        /* Custom styles */
        .hero-image {
            max-height: 400px;
            object-fit: cover;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 600;
        }

        .section-description {
            font-size: 1.2rem;
        }

        .btn-custom {
            background-color: #007bff;
            border-color: #007bff;
            font-size: 1.2rem;
            padding: 12px 30px;
        }

        .btn-custom:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .rounded-image {
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 767px) {
            .hero-image {
                max-height: 250px;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .section-description {
                font-size: 1rem;
            }

            .btn-custom {
                font-size: 1rem;
                padding: 10px 25px;
            }
        }
    </style>
</head>

<body>
    <?php 
    include './../app/views/partial/header.php';
    ?>

    <!-- Hero Section -->
    <div class="container-fluid p-0">
        <img src="public/assets/img/home.png" class="img-fluid hero-image w-100" alt="Imagem principal">
    </div>

    <!-- Welcome Section -->
    <div class="container my-5">
        <div class="row align-items-center mb-5">
            <div class="col-lg-6">
                <h1 class="section-title">Bem-vindo ao <?= APP_NOME ?>!</h1>
                <p class="section-description">
                    O <?= APP_NOME ?> é a sua plataforma para explorar os recursos acadêmicos, culturais e tecnológicos oferecidos pelo Instituto Federal de Rondônia - Campus Guajará-Mirim. Aqui você encontra informações essenciais, eventos e ferramentas para enriquecer sua experiência educacional e profissional.
                </p>
            </div>
            <div class="col-lg-6">
                <img src="public/assets/img/campus.jpeg" class="img-fluid rounded-image" alt="Imagem do campus">
            </div>
        </div>

        <!-- About Section -->
        <div class="row align-items-center">
            <div class="col-lg-6 order-lg-2">
                <h1 class="section-title">Sobre o <?= APP_NOME ?></h1>
                <p class="section-description">
                    O <?= APP_NOME ?> foi desenvolvido para oferecer suporte aos alunos e professores do Instituto Federal de Rondônia - Campus Guajará-Mirim. Nosso objetivo é facilitar a comunicação e promover o acesso a projetos acadêmicos.
                </p>
                <p class="section-description">
                    Acreditamos no poder da educação como uma ferramenta transformadora e trabalhamos para tornar o aprendizado mais acessível e organizado. Seja bem-vindo e aproveite os recursos disponíveis!
                </p>
            </div>
            <div class="col-lg-6 order-lg-1">
                <img src="public/assets/img/campus.jpeg" class="img-fluid rounded-image" alt="Imagem do campus">
            </div>
        </div>
    </div>

    <!-- Call to Action Section -->
    <div class="container text-center my-5">
        <a href="<?= URL ?>projetos" class="btn btn-custom btn-lg">Ir para Projetos</a>
    </div>

    <?php include './../app/views/partial/footer.php'; ?>

    <script src="<?= URL ?>/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
