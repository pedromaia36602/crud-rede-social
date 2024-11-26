<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$db = new SQLite3('rede_social.db');

// Verifica se o ID de outro usuário foi passado pela URL
$perfil_id = isset($_GET['id']) ? (int)$_GET['id'] : $_SESSION['usuario_id'];

// Recupera as informações do perfil
$stmt = $db->prepare("SELECT * FROM usuarios WHERE id = :usuario_id");
$stmt->bindValue(':usuario_id', $perfil_id, SQLITE3_INTEGER);
$resultado = $stmt->execute();
$usuario = $resultado->fetchArray(SQLITE3_ASSOC);

if (!$usuario) {
    echo "Usuário não encontrado!";
    exit;
}

$nome = $usuario['nome'];
$data_nascimento = $usuario['data_nascimento'];
$curso = $usuario['curso'];
$foto_perfil = $usuario['foto_perfil'] ? $usuario['foto_perfil'] : 'images/usuario_default.jpg';
$foto_fundo = $usuario['foto_fundo'] ? $usuario['foto_fundo'] : 'images/fundo_default.jpg';

// Calcula a idade
$dataNascimento = new DateTime($data_nascimento);
$hoje = new DateTime();
$idade = $hoje->diff($dataNascimento)->y;

// Verifica se o perfil acessado é do usuário logado
$proprio_perfil = $perfil_id === $_SESSION['usuario_id'];

// Recupera as postagens do usuário
$stmtPosts = $db->prepare("
    SELECT p.id, p.texto, p.imagem, p.video, p.data_postagem
    FROM postagens p
    WHERE p.usuario_id = :usuario_id
    ORDER BY p.data_postagem DESC
");
$stmtPosts->bindValue(':usuario_id', $perfil_id, SQLITE3_INTEGER);
$resultadoPosts = $stmtPosts->execute();

$postagens = [];
while ($postagem = $resultadoPosts->fetchArray(SQLITE3_ASSOC)) {
    $postagens[] = $postagem;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de <?php echo $nome; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="body-perfilusuario" style="background-image: url('<?php echo $foto_fundo; ?>');">

<div class="menu">
	<h1>UNIMETROFÓRUM</h1>
    <div class="hamburger" id="hamburger">
        &#9776;
    </div>
    <nav class="nav" id="nav">
        <ul>
            <li><a href="index2.html">Feed</a></li>
            <li><a href="perfilusuario.php">Seu perfil</a></li>
            <li><a href="amigos.php">Amigos</a></li>
            <li><a href="logout.php">Sair</a></li>
        </ul>
    </nav>
</div>

<div class="cabecalho">
    <div class="foto">
        <img src="<?php echo $foto_perfil; ?>" alt="Foto do perfil">
    </div>
    <div class="informacoes">
        <h1><?php echo $nome; ?></h1>
        <h3><?php echo $idade; ?> anos</h3>
        <h3><?php echo $curso; ?></h3>
    </div>
</div>

<?php if ($proprio_perfil): ?>
    <!-- Formulários para alterar foto e postar conteúdo -->
    <div class="form-container">
    <form id="formFotoPerfil" enctype="multipart/form-data">
        <fieldset>
            <legend>Alterar foto de perfil:</legend>
            <input type="file" name="fotoPerfil" accept="image/*" required>
            <button class="botao" type="submit">Alterar</button>
        </fieldset>
    </form>

    <form id="formFotoFundo" enctype="multipart/form-data">
        <fieldset>
            <legend>Alterar foto de fundo:</legend>
            <input type="file" name="fotoFundo" accept="image/*" required>
            <button class="botao" type="submit">Alterar</button>
        </fieldset>
    </form>
</div>

    <form id="formPostagem" enctype="multipart/form-data">
        <fieldset>
            <legend>Fazer uma nova postagem:</legend>
            <textarea name="postagemTexto" rows="4" placeholder="Escreva algo..."></textarea>
            <input type="file" name="imagemPost" accept="image/*">
            <input type="file" name="video" accept="video/mp4">
            <button class="botao" type="submit">Postar</button>
        </fieldset>
    </form>
<?php endif; ?>

<div id="postagensContainer">
    <h2>Postagens de <?php echo $nome; ?></h2>
    <?php if (!empty($postagens)): ?>
        <?php foreach ($postagens as $postagem): ?>
            <div class="postagem">
                <p><?php echo nl2br(htmlspecialchars($postagem['texto'])); ?></p>
                <?php if ($postagem['imagem']): ?>
                    <img src="<?php echo $postagem['imagem']; ?>" alt="Imagem da postagem" class="imagempost">
                <?php endif; ?>
                <?php if ($postagem['video']): ?>
                    <video controls class="videopost">
                        <source src="<?php echo $postagem['video']; ?>" type="video/mp4">
                    </video>
                <?php endif; ?>
                <p><small>Postado em <?php echo date('d/m/Y H:i', strtotime($postagem['data_postagem'])); ?></small></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Este usuário ainda não fez postagens.</p>
    <?php endif; ?>
</div>

<script src="script.js"></script>
</body>
</html>
