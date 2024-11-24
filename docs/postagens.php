<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não logado']);
    exit;
}

$db = new SQLite3('rede_social.db');

// Verifica se a conexão com o banco foi feita corretamente
if (!$db) {
    echo json_encode(['success' => false, 'message' => 'Erro ao conectar ao banco de dados']);
    exit;
}

// Prepara a consulta para incluir as informações do usuário
$stmt = $db->prepare("
    SELECT 
        p.texto, 
        p.imagem, 
        p.video, 
        p.data_postagem, 
        u.nome AS nome_usuario, 
        u.foto_perfil AS foto_usuario 
    FROM postagens p
    JOIN usuarios u ON p.usuario_id = u.id
    WHERE p.usuario_id = :usuario_id
    ORDER BY p.data_postagem DESC
");
$stmt->bindValue(':usuario_id', $_SESSION['usuario_id'], SQLITE3_INTEGER);

$resultado = $stmt->execute();

// Depura o resultado
if (!$resultado) {
    echo json_encode(['success' => false, 'message' => 'Erro na execução da consulta']);
    exit;
}

$postagens = [];
while ($post = $resultado->fetchArray(SQLITE3_ASSOC)) {
    // Adiciona uma foto padrão se o usuário não tiver foto
    $post['foto_usuario'] = $post['foto_usuario'] ?: 'images/usuario_default.jpg';
    $postagens[] = $post;
}

// Verifica se há postagens
if (empty($postagens)) {
    echo json_encode(['success' => false, 'message' => 'Nenhuma postagem encontrada']);
    exit;
}

// Retorna os dados
echo json_encode(['success' => true, 'postagens' => $postagens]);
?>
