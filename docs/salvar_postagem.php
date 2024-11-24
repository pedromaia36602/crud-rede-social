<?php
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não logado.']);
    exit;
}

$db = new SQLite3('rede_social.db');

$texto = isset($_POST['postagemTexto']) ? $_POST['postagemTexto'] : null;


$usuario_id = $_SESSION['usuario_id'];
$texto = $_POST['postagemTexto'];
$imagem = null;

// Diretório para uploads
$uploadDir = 'uploads/';

// Processa a imagem, se enviada
if (!empty($_FILES['imagemPost']['name'])) {
    $imagem = $uploadDir . basename($_FILES['imagemPost']['name']);
    if (!move_uploaded_file($_FILES['imagemPost']['tmp_name'], $imagem)) {
        echo json_encode(['success' => false, 'message' => 'Erro ao salvar a imagem.']);
        exit;
    }
}

// Insere a postagem no banco de dados
$stmt = $db->prepare("INSERT INTO postagens (usuario_id, texto, imagem, data_postagem) VALUES (:usuario_id, :texto, :imagem, datetime('now'))");
$stmt->bindValue(':usuario_id', $usuario_id, SQLITE3_INTEGER);
$stmt->bindValue(':texto', $texto, SQLITE3_TEXT);
$stmt->bindValue(':imagem', $imagem, SQLITE3_TEXT);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Postagem salva com sucesso!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar a postagem.']);
}
?>
