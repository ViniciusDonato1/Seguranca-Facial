<?php
require 'auth.php';

$sql = "SELECT i.id, i.nome, i.email, i.data_cadastro,
            (SELECT COUNT(*) FROM alunos WHERE id_instituicao = i.id) AS total_alunos
        FROM instituicoes i ORDER BY i.data_cadastro DESC";
$stmt = $pdo->query($sql);
$instituicoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/main.css">
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const currentTheme = localStorage.getItem('theme') || 'light-mode';
            document.body.classList.add(currentTheme);
        });
    </script>
</head>
<body class="admin-page">
    <div class="sidebar">
        <h2>Admin</h2>
        <p>Bem-vindo, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></p>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="logout.php">Sair</a></li>
        </ul>
    </div>
    <div class="main-content">
        <header><h1>Gerenciamento de Instituições</h1></header>
        <main>
            <?php if(isset($_GET['sucesso'])): ?>
                <p class="success"><?php echo htmlspecialchars($_GET['sucesso']); ?></p>
            <?php endif; ?>
            <table>
                <thead>
                    <tr><th>Nome da Instituição</th><th>Email</th><th>Alunos</th><th>Data de Cadastro</th><th>Ação</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($instituicoes as $inst): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($inst['nome']); ?></td>
                        <td><?php echo htmlspecialchars($inst['email']); ?></td>
                        <td><?php echo $inst['total_alunos']; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($inst['data_cadastro'])); ?></td>
                        <td>
                            <a class="btn-action btn-delete" href="actions/processa_excluir_inst.php?id=<?php echo $inst['id']; ?>" onclick="return confirm('ATENÇÃO: Excluir esta instituição irá apagar TODOS os seus dados permanentemente. Continuar?');">Excluir</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>