<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php?erro=acesso_negado");
    exit();
}
require '../php/conexao.php'; 
?>