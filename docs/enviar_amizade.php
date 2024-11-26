<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não autenticado.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$usuarioLogadoId = $_SESSION['usuario_id'];
$usuarioAlvoId = $data['usuario_alvo'] ?? null;

if (!$usuarioAlvoId) {
    echo json_encode(['status' => 'error', 'message' => 'ID do usuário alvo não fornecido.']);
    exit;
}

try {
    $query = $pdo->prepare("INSERT INTO amizades (usuario_id, amigo_id, status) VALUES (:usuario_logado, :usuario_alvo, 'pendente')");
    $query->execute([
        'usuario_logado' => $usuarioLogadoId,
        'usuario_alvo' => $usuarioAlvoId,
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Solicitação de amizade enviada.']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao enviar solicitação.']);
    exit;
}
