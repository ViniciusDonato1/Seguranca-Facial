<?php
require 'sessoes.php';
require 'conexao.php';

// Define que a resposta será em formato JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
    exit();
}

$id_aluno = $_POST['id_aluno'] ?? 0;
$nome_completo = $_POST['nome_completo'] ?? '';
$data_nascimento = $_POST['data_nascimento'] ?? '';
$turma = $_POST['turma'] ?? '';

// Validação
$id_aluno_validado = filter_var($id_aluno, FILTER_VALIDATE_INT);
if (!$id_aluno_validado || empty($nome_completo) || empty($data_nascimento)) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Dados inválidos ou campos obrigatórios não preenchidos.']);
    exit();
}

try {
    $sql = "UPDATE alunos 
            SET nome_completo = :nome, data_nascimento = :data_nasc, turma = :turma 
            WHERE id = :id AND id_instituicao = :id_instituicao";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nome' => $nome_completo,
        'data_nasc' => $data_nascimento,
        'turma' => $turma,
        'id' => $id_aluno_validado,
        'id_instituicao' => $id_instituicao
    ]);

    if ($stmt->rowCount() > 0) {
        // Se a atualização foi bem-sucedida
        echo json_encode(['success' => true, 'message' => 'Aluno atualizado com sucesso!']);
    } else {
        // Se nenhum dado foi alterado (o usuário salvou sem mudar nada)
        echo json_encode(['success' => true, 'message' => 'Nenhuma alteração foi detectada.']);
    }
    exit();

} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Erro no servidor ao tentar atualizar o aluno.']);
    exit();
}
?>