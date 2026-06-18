<?php
// ================================================
// con_login.php — Lógica do login
// ================================================

require_once dirname(__DIR__) . '/includes/funcoes.php';

$erro    = '';
$sucesso = '';

if (isset($_GET['cadastro']) && $_GET['cadastro'] === 'ok') {
    $sucesso = 'Cadastro realizado com sucesso! Faça login para continuar.';
}

if (isset($_GET['aviso']) && $_GET['aviso'] === 'precisa_login') {
    $erro = 'Você precisa estar logado para acessar essa página.';
}

$email_salvo = $_COOKIE['quiz_email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha']      ?? '';

    if (empty($email) || empty($senha)) {
        $erro = 'Preencha o e-mail e a senha.';
    } else {
        $usuario = buscar_usuario_por_email($email);

        if (!$usuario || !password_verify($senha, $usuario['senha'])) {
            $erro = 'E-mail ou senha incorretos.';
        } else {
            iniciar_sessao_usuario($usuario);

            if (!empty($_POST['lembrar'])) {
                setcookie('quiz_email', $email, time() + (30 * 24 * 3600), '/');
            } else {
                if (isset($_COOKIE['quiz_email'])) setcookie('quiz_email', '', time() - 3600, '/');
            }

            header('Location: pg_inicial.php');
            exit;
        }
    }
}