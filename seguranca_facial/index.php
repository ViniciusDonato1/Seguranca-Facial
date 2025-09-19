<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Segurança Facial</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <div class="container">
        <h1>Segurança Facial</h1>
        <h2>Login da Instituição</h2>
        <form action="php/processa_login.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required>
            <button type="submit">Entrar</button>
        </form>
        <p>Não tem uma conta? <a href="cadastro_inst.php">Cadastre-se aqui</a></p>
        <?php
        if (isset($_GET['erro'])) { echo '<p class="error">Usuário ou senha inválidos!</p>'; }
        if (isset($_GET['sucesso'])) { echo '<p class="success">Cadastro realizado com sucesso! Faça o login.</p>'; }
        ?>
    </div>
</body>
</html>