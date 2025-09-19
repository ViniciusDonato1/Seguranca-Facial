<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Instituição</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <div class="container">
        <h1>Cadastro de Instituição</h1>
        <form action="php/processa_cadastro_inst.php" method="POST">
            <label for="nome">Nome da Instituição:</label>
            <input type="text" name="nome" id="nome" required>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required>
            <button type="submit">Cadastrar</button>
        </form>
        <p>Já tem uma conta? <a href="index.php">Faça login</a></p>
    </div>
</body>
</html>