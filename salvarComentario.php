<?php
include("configA.php");
session_start();
// Garantir que o cabeçalho seja JSON
ob_clean();
header('Content-Type: application/json');

$response = [];

// ERROR CORREÇÃO
// Foi nescessario altera o aqrquivo, telaEntrar.php
// pois estava sendo criado a chave : 'admin_email' no objeto $_SESSION em vez de 'email'
// assim ele travava nesta validação;
if (isset($_SESSION['email'])) {
    // Obtém o email da sessão
    $email = $_SESSION['email'];

    // Obtendo os dados JSON recebidos
    $data = json_decode(file_get_contents("php://input"), true);
    // Verificar se os dados JSON foram recebidos corretamente
    if ($data === null) {
        $response = [
            'status' => 'error',
            'message' => 'Erro ao receber dados JSON.'
        ];
    } else {
        $titulo = $data['title'] ?? ''; // Obtém o título do comentário
        $texto = $data['text'] ?? ''; // Obtém o texto do comentário

        // Verificação se os dados são válidos
        if (empty($titulo) || empty($texto)) {
            $response = [
                'status' => 'error',
                'message' => 'Todos os campos são obrigatórios.'
            ];
        } else {

            // ERROR CORREÇÃO
            // A coluna no banco de dados é 'aluno_email' estava sendo referenciada aqui como 'email_aluno'
            
            // A tabela 'comentario' possui uma propriedade de NOT NULL na coluna 'data' assim sendo obrigatorio informar um valor para ela, já que não possui valor default
            
            // No banco de dados foi criada uma FK ('comentario_ibfk_1') que permite apenas inserir registros na tabela 'comentario' caso exista aquele email na tabela 'aluno',
            // problema se encontra quando um admin fica registrado na tabela 'admin' e quando o mesmo tenta criar um comentario, por não ter registro com esse email na tabela aluno,
            // a FK ('comentario_ibfk_1') não permite

            /*
            SUGESTÃO:

                crie uma tabela unica para armazenar os usuario, 
                e adicone uma coluna informando se é um admin, 
                em vez de criar uma tabela a parte para isso ex:

                CREATE TABLE `usuario` (
                    `nome` varchar(255) DEFAULT 'Anônimo',
                    `senha` varchar(255) NOT NULL,
                    `email` varchar(255) NOT NULL,
                    `CPF` varchar(255) NOT NULL,
                    `isAdmin` INT NOT NULL DEFAULT 0, -- SE FOR 0 É ALUNO, SE FOR 1 É ADMIN 
                    PRIMARY KEY (`email`),
                    UNIQUE KEY `CPF` (`CPF`)
                )


                CREATE TABLE `comentario` (
                    `id` int NOT NULL AUTO_INCREMENT,
                    `texto` text NOT NULL,
                    `data` datetime NOT NULL,
                    `email` varchar(255) NOT NULL,
                    `titulo` varchar(255) NOT NULL,
                    PRIMARY KEY (`id`),
                    KEY `email` (`email`),
                    CONSTRAINT `comentario_ibfk_1` FOREIGN KEY (`email`) REFERENCES `usuario` (`email`)
                )



                -- SELECIONA SÓ OS ADMIN
                SELECT * FROM `usuario`
                WHERE isAdmin = 1 

                
                -- SELECIONA SÓ OS ALUNOS
                SELECT * FROM `usuario`
                WHERE isAdmin = 10

            */

            // Inserir comentário no banco
            $sql = "INSERT INTO comentario (titulo, texto, aluno_email, data) VALUES ('$titulo', '$texto', '$email', current_date())";
            if ($mysqli->query($sql) === TRUE) {
                $response = [
                    'status' => 'success',
                    'message' => 'Comentário salvo com sucesso!',
                    'email' => $email
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Erro ao salvar comentário: ' . $mysqli->error
                ];
            }
        }
    }
    } else {
    $response = [
        'status' => 'error',
        'message' => 'Usuário não autenticado.'
    ];
}
echo json_encode($response);

?>