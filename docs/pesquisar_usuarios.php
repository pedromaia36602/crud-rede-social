<?php
include 'conexao.php';
session_start();
$usuarioId = $_SESSION['usuario_id'] ?? null;

$nome = $_GET['nome'] ?? '';

if ($nome) {
    try {
        $query = $pdo->prepare("
    SELECT id, nome, foto_perfil
    FROM usuarios
    WHERE nome LIKE :nome
    LIMIT 10
");
$query->execute(['nome' => "%$nome%"]);

        $usuarios = $query->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status' => 'success', 'usuarios' => $usuarios]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao buscar usuários.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Nome não informado.']);
}
?>
