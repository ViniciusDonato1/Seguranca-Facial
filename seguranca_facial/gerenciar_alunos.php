<?php
require 'php/sessoes.php';
require 'php/conexao.php';

$sql = "SELECT id, nome_completo, cpf, data_nascimento, turma 
        FROM alunos 
        WHERE id_instituicao = :id_instituicao 
        ORDER BY nome_completo ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id_instituicao' => $id_instituicao]);
$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Alunos</title>
    <link rel="stylesheet" href="css/main.css">
    <script defer src="js/script.js"></script>
</head>
<body>
    <?php require 'sidebar.php'; ?>
    <div class="main-content">
        <header><h1>Gerenciar Alunos</h1></header>
        <main>
            <?php if (isset($_GET['sucesso'])): ?><p class="success"><?php echo htmlspecialchars($_GET['sucesso']); ?></p><?php endif; ?>
            <?php if (isset($_GET['erro'])): ?><p class="error"><?php echo htmlspecialchars($_GET['erro']); ?></p><?php endif; ?>
            <table>
                <thead>
                    <tr><th>Nome Completo</th><th>CPF</th><th>Data de Nasc.</th><th>Turma</th><th>Ações</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($alunos as $aluno): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($aluno['nome_completo']); ?></td>
                        <td><?php echo htmlspecialchars($aluno['cpf']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($aluno['data_nascimento'])); ?></td>
                        <td><?php echo htmlspecialchars($aluno['turma']); ?></td>
                        <td>
                            <a class="btn-action btn-edit" href="editar_aluno.php?id=<?php echo $aluno['id']; ?>">Editar</a>
                            <a class="btn-action btn-delete" href="php/processa_exclusao_aluno.php?id=<?php echo $aluno['id']; ?>" onclick="return confirm('ATENÇÃO: Excluir um aluno também removerá permanentemente os responsáveis associados APENAS a ele. Deseja continuar?');">Excluir</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($alunos)): ?>
                    <tr><td colspan="5">Nenhum aluno cadastrado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>