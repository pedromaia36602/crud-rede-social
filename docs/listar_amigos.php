<?php
// Conexão com o banco
include 'conexao.php';

$usuario_id = $_GET['usuario_id'] ?? null;

if ($usuario_id) {
    try {
        $query = $pdo->prepare("
    SELECT u.id, u.nome, u.foto_perfil
    FROM usuarios u
    INNER JOIN amizades a ON (u.id = a.amigo_id OR u.id = a.usuario_id)
    WHERE (a.usuario_id = :usuario_id OR a.amigo_id = :usuario_id)
      AND a.status = 'aceito'
      AND u.id != :usuario_id
");

        $query->execute(['usuario_id' => $usuario_id]);
        $amigos = $query->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status' => 'success', 'amigos' => $amigos]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao listar amigos.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID do usuário inválido.']);
}
?>
