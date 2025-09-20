<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Segurança Facial</title>
    <link rel="stylesheet" href="../css/main.css">
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const currentTheme = localStorage.getItem('theme') || 'light-mode';
            document.body.classList.add(currentTheme);
        });
    </script>
</head>
<body class="admin-page">
    <div class="container">
        <h1>Área Administrativa</h1>
        <h2>Login</h2>
        <form action="actions/processa_login_admin.php" method="POST">
            <label for="username">Usuário:</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Senha:</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Entrar</button>
        </form>
        <?php if(isset($_GET['erro'])): ?>
            <p class="error">Usuário ou senha inválidos.</p>
        <?php endif; ?>
    </div>
</body>
</html>