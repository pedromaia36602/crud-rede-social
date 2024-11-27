<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método não permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$amizade_id = $data['amizade_id'];
$status = $data['status'];
$usuario_id = $_SESSION['usuario_id'];

if (!$amizade_id || !$status) {
    echo json_encode(['status' => 'error', 'message' => 'Dados inválidos']);
    exit;
}

$db_path = __DIR__ . '/rede_social.db';
$db = new SQLite3($db_path);

// Atualiza o status da solicitação
$stmt = $db->prepare("UPDATE amizades SET status = :status WHERE id = :amizade_id AND (usuario_id = :usuario_id OR amigo_id = :usuario_id)");
$stmt->bindValue(':status', $status);
$stmt->bindValue(':amizade_id', $amizade_id, SQLITE3_INTEGER);
$stmt->bindValue(':usuario_id', $usuario_id, SQLITE3_INTEGER);
$result = $stmt->execute();

if ($status === 'aceito') {
    // Retorna os dados do novo amigo
    $stmtAmigo = $db->prepare("
        SELECT u.id, u.nome, u.foto_perfil 
        FROM amizades a
        JOIN usuarios u ON u.id = CASE 
            WHEN a.usuario_id = :usuario_id THEN a.amigo_id 
            ELSE a.usuario_id 
        END
        WHERE a.id = :amizade_id
    ");
    $stmtAmigo->bindValue(':usuario_id', $usuario_id, SQLITE3_INTEGER);
    $stmtAmigo->bindValue(':amizade_id', $amizade_id, SQLITE3_INTEGER);
    $amigo = $stmtAmigo->execute()->fetchArray(SQLITE3_ASSOC);

    if ($amigo) {
        echo json_encode(['status' => 'success', 'amigo_id' => $amigo['id'], 'nome' => $amigo['nome'], 'foto_perfil' => $amigo['foto_perfil']]);
        exit;
    }
}

echo json_encode(['status' => 'success']);
?>
