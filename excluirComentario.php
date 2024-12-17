<?php
include("configA.php");
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? null;

    if (!$id) {
        echo json_encode(['status' => 'error', 'message' => 'ID do comentário não fornecido.']);
        exit();
    }

    // Verifique se o usuário tem permissão para excluir
    if (isset($_SESSION['admin_email'])) {
        $email = $_SESSION['admin_email'];

        // Query para excluir o comentário
        $sql = "DELETE FROM comentario WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Comentário excluído com sucesso.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao excluir comentário.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Usuário não autenticado.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método não permitido.']);
}
?>