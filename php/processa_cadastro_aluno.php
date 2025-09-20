<?php
require 'sessoes.php';
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_aluno = $_POST['nome_aluno'];
    $cpf = $_POST['cpf'];
    $data_nascimento = $_POST['data_nascimento'];
    $turma = $_POST['turma'];

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
        header("Location: ../pages/cadastro_aluno.php?sucesso=1");
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            header("Location: ../pages/cadastro_aluno.php?erro=CPF já cadastrado.");
        } else {
            die("Erro ao cadastrar aluno: " . $e->getMessage());
        }
    }
}
?>