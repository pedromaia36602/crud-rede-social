<?php
// init_db.php
$db = new PDO('sqlite:database.db');

// Cria a tabela 'users' se não existir
$db->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL
    )
");

echo "Banco de dados e tabela criados com sucesso!";
?>