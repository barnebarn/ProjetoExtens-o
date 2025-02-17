<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editando <?= $projeto['title'] ?></title>
    <link rel="stylesheet" href="<?= URL ?>/bootstrap/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" href="public/assets/img/projifMin.png">
    <script>
        $(document).ready(function() {
            // Função para alterar o status do participante via AJAX
            $('.status-participante').change(function() {
                const participanteId = $(this).data('participante-id');
                const status = $(this).val();
                const projetoId = $(this).data('projeto-id');
                
                $.ajax({
                    url: '<?= URL ?>projeto/alterar_status/' + projetoId + '/' + participanteId,
                    method: 'POST',
                    data: { status: status },
                    success: function(response) {
                        alert('Status alterado com sucesso!');
                    },
                    error: function() {
                        alert('Erro ao alterar status. Tente novamente.');
                    }
                });
            });
        });
    </script>
</head>
<body>
    <?php include './../app/views/partial/header.php'; ?>

    <div class="container my-5">
        <div class="row">
            <!-- Coluna para o Formulário -->
            <div class="col-md-8">
                <h2 class="text-center mb-4">Editando Projeto: <?= $projeto['title'] ?></h2>
                <form action="<?= URL ?>projeto/salvar" method="POST" enctype="multipart/form-data">

                    <input type="hidden" name="id" value="<?= $projeto['id'] ?>">

                    <div class="mb-3">
    <label for="banner" class="form-label">Imagem Banner</label>

    <?php if (!empty($projeto['banner'])): ?>
        <div class="mb-3">
            <img src="<?= URL . 'public/' . $projeto['banner'] ?>" alt="Banner do Projeto" class="img-fluid rounded shadow-sm">
        </div>
        <!-- Campo oculto para armazenar a imagem existente -->
        <input type="hidden" name="banner_existente" value="<?= htmlspecialchars($projeto['banner']) ?>">
        <!-- Botão para trocar a imagem -->
        <button type="button" class="btn btn-warning btn-sm" id="trocarBanner">Trocar Imagem</button>
    <?php endif; ?>

    <!-- Campo de upload inicialmente oculto -->
    <input type="file" class="form-control mt-2" id="banner" name="banner" accept="image/*" <?= empty($projeto['banner']) ? 'required' : 'style="display: none;"' ?>>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const botaoTrocar = document.getElementById("trocarBanner");
    const inputBanner = document.getElementById("banner");

    if (botaoTrocar) {
        botaoTrocar.addEventListener("click", function() {
            inputBanner.style.display = "block"; // Mostra o campo de upload
            botaoTrocar.style.display = "none"; // Esconde o botão
        });
    }
});
</script>



                    <div class="mb-3">
                        <label for="title" class="form-label">Nome do Projeto</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($projeto['title']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição do Projeto</label>
                        <input type="text" class="form-control" id="description" name="description" value="<?= htmlspecialchars($projeto['description']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="technologies" class="form-label">Tecnologias Utilizadas</label>
                        <input type="text" class="form-control" id="technologies" name="technologies" value="<?= htmlspecialchars($projeto['Tecnologias']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="objective" class="form-label">Objetivo do Projeto</label>
                        <input type="text" class="form-control" id="objective" name="objective" value="<?= htmlspecialchars($projeto['texto']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Selecione o Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="A" <?= $projeto['status'] == 'ativo' ? 'selected' : '' ?>>Ativo</option>
                            <option value="C" <?= $projeto['status'] == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                            <option value="F" <?= $projeto['status'] == 'finalizado' ? 'selected' : '' ?>>Finalizado</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Salvar Projeto</button>
                </form>

                <form action="<?= URL . 'projeto/apagar/' . $projeto['id'] ?>" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este projeto?')">
                    <button type="submit" class="btn btn-danger mt-3 w-100">Excluir Projeto</button>
                </form>
            </div>

            <!-- Coluna para Gerenciamento de Participantes -->
            <div class="col-md-4">
                <?php 
                use App\Models\Projeto;
                $participantes = Projeto::buscarParticipantes($projeto['id']);
                ?>

                <div class="mt-5">
                    <h3>Gerenciar Participantes</h3>

                    <?php if (!empty($participantes)): ?>
                        <ul class="list-group">
                            <?php foreach ($participantes as $participante): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?= htmlspecialchars($participante['nome']) ?></strong>
                                        <br>
                                        <small><?= htmlspecialchars($participante['email']) ?></small>
                                    </div>

                                    <select class="form-select form-select-sm status-participante" data-participante-id="<?= $participante['id'] ?>" data-projeto-id="<?= $projeto['id'] ?>">
                                        <option value="pendente" <?= $participante['status'] == 'pendente' ? 'selected' : '' ?>>Pendente</option>
                                        <option value="aprovado" <?= $participante['status'] == 'aprovado' ? 'selected' : '' ?>>Aprovado</option>
                                        <option value="removido" <?= $participante['status'] == 'removido' ? 'selected' : '' ?>>Removido</option>
                                    </select>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-muted">Nenhum participante no momento.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php include './../app/views/partial/footer.php'; ?>

    <script src="<?= URL ?>/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
