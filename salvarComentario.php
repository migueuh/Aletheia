<?php
include("configA.php");
session_start();
// Garantir que o cabeçalho seja JSON
ob_clean();
header('Content-Type: application/json');

$response = [];

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