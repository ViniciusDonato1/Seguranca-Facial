<?php
require '../php/sessoes.php';
require '../php/conexao.php';

$id_responsavel = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_responsavel) { header("Location: gerenciar_responsaveis.php"); exit(); }

$sql = "SELECT * FROM responsaveis WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id_responsavel]);
$responsavel = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$responsavel) { header("Location: gerenciar_responsaveis.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Responsável</title>
    <link rel="stylesheet" href="../css/main.css">
    <script type="module" defer src="../js/script.js"></script>
</head>
<body>
    <?php require '../templates/sidebar.php'; ?>
    <div class="main-content">
        <header><h1>Editar Responsável: <?php echo htmlspecialchars($responsavel['nome_completo']); ?></h1></header>
        <main>
            <main>
    <form id="formEditaResponsavel" action="../php/processa_atualizacao.php" method="POST">
        <input type="hidden" name="id_responsavel" value="<?php echo $responsavel['id']; ?>">

        <label for="nome_responsavel">Nome Completo:</label>
        <input type="text" id="nome_responsavel" name="nome_responsavel" value="<?php echo htmlspecialchars($responsavel['nome_completo']); ?>" required>

        <label for="parentesco">Parentesco:</label>
        <input type="text" id="parentesco" name="parentesco" value="<?php echo htmlspecialchars($responsavel['parentesco']); ?>" required>

        <div id="formStatus" class="status-message" style="display: none;"></div>

        <button type="submit" id="submitBtn">Salvar Alterações</button>
    </form>
</main>
        </main>
    </div>
</body>
</html>