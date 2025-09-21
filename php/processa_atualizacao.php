<?php
require 'sessoes.php';
require 'conexao.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido.'], JSON_UNESCAPED_UNICODE);
    exit();
}

$id_responsavel = $_POST['id_responsavel'] ?? 0;
$nome_responsavel = $_POST['nome_responsavel'] ?? '';
$parentesco = $_POST['parentesco'] ?? '';

// Validação
$id_responsavel_validado = filter_var($id_responsavel, FILTER_VALIDATE_INT);
if (!$id_responsavel_validado || empty($nome_responsavel) || empty($parentesco)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Dados inválidos ou campos obrigatórios não preenchidos.'], JSON_UNESCAPED_UNICODE);
    exit();
}

try {
    // Por enquanto, não estamos atualizando a imagem aqui, apenas os dados de texto.
    // A lógica de atualização de imagem pode ser uma funcionalidade futura.
    $sql = "UPDATE responsaveis SET nome_completo = :nome, parentesco = :parentesco WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nome' => $nome_responsavel,
        'parentesco' => $parentesco,
        'id' => $id_responsavel_validado
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Responsável atualizado com sucesso!'], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['success' => true, 'message' => 'Nenhuma alteração foi detectada.'], JSON_UNESCAPED_UNICODE);
    }
    exit();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro no servidor ao tentar atualizar o responsável.'], JSON_UNESCAPED_UNICODE);
    exit();
}
?>