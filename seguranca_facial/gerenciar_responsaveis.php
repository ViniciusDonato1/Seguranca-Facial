<?php
require 'php/sessoes.php';
require 'php/conexao.php';

$sql = "SELECT a.nome_completo as aluno_nome, r.id as responsavel_id, r.nome_completo as responsavel_nome, r.parentesco
        FROM responsaveis r
        JOIN aluno_responsavel ar ON r.id = ar.id_responsavel
        JOIN alunos a ON ar.id_aluno = a.id
        WHERE a.id_instituicao = :id_instituicao ORDER BY a.nome_completo, r.nome_completo";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id_instituicao' => $id_instituicao]);
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
$alunos = [];
foreach ($registros as $registro) {
    $alunos[$registro['aluno_nome']][] = $registro;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Responsáveis</title>
    <link rel="stylesheet" href="css/main.css">
    <script defer src="js/script.js"></script>
</head>
<body>
    <?php require 'sidebar.php'; ?>
    <div class="main-content">
        <header><h1>Gerenciar Alunos e Responsáveis</h1></header>
        <main>
            <?php if (isset($_GET['sucesso'])): ?><p class="success"><?php echo htmlspecialchars($_GET['sucesso']); ?></p><?php endif; ?>
            <?php foreach ($alunos as $aluno_nome => $responsaveis): ?>
                <div class="aluno-group">
                    <h3>Aluno: <?php echo htmlspecialchars($aluno_nome); ?></h3>
                    <table>
                        <thead><tr><th>Responsável</th><th>Parentesco</th><th>Ações</th></tr></thead>
                        <tbody>
                            <?php foreach ($responsaveis as $responsavel): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($responsavel['responsavel_nome']); ?></td>
                                <td><?php echo htmlspecialchars($responsavel['parentesco']); ?></td>
                                <td>
                                    <a class="btn-action btn-edit" href="editar_responsavel.php?id=<?php echo $responsavel['responsavel_id']; ?>">Editar</a>
                                    <a class="btn-action btn-delete" href="php/processa_exclusao.php?id=<?php echo $responsavel['responsavel_id']; ?>" onclick="return confirm('Tem certeza? Esta ação remove o responsável e todo o seu histórico.');">Excluir</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
            <?php if (empty($alunos)): ?><p>Nenhum responsável cadastrado ainda.</p><?php endif; ?>
        </main>
    </div>
</body>
</html>