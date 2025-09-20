<?php require 'php/sessoes.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Reconhecimento Facial</title>
    <link rel="stylesheet" href="css/main.css">
    <script defer src="js/script.js"></script>
</head>
<body>
    <?php require 'sidebar.php'; ?>
     <div class="main-content">
        <header><h1>Realizar Reconhecimento para Retirada</h1></header>
        <main>
            <p>Aponte a câmera para o rosto do responsável.</p>
            <div class="video-container">
                <video id="video" width="720" height="560" autoplay muted></video>
            </div>
            <div id="resultado" class="status-message">Aguardando reconhecimento...</div>
        </main>
    </div>
</body>
</html>