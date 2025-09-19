<?php
require 'sessoes.php';
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_aluno = $_POST['id_aluno'];
    $nome_completo = $_POST['nome_completo'];
    $data_nascimento = $_POST['data_nascimento'];
    $turma = $_POST['turma'];

    // Validar se o ID é um número e pertence à instituição logada
    $id_aluno_validado = filter_var($id_aluno, FILTER_VALIDATE_INT);
    if (!$id_aluno_validado) {
        die("ID de aluno inválido.");
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
            header("Location: ../gerenciar_alunos.php?sucesso=Aluno atualizado com sucesso!");
        } else {
            throw new Exception("Nenhuma alteração foi feita ou o aluno não pertence à sua instituição.");
        }
    } catch (Exception $e) {
        header("Location: ../gerenciar_alunos.php?erro=Erro ao atualizar aluno: " . $e->getMessage());
    }
}
?>