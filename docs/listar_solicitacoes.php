<?php 
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não autenticado.']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

try {
    $query = $pdo->prepare("
        SELECT a.id AS amizade_id, u.id AS usuario_id, u.nome, u.foto_perfil
        FROM amizades a
        JOIN usuarios u ON a.usuario_id = u.id
        WHERE a.amigo_id = :usuario_id AND a.status = 'pendente'
    ");
    $query->execute(['usuario_id' => $usuario_id]);
    $solicitacoes = $query->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'data' => $solicitacoes]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao listar solicitações.']);
    exit;
}
?>
