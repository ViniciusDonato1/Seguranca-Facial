<?php
require 'sessoes.php';
require 'conexao.php';

// Define que a resposta será em formato JSON
header('Content-Type: application/json');

// Verifica se o método da requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Método não permitido
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
    exit();
}

$nome_aluno = $_POST['nome_aluno'] ?? '';
$cpf = $_POST['cpf'] ?? '';
$data_nascimento = $_POST['data_nascimento'] ?? '';
$turma = $_POST['turma'] ?? '';

// Validação simples
if (empty($nome_aluno) || empty($cpf) || empty($data_nascimento)) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Por favor, preencha todos os campos obrigatórios.']);
    exit();
}

try {
    $sql = "INSERT INTO alunos (id_instituicao, nome_completo, cpf, data_nascimento, turma) VALUES (:id_inst, :nome, :cpf, :data_nasc, :turma)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id_inst' => $id_instituicao,
        'nome' => $nome_aluno,
        'cpf' => $cpf,
        'data_nasc' => $data_nascimento,
        'turma' => $turma
    ]);

    // Se tudo deu certo, envia uma resposta de sucesso
    echo json_encode(['success' => true, 'message' => 'Aluno cadastrado com sucesso!']);
    exit();

} catch (PDOException $e) {
    // Trata erros específicos, como CPF duplicado
    if ($e->errorInfo[1] == 1062) {
        http_response_code(409); // Conflict
        echo json_encode(['success' => false, 'message' => 'Este CPF já está cadastrado no sistema.']);
    } else {
        // Trata outros erros de banco de dados
        http_response_code(500); // Internal Server Error
        // Em produção, seria bom logar o $e->getMessage() em vez de exibi-lo
        echo json_encode(['success' => false, 'message' => 'Erro no servidor ao tentar cadastrar o aluno.']);
    }
    exit();
}
?>