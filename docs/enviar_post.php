<?php
session_start();

echo '<pre>';
print_r($_POST);
print_r($_FILES);
echo '</pre>';
exit;

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não logado']);
    exit;
}

$db = new SQLite3('rede_social.db');

// Função para fazer o upload da imagem ou vídeo
function uploadArquivo($arquivo) {
    if ($arquivo['error'] != 0) {
        return ['success' => false, 'message' => 'Erro no envio do arquivo'];
    }

    $uploadDir = 'uploads/';
    $fileName = uniqid() . '_' . basename($arquivo['name']);
    $fileTmpName = $arquivo['tmp_name'];

    // Tenta mover o arquivo para o diretório de uploads
    if (move_uploaded_file($fileTmpName, $uploadDir . $fileName)) {
        return ['success' => true, 'filePath' => $uploadDir . $fileName];
    }

    return ['success' => false, 'message' => 'Erro ao fazer upload do arquivo'];
}

if (isset($_FILES['imagemPost']) && isset($_POST['postagemTexto'])) {
    // Envia a imagem (ou outro tipo de arquivo, como vídeo)
    $resultadoImagemPost = uploadArquivo($_FILES['imagemPost']);
    
    $imagemPath = '';
    if ($resultadoImagemPost['success']) {
        $imagemPath = $resultadoImagemPost['filePath'];
    }
    
    // Inserir a postagem no banco de dados
    $stmt = $db->prepare("INSERT INTO postagens (usuario_id, texto, imagem) VALUES (:usuario_id, :texto, :imagem)");
    $stmt->bindValue(':usuario_id', $_SESSION['usuario_id'], SQLITE3_INTEGER);
    $stmt->bindValue(':texto', $_POST['postagemTexto'], SQLITE3_TEXT);
    $stmt->bindValue(':imagem', $imagemPath, SQLITE3_TEXT);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'imagemPost' => $imagemPath, 'textoPost' => $_POST['postagemTexto']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao salvar a postagem no banco de dados']);
    }
}
?>
