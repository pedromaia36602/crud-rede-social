<?php
try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/rede_social.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}
?>
