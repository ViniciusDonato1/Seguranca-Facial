<?php
require 'sessoes.php';
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_responsavel = $_POST['nome_responsavel'];
    $parentesco = $_POST['parentesco'];
    $cpfs_alunos_str = $_POST['cpfs_alunos'];
    $imagens = [
        1 => $_POST['imagem_base64_1'], 2 => $_POST['imagem_base64_2'], 3 => $_POST['imagem_base64_3'],
        4 => $_POST['imagem_base64_4'], 5 => $_POST['imagem_base64_5'],
    ];
    $cpfs_alunos = array_map('trim', explode(',', $cpfs_alunos_str));
    $pdo->beginTransaction();

    try {
        $sql_resp = "INSERT INTO responsaveis (nome_completo, parentesco) VALUES (:nome, :parentesco)";
        $stmt_resp = $pdo->prepare($sql_resp);
        $stmt_resp->execute(['nome' => $nome_responsavel, 'parentesco' => $parentesco]);
        $id_responsavel = $pdo->lastInsertId();

        foreach ($cpfs_alunos as $cpf) {
            $sql_find_aluno = "SELECT id FROM alunos WHERE cpf = :cpf AND id_instituicao = :id_inst";
            $stmt_find = $pdo->prepare($sql_find_aluno);
            $stmt_find->execute(['cpf' => $cpf, 'id_inst' => $id_instituicao]);
            $aluno = $stmt_find->fetch(PDO::FETCH_ASSOC);
            if ($aluno) {
                $sql_link = "INSERT INTO aluno_responsavel (id_aluno, id_responsavel) VALUES (:id_aluno, :id_resp)";
                $stmt_link = $pdo->prepare($sql_link);
                $stmt_link->execute(['id_aluno' => $aluno['id'], 'id_resp' => $id_responsavel]);
            } else { throw new Exception("Aluno com CPF $cpf não encontrado."); }
        }
        
        foreach ($imagens as $index => $imagem_base64) {
            if (empty($imagem_base64)) { throw new Exception("A captura das 5 fotos é obrigatória."); }
            $python_service_url = 'http://127.0.0.1:5000/add_face';
            $data = ['id_responsavel' => $id_responsavel, 'image' => $imagem_base64, 'index' => $index];
            $ch = curl_init($python_service_url);
            curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_POSTFIELDS => json_encode($data), CURLOPT_HTTPHEADER => ['Content-Type: application/json']]);
            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $responseData = json_decode($response, true);
            curl_close($ch);
            if ($httpcode != 200 || !$responseData['success']) {
                throw new Exception("Falha ao registrar o rosto (amostra {$index}): " . ($responseData['error'] ?? 'Erro desconhecido'));
            }
        }
        $pdo->commit();
        header("Location: ../pages/painel.php?sucesso=cadastro_responsavel");
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Erro ao cadastrar: " . $e->getMessage());
    }
}
?>