<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não logado']);
    exit;
}

$db = new SQLite3('rede_social.db');

// Função para fazer o upload de uma foto
function uploadFoto($foto, $tipo) {
    // Verifica se um arquivo foi enviado
    if ($foto['error'] != 0) {
        return ['success' => false, 'message' => 'Erro no envio do arquivo'];
    }

    // Diretório para armazenar a imagem
    $uploadDir = 'uploads/';
    $fileName = uniqid() . '_' . basename($foto['name']);
    $fileTmpName = $foto['tmp_name'];

    // Tenta mover o arquivo para o diretório de uploads
    if (move_uploaded_file($fileTmpName, $uploadDir . $fileName)) {
        return ['success' => true, 'filePath' => $uploadDir . $fileName];
    }

    return ['success' => false, 'message' => 'Erro ao fazer upload do arquivo'];
}

// Verifica qual foto ou postagem foi enviada
if (isset($_FILES['fotoPerfil'])) {
    // Envia foto de perfil
    $resultadoFotoPerfil = uploadFoto($_FILES['fotoPerfil'], 'perfil');
    if ($resultadoFotoPerfil['success']) {
        // Atualiza o caminho da foto de perfil no banco de dados
        $stmt = $db->prepare("UPDATE usuarios SET foto_perfil = :foto_perfil WHERE id = :usuario_id");
        $stmt->bindValue(':foto_perfil', $resultadoFotoPerfil['filePath'], SQLITE3_TEXT);
        $stmt->bindValue(':usuario_id', $_SESSION['usuario_id'], SQLITE3_INTEGER);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'novaFotoPerfil' => $resultadoFotoPerfil['filePath']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar foto de perfil no banco de dados']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => $resultadoFotoPerfil['message']]);
    }
} elseif (isset($_FILES['fotoFundo'])) {
    // Envia foto de fundo
    $resultadoFotoFundo = uploadFoto($_FILES['fotoFundo'], 'fundo');
    if ($resultadoFotoFundo['success']) {
        // Atualiza o caminho da foto de fundo no banco de dados
        $stmt = $db->prepare("UPDATE usuarios SET foto_fundo = :foto_fundo WHERE id = :usuario_id");
        $stmt->bindValue(':foto_fundo', $resultadoFotoFundo['filePath'], SQLITE3_TEXT);
        $stmt->bindValue(':usuario_id', $_SESSION['usuario_id'], SQLITE3_INTEGER);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'novaFotoFundo' => $resultadoFotoFundo['filePath']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao atualizar foto de fundo no banco de dados']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => $resultadoFotoFundo['message']]);
    }
} elseif (isset($_FILES['imagemPost']) && isset($_POST['postagemTexto'])) {
    // Envia uma postagem
    $resultadoImagemPost = uploadFoto($_FILES['imagemPost'], 'post');
    if ($resultadoImagemPost['success']) {
        $stmt = $db->prepare("INSERT INTO postagens (usuario_id, texto, imagem) VALUES (:usuario_id, :texto, :imagem)");
        $stmt->bindValue(':usuario_id', $_SESSION['usuario_id'], SQLITE3_INTEGER);
        $stmt->bindValue(':texto', $_POST['postagemTexto'], SQLITE3_TEXT);
        $stmt->bindValue(':imagem', $resultadoImagemPost['filePath'], SQLITE3_TEXT);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'imagemPost' => $resultadoImagemPost['filePath'], 'textoPost' => $_POST['postagemTexto']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao salvar a postagem no banco de dados']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => $resultadoImagemPost['message']]);
    }
}
