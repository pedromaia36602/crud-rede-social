<?php
header('Content-Type: application/json');

// Função para validar o formato do e-mail
function validar_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Função para validar matrícula (número inteiro)
function validar_matricula($matricula) {
    return is_numeric($matricula);
}

// Verifica se os dados foram recebidos e são válidos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['novousuario'] ?? '');
    $email = trim($_POST['novoemail'] ?? '');
    $senha = $_POST['novasenha'] ?? '';
    $data_nascimento = $_POST['novadatanascimento'] ?? '';
    $matricula = $_POST['novamatricula'] ?? '';
    $curso = $_POST['novocurso'] ?? '';

    // Validar os dados
    if (empty($nome) || empty($email) || empty($senha) || empty($data_nascimento) || empty($matricula) || empty($curso)) {
        echo json_encode(['success' => false, 'message' => 'Todos os campos são obrigatórios!']);
        exit;
    }

    if (!validar_email($email)) {
        echo json_encode(['success' => false, 'message' => 'E-mail inválido!']);
        exit;
    }

    if (!validar_matricula($matricula)) {
        echo json_encode(['success' => false, 'message' => 'Matrícula inválida!']);
        exit;
    }

    // Criptografar a senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Conectar ao banco de dados SQLite
    $db = new SQLite3('rede_social.db');

    // Preparar a query para inserir os dados
    $query = $db->prepare("INSERT INTO usuarios (nome, email, senha, data_nascimento, matricula,curso) VALUES (:nome, :email, :senha, :data_nascimento, :matricula, :curso)");
    $query->bindValue(':nome', $nome);
    $query->bindValue(':email', $email);
    $query->bindValue(':senha', $senha_hash);
    $query->bindValue(':data_nascimento', $data_nascimento);
    $query->bindValue(':matricula', $matricula);
    $query->bindValue(':curso', $curso);

    // Executar a query
    if ($query->execute()) {
        echo json_encode(['success' => true, 'message' => 'Cadastro realizado com sucesso!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar o usuário.']);
    }

    $db->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Método de requisição inválido!']);
}
?>
