<?php
require '../php/sessoes.php';
require '../php/conexao.php';

// --- LÓGICA DA BUSCA (ADICIONADO) ---
$termo_busca = $_GET['busca'] ?? '';

$sql = "SELECT a.nome_completo as aluno_nome, r.id as responsavel_id, r.nome_completo as responsavel_nome, r.parentesco
        FROM responsaveis r
        JOIN aluno_responsavel ar ON r.id = ar.id_responsavel
        JOIN alunos a ON ar.id_aluno = a.id
        WHERE a.id_instituicao = :id_instituicao";

$params = ['id_instituicao' => $id_instituicao];

// Adiciona a condição de busca pelo nome do ALUNO
if (!empty($termo_busca)) {
    $sql .= " AND a.nome_completo LIKE :busca";
    $params['busca'] = '%' . $termo_busca . '%';
}

$sql .= " ORDER BY a.nome_completo, r.nome_completo";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Agrupa os resultados por aluno (lógica existente)
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
    <link rel="stylesheet" href="../css/main.css">
    <script type="module" defer src="../js/script.js"></script>
</head>
<body>
    <?php require '../templates/sidebar.php'; ?>
    <div class="main-content">
        <header><h1>Gerenciar Alunos e Responsáveis</h1></header>
        <main>
            <?php if (isset($_GET['sucesso'])): ?><p class="success"><?php echo htmlspecialchars($_GET['sucesso']); ?></p><?php endif; ?>

            <div class="search-filter-container">
                <form action="gerenciar_responsaveis.php" method="GET" class="search-filter-form">
                    <div class="form-group">
                        <label for="busca">Buscar por Nome do Aluno:</label>
                        <input type="search" name="busca" id="busca" placeholder="Digite o nome do aluno..." value="<?php echo htmlspecialchars($termo_busca); ?>">
                    </div>
                    <div class="form-group form-group-buttons">
                        <button type="submit" class="btn-action">Buscar</button>
                        <a href="gerenciar_responsaveis.php" class="btn-action btn-secondary">Limpar</a>
                    </div>
                </form>
            </div>
            <?php if (empty($alunos)): ?>
                <p>Nenhum responsável encontrado com os critérios de busca.</p>
            <?php else: ?>
                <?php foreach ($alunos as $aluno_nome => $responsaveis): ?>
                    <div class="aluno-group">
                        <h3>Aluno: <?php echo htmlspecialchars($aluno_nome); ?></h3>
                        <table>
                            <thead><tr><th>Responsável</th><th>Parentesco</th><th>Ações</th></tr></thead>
                            <tbody>
                                <?php foreach ($responsaveis as $responsavel): ?>
                                <tr>
                                    <td data-label="Responsável"><?php echo htmlspecialchars($responsavel['responsavel_nome']); ?></td>
                                    <td data-label="Parentesco"><?php echo htmlspecialchars($responsavel['parentesco']); ?></td>
                                    <td data-label="Ações">
                                        <a class="btn-action btn-edit" href="editar_responsavel.php?id=<?php echo $responsavel['responsavel_id']; ?>">Editar</a>
                                        <a class="btn-action btn-delete" href="../php/processa_exclusao.php?id=<?php echo $responsavel['responsavel_id']; ?>" onclick="return confirm('Tem certeza? Esta ação remove o responsável e todo o seu histórico.');">Excluir</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
        </main>
    </div>
</body>
</html>