<?php
// ================================================
// con_login.php — Lógica do login
// Contém também as funções base de sessão e JSON
// pois todos os controllers dependem delas
// ================================================

// --- Caminho base para os arquivos JSON ---
define('CAMINHO_DADOS', __DIR__ . '/data/');

// Lê um arquivo JSON e retorna como array PHP
function ler_json(string $arquivo): array {
    $caminho  = CAMINHO_DADOS . $arquivo;
    if (!file_exists($caminho)) return [];
    $dados = json_decode(file_get_contents($caminho), true);
    return is_array($dados) ? $dados : [];
}

// Salva um array PHP como JSON no arquivo indicado
function salvar_json(string $arquivo, array $dados): bool {
    $caminho = CAMINHO_DADOS . $arquivo;
    return file_put_contents($caminho, json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
}

// Sanitiza string para exibição segura no HTML
function limpar(string $texto): string {
    return htmlspecialchars(trim($texto), ENT_QUOTES, 'UTF-8');
}

// Gera um ID único baseado em timestamp
function gerar_id(): string {
    return uniqid('u_', true);
}

// Verifica se o usuário está logado, redireciona se não estiver
function exigir_login(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['usuario'])) {
        header('Location: login.php?aviso=precisa_login');
        exit;
    }
}

// Retorna os dados do usuário logado ou null
function usuario_logado(): ?array {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $usuario = $_SESSION['usuario'] ?? null;
    if (!is_array($usuario)) {
        unset($_SESSION['usuario']);
        return null;
    }
    return $usuario;
}

// Inicia a sessão do usuário após login bem-sucedido
function iniciar_sessao_usuario(array $usuario): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['usuario'] = [
        'id'    => $usuario['id'],
        'nome'  => $usuario['nome'],
        'email' => $usuario['email'],
        'foto'  => $usuario['foto'] ?? '',
    ];
}

// Busca usuário pelo e-mail no JSON
function buscar_usuario_por_email(string $email): ?array {
    foreach (ler_json('usuarios.json') as $u) {
        if (strtolower($u['email']) === strtolower($email)) return $u;
    }
    return null;
}

// --- Lógica do login ---
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