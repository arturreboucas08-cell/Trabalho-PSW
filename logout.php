<?php
// ================================================
// logout.php — Encerra a sessão do usuário
// ================================================

session_start();

// Apaga todos os dados da sessão
$_SESSION = [];

// Remove o cookie de sessão do navegador
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), '',
        time() - 3600,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// Remove o cookie de "lembrar usuário" se existir
if (isset($_COOKIE['quiz_email'])) {
    setcookie('quiz_email', '', time() - 3600, '/');
}

// Destroi a sessão no servidor
session_destroy();

// Volta para a página inicial
header('Location: pg_inicial.php');
exit;