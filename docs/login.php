<?php
// Definir o cabeçalho como JSON
header('Content-Type: application/json');

// Recebe dados via JSON
$dados = json_decode(file_get_contents('php://input'), true);

// Verifica se os dados foram recebidos corretamente
if (isset($dados['usuario']) && isset($dados['senha'])) {
    $usuario = $dados['usuario'];
    $senha = $dados['senha'];

    // Conectar ao banco de dados SQLite
    $db = new SQLite3('rede_social.db');
    
    // Prepara a consulta para buscar o usuário no banco de dados
    $stmt = $db->prepare('SELECT * FROM usuarios WHERE email = :usuario');
    $stmt->bindValue(':usuario', $usuario, SQLITE3_TEXT);
    $result = $stmt->execute();
    
    // Verifica se o usuário foi encontrado
    if ($row = $result->fetchArray()) {
        // Verifica se a senha corresponde
        if (password_verify($senha, $row['senha'])) {
            // Senha correta
            echo json_encode(["success" => true]);
        } else {
            // Senha incorreta
            echo json_encode(["success" => false, "message" => "Senha incorreta"]);
        }
    } else {
        // Usuário não encontrado
        echo json_encode(["success" => false, "message" => "Usuário não encontrado"]);
    }

    $db->close();
} else {
    echo json_encode(["success" => false, "message" => "Dados inválidos!"]);
}
?>
