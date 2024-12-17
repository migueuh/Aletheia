<?php

// ERROR CORREÇÃO
// Essa configuração facilita o desenvolvimento, 
// Faz com que o PHP retorne o erro na tela caso ele aconteça
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// // ========== Config ============
$_DB['server'] = 'localhost'; // Servidor MySQL
$_DB['user'] = 'root'; // Usuário MySQL

// ERROR CORREÇÃO
// Caso você esteja usando o usuario padrão e ele não tenha senha, 
// esse campo deve se uma string vazia
$_DB['password'] = 'banana123'; // Senha MySQL
$_DB['database'] = 'aletheia_db'; // Banco de dados MySQL
// ==============================
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Desativa relatórios de erros
try {
$mysqli = new mysqli($_DB['server'], $_DB['user'], $_DB['password'], $_DB['database']);
$mysqli->set_charset("utf8mb4");
} catch (Exception $e) {
error_log($e->getMessage());
exit('Alguma coisa estranha aconteceu...');
}
?>