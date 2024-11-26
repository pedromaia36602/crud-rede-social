<?php
// Conexão com o banco
include 'conexao.php';

$data = json_decode(file_get_contents('php://input'), true);

$amizade_id = $data['amizade_id'];
$status = $data['status']; // 'aceito' ou 'rejeitado'

if ($amizade_id && in_array($status, ['aceito', 'rejeitado'])) {
    try {
        $query = $pdo->prepare("UPDATE amizades SET status = :status WHERE id = :amizade_id");
        $query->execute(['status' => $status, 'amizade_id' => $amizade_id]);

        echo json_encode(['status' => 'success', 'message' => 'Solicitação atualizada.']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar solicitação.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Dados inválidos.']);
}
?>
