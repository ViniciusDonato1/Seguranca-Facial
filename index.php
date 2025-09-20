<?php
session_start();
// Se já estiver logado, redireciona para o painel
if (isset($_SESSION['id_instituicao'])) {
    header("Location: painel.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login - Segurança Facial</title>
    <link rel="stylesheet" href="css/main.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const currentTheme = localStorage.getItem('theme') || 'light-mode';
            document.body.classList.add(currentTheme);
        });
    </script>
</head>
<body>
    <div class="auth-wrapper">
        <div class="form-section">
            <div class="form-container">
                <h1>Bem-vindo!</h1>
                <p>Faça login para continuar.</p>

                <form action="php/processa_login.php" method="POST">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>
                    
                    <label for="senha">Senha</label>
                    <input type="password" name="senha" id="senha" required>
                    
                    <button type="submit">Entrar</button>
                </form>

                <p style="margin-top: 20px;">Não tem uma conta? <a href="cadastro_inst.php">Cadastre-se</a></p>

                <?php
                if (isset($_GET['erro'])) { echo '<p class="error">Usuário ou senha inválidos!</p>'; }
                if (isset($_GET['sucesso'])) { echo '<p class="success">Cadastro realizado! Faça o login.</p>'; }
                ?>
            </div>
        </div>
        <div class="branding-section">
            <h2>SEGURANÇA FACIAL</h2>
            <p>Mais que tecnologia, uma solução segura para o cuidado das nossas crianças.</p>
        </div>
    </div>
</body>
</html>