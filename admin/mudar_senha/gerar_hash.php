<?php
$novaSenha = 'novaSenha456'; 

echo "Seu novo HASH (o 'cadeado' para o banco de dados) é:<br>";
echo password_hash($novaSenha, PASSWORD_DEFAULT);
//executar esse de cima no navegador, copiar o novo cadeado
?> 