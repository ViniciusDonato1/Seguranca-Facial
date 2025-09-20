<?php
require 'sessoes.php';
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_responsavel = $_POST['id_responsavel'];
    $nome_responsavel = $_POST['nome_responsavel'];
    $parentesco = $_POST['parentesco'];
    $imagem_base64 = $_POST['imagem_base64'];

    $pdo->beginTransaction();

    try {
        // 1. Atualiza os dados de texto no banco de dados
        $sql = "UPDATE responsaveis SET nome_completo = :nome, parentesco = :parentesco WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nome' => $nome_responsavel,
            'parentesco' => $parentesco,
            'id' => $id_responsavel
        ]);

        // 2. Se uma nova imagem foi enviada, atualiza no serviço Python
        if (!empty($imagem_base64)) {
            $python_service_url = 'http://127.0.0.1:5000/add_face';
            $data = ['id_responsavel' => $id_responsavel, 'image' => $imagem_base64];
            
            $ch = curl_init($python_service_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            
            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $responseData = json_decode($response, true);

            if ($httpcode != 200 || !$responseData['success']) {
                throw new Exception("Falha ao atualizar o rosto no serviço de IA: " . ($responseData['error'] ?? 'Erro desconhecido'));
            }
        }

        $pdo->commit();
        header("Location: ../pages/gerenciar_responsaveis.php?sucesso=Responsável atualizado com sucesso!");

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Erro ao atualizar responsável: " . $e->getMessage());
    }
}
?>