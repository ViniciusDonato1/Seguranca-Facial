<?php require 'php/sessoes.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel - <?php echo htmlspecialchars($nome_instituicao); ?></title>
    <link rel="stylesheet" href="css/main.css">
    <script defer src="js/script.js"></script>
</head>
<body>
    <?php require 'sidebar.php'; ?>
    <div class="main-content">
        <header><h1>Página Inicial</h1></header>
        <main>
            <p>Selecione uma opção no menu lateral para começar.</p>
        </main>
    </div>
</body>
</html>