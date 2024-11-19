<?php
session_start();

// Destruir a sessão para realizar o logout
session_destroy();

// Redirecionar para a página de login
header('Location: index.html');
exit();
?>