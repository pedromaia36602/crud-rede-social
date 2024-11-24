<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    // Redireciona para a página de login caso não esteja logado
    header('Location: login.php');
    exit;
}

$db = new SQLite3('rede_social.db');

// Recupera as informações do usuário logado
$stmt = $db->prepare("SELECT * FROM usuarios WHERE id = :usuario_id");
$stmt->bindValue(':usuario_id', $_SESSION['usuario_id'], SQLITE3_INTEGER);
$resultado = $stmt->execute();
$usuario = $resultado->fetchArray(SQLITE3_ASSOC);

if (!$usuario) {
    echo "Usuário não encontrado!";
    exit;
}

// Dados do usuário
$nome = $usuario['nome'];
$data_nascimento = $usuario['data_nascimento'];
$curso = $usuario['curso'];
$foto_perfil = $usuario['foto_perfil'] ? $usuario['foto_perfil'] : 'images/usuario_default.jpg';
$foto_fundo = $usuario['foto_fundo'] ? $usuario['foto_fundo'] : 'images/fundo_default.jpg';


// Calcula a idade do usuário
$dataNascimento = new DateTime($data_nascimento);
$hoje = new DateTime();
$idade = $hoje->diff($dataNascimento)->y;

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="body-perfilusuario" style="background-image: url('<?php echo $foto_fundo; ?>');">

    <!-- Cabeçalho com a foto e informações do usuário -->








    <div class="menu">
	<h1>UNIMETROFÓRUM</h1> <!-- Logotipo como o primeiro item -->
        <div class="hamburger" id="hamburger">
            &#9776;
        </div>
    <nav class="nav" id="nav">
        <ul>
            <li><a href="index2.html">Feed</a></li>
            <li><a href="perfilusuario.html">Seu perfil</a></li>
            <li><a href="amigos.html">Amigos</a></li>
            <li><a href="index.html">Sair</a></li>
        </ul>
    </nav>
</div>
    
    <!-- Cabeçalho abaixo do nav -->
    <div class="cabecalho">
      <div class="foto">
      <img src="<?php echo $foto_perfil; ?>" alt="Foto do perfil">
      </div>
      <div class="informacoes">
      <h1><?php echo $nome; ?></h1>
        <h3><?php echo $idade; ?> anos</h3>
        <h3><?php echo $curso; ?></h3>
      </div>
      <!-- Formulário para alterar foto de perfil -->
    <form id="formFotoPerfil" enctype="multipart/form-data">
        <div>
        <fieldset>
            <legend><h2>Alterar foto de perfil:</h2></legend>
            <input type="file" name="fotoPerfil" accept="image/*" required>
            <button class= botao type="submit">Alterar Foto de Perfil</button>
        </fieldset>
    </form>

    <!-- Formulário para alterar foto de fundo -->
    <form id="formFotoFundo" enctype="multipart/form-data">
        <fieldset>
            <legend><h2>Alterar foto de fundo:</h2></legend>
            <input type="file" name="fotoFundo" accept="image/*" required>
            <button class= botao type="submit">Alterar Foto de Fundo</button>
        </fieldset>
    </form>
</div>

    <!-- Formulário para postar conteúdo -->
    <form id="formPostagem" enctype="multipart/form-data">
        <fieldset>
            <legend><h2>Fazer uma nova postagem:</h2></legend>
            <textarea name="postagemTexto" rows="4" placeholder="Escreva algo..."></textarea>
            <p>postar imagem:</p>
            <input type="file" name="imagemPost" accept="image/*">
            <p>postar vídeo:</p>
            <input type="file" name="video" accept="video/mp4">
            <button class= botao type="submit">Postar</button>
        </fieldset>
    </form>
    </div>










    

    <!-- Exibição das postagens -->
    <div id="postagensContainer">
        <!-- As postagens serão carregadas aqui dinamicamente -->
    </div>

    <!-- Scripts -->
    <script src="script.js"></script>
    <script src="mudar_foto.js"></script>
    <script src="postagens.js"></script>
    <script src="carregar_postagem.js"></script>
</body>
</html>
