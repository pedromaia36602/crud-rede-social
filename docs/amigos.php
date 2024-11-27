<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$db_path = __DIR__ . '/rede_social.db';

if (!file_exists($db_path)) {
    die("Erro: O arquivo do banco de dados não foi encontrado.");
}

try {
    $db = new SQLite3($db_path);
} catch (Exception $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Dados do usuário logado
$stmt = $db->prepare("SELECT nome, foto_perfil, foto_fundo FROM usuarios WHERE id = :usuario_id");
$stmt->bindValue(':usuario_id', $_SESSION['usuario_id'], SQLITE3_INTEGER);
$resultado = $stmt->execute();
$usuario = $resultado->fetchArray(SQLITE3_ASSOC);

if (!$usuario) {
    echo "Usuário não encontrado!";
    exit;
}

$nome = $usuario['nome'];
$foto_perfil = $usuario['foto_perfil'] ? $usuario['foto_perfil'] : 'images/usuario_default.jpg';
$foto_fundo = $usuario['foto_fundo'] ? $usuario['foto_fundo'] : 'images/fundo_default.jpg';

// Recupera amigos do usuário
$stmtAmigos = $db->prepare("
    SELECT u.id, u.nome, u.foto_perfil 
    FROM amizades a
    JOIN usuarios u ON (u.id = a.amigo_id OR u.id = a.usuario_id)
    WHERE (a.usuario_id = :usuario_id OR a.amigo_id = :usuario_id)
      AND a.status = 'aceito'
      AND u.id != :usuario_id
");
$stmtAmigos->bindValue(':usuario_id', $_SESSION['usuario_id'], SQLITE3_INTEGER);
$resultadoAmigos = $stmtAmigos->execute();

$amigos = [];
while ($amigo = $resultadoAmigos->fetchArray(SQLITE3_ASSOC)) {
    $amigos[] = $amigo;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amigos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="body-amigos">
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

    <div class="amigos-lista">
    <h2>Solicitações de Amizade</h2>
    <ul id="solicitacoesLista"></ul> <!-- Lista de solicitações pendentes -->

    <h2>Seus Amigos</h2>
    <ul id="amigosLista">
        <?php if (count($amigos) > 0): ?>
            <?php foreach ($amigos as $amigo): ?>
                <li>
                    <div class="amigo">
                        <a href="perfilusuario.php?id=<?php echo $amigo['id']; ?>" style="text-decoration: none; color: inherit;">
                            <img src="<?php echo $amigo['foto_perfil'] ?: 'images/usuario_default.jpg'; ?>" alt="Foto do amigo" class="user-photo">
                            <h2><?php echo htmlspecialchars($amigo['nome']); ?></h2>
                        </a>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>Você ainda não tem amigos. Que tal enviar uma solicitação?</li>
        <?php endif; ?>
    </ul>
</div>


    <div class="pesquisa-usuarios">
    <h2>Pesquisar usuários</h2>
    <input type="text" id="nomePesquisa" placeholder="Digite um nome para pesquisar">
    <button class="botao" onclick="pesquisarUsuarios()">Pesquisar</button>
    <ul id="resultados"></ul>
</div>
<ul id="solicitacoesLista"></ul>

<script>
    const usuarioId = <?php echo json_encode($_SESSION['usuario_id']); ?>;
</script>

<script src="pesquisarUsuarios.js"></script>
<script src="script.js"></script>
<script src="responderSolicitacao.js"></script>
<script src="listarSolicitacoes.js"></script>
<script src="listarAmigos.js"></script>
<script src="enviarSolicitacao.js"></script>
<script src="getUsuarioLogadoId.js"></script>



</body>
</html>
