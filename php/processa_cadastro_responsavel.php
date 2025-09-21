<?php
require 'sessoes.php';
require 'conexao.php';

// Define que a resposta será em formato JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido.'], JSON_UNESCAPED_UNICODE);
    exit();
}

$pdo->beginTransaction();

try {
    $nome_responsavel = $_POST['nome_responsavel'] ?? '';
    $parentesco = $_POST['parentesco'] ?? '';
    $cpfs_alunos_str = $_POST['cpfs_alunos'] ?? '';
    $imagens = [
        1 => $_POST['imagem_base64_1'] ?? '', 2 => $_POST['imagem_base64_2'] ?? '', 3 => $_POST['imagem_base64_3'] ?? '',
        4 => $_POST['imagem_base64_4'] ?? '', 5 => $_POST['imagem_base64_5'] ?? '',
    ];

    // Validações
    if (empty($nome_responsavel) || empty($parentesco) || empty($cpfs_alunos_str) || in_array('', $imagens, true)) {
        throw new Exception("Todos os campos, incluindo as 5 fotos, são obrigatórios.");
    }

    $cpfs_alunos = array_map('trim', explode(',', $cpfs_alunos_str));

    // Insere o responsável
    $sql_resp = "INSERT INTO responsaveis (nome_completo, parentesco) VALUES (:nome, :parentesco)";
    $stmt_resp = $pdo->prepare($sql_resp);
    $stmt_resp->execute(['nome' => $nome_responsavel, 'parentesco' => $parentesco]);
    $id_responsavel = $pdo->lastInsertId();

    // Vincula o responsável aos alunos
    foreach ($cpfs_alunos as $cpf) {
        $sql_find_aluno = "SELECT id FROM alunos WHERE cpf = :cpf AND id_instituicao = :id_inst";
        $stmt_find = $pdo->prepare($sql_find_aluno);
        $stmt_find->execute(['cpf' => $cpf, 'id_inst' => $id_instituicao]);
        $aluno = $stmt_find->fetch(PDO::FETCH_ASSOC);

        if ($aluno) {
            $sql_link = "INSERT INTO aluno_responsavel (id_aluno, id_responsavel) VALUES (:id_aluno, :id_resp)";
            $stmt_link = $pdo->prepare($sql_link);
            $stmt_link->execute(['id_aluno' => $aluno['id'], 'id_resp' => $id_responsavel]);
        } else {
            throw new Exception("Aluno com CPF '$cpf' não foi encontrado ou não pertence a esta instituição.");
        }
    }

    // Envia as imagens para o serviço Python
    foreach ($imagens as $index => $imagem_base64) {
        $python_service_url = 'http://127.0.0.1:5000/add_face';
        $data = ['id_responsavel' => $id_responsavel, 'image' => $imagem_base64, 'index' => $index];
        $ch = curl_init($python_service_url);
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_POSTFIELDS => json_encode($data), CURLOPT_HTTPHEADER => ['Content-Type: application/json']]);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $responseData = json_decode($response, true);
        curl_close($ch);
        if ($httpcode != 200 || !($responseData['success'] ?? false)) {
            throw new Exception("Falha ao registrar o rosto (foto {$index}): " . ($responseData['error'] ?? 'Erro desconhecido no serviço de IA.'));
        }
    }

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Responsável cadastrado com sucesso!'], JSON_UNESCAPED_UNICODE);
    exit();

} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(400); // Bad Request (erro do cliente ou de dados)
    echo json_encode(['success' => false, 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
    exit();
}
?>