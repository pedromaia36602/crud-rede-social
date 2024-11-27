<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$db = new SQLite3('rede_social.db');

// ID do usuário logado
$usuario_id = $_SESSION['usuario_id'];

// Busca os IDs dos amigos
$queryAmigos = $db->prepare("
    SELECT amigo_id 
    FROM amizades 
    WHERE usuario_id = :usuario_id AND status = 'aceito'
    UNION
    SELECT usuario_id 
    FROM amizades 
    WHERE amigo_id = :usuario_id AND status = 'aceito'
");
$queryAmigos->bindValue(':usuario_id', $usuario_id, SQLITE3_INTEGER);
$resultAmigos = $queryAmigos->execute();

$amigos = [];
while ($amigo = $resultAmigos->fetchArray(SQLITE3_ASSOC)) {
    $amigos[] = $amigo['amigo_id'];
}
$amigos[] = $usuario_id; // Inclui o próprio usuário

// Monta a consulta para buscar posts
$placeholders = implode(',', array_fill(0, count($amigos), '?'));
$queryPosts = $db->prepare("
    SELECT 
        p.texto, p.imagem, p.video, p.data_postagem, 
        u.nome AS nome_usuario, u.foto_perfil 
    FROM postagens p
    JOIN usuarios u ON p.usuario_id = u.id
    WHERE p.usuario_id IN ($placeholders)
    ORDER BY p.data_postagem DESC
");

// Adiciona os IDs ao bind
foreach ($amigos as $index => $id) {
    $queryPosts->bindValue($index + 1, $id, SQLITE3_INTEGER);
}

$resultPosts = $queryPosts->execute();

// Processa os posts
$postagens = [];
while ($postagem = $resultPosts->fetchArray(SQLITE3_ASSOC)) {
    $postagens[] = $postagem;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="body-feed">
<div class="menu">
        <h1>UNIMETROFÓRUM</h1>
        <div class="hamburger" id="hamburger">&#9776;</div>
        <nav class="nav" id="nav">
            <ul>
                <li><a href="feed.php">Feed</a></li>
                <li><a href="perfilusuario.php">Seu perfil</a></li>
                <li><a href="amigos.php">Amigos</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </div>

    <div class="conteudo">
        <h2>Feed</h2>
        <?php if (!empty($postagens)): ?>
            <?php foreach ($postagens as $postagem): ?>
                <div class="postagem">
                    <div class="post-header">
                        <img src="<?php echo $postagem['foto_perfil'] ?: 'images/usuario_default.jpg'; ?>" alt="Foto do usuário" class="user-photo">
                        <h3><?php echo htmlspecialchars($postagem['nome_usuario']); ?></h3>
                        <p><?php echo date('d/m/Y H:i', strtotime($postagem['data_postagem'])); ?></p>
                    </div>
                    <div class="post-content">
                        <?php if ($postagem['texto']): ?>
                            <h1><?php echo nl2br(htmlspecialchars($postagem['texto'])); ?></h1>
                        <?php endif; ?>
                        <?php if ($postagem['imagem']): ?>
                            <img src="<?php echo htmlspecialchars($postagem['imagem']); ?>" alt="Imagem do post" class="imagempost">
                        <?php endif; ?>
                        <?php if ($postagem['video']): ?>
                            <video controls class="videopost">
                                <source src="<?php echo htmlspecialchars($postagem['video']); ?>" type="video/mp4">
                            </video>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Sem postagens para exibir.</p>
        <?php endif; ?>
    </div>

    <script src="script.js"></script>
</body>
</html>
