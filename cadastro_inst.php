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
    <title>Cadastro - Segurança Facial</title>
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
                <h1>Cadastro</h1>
                <p>Crie uma conta para sua instituição.</p>

                <form action="php/processa_cadastro_inst.php" method="POST">
                    <label for="nome">Instituição</label>
                    <input type="text" name="nome" id="nome" required>

                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>
                    
                    <label for="senha">Senha</label>
                    <input type="password" name="senha" id="senha" required>
                    
                    <button type="submit">Cadastrar</button>
                </form>

                <p style="margin-top: 20px;">Já tem uma conta? <a href="index.php">Faça login</a></p>
            </div>
        </div>
        <div class="branding-section">
            <h2>SEGURANÇA FACIAL</h2>
            <p>Mais que tecnologia, uma solução segura para o cuidado das nossas crianças.</p>
        </div>
    </div>
</body>
</html>