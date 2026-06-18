<?php
// ================================================
// con_registro.php — Lógica do cadastro
// ================================================

require_once dirname(__DIR__) . '/includes/funcoes.php';

// Busca usuário pelo nome no JSON
function buscar_usuario_por_nome(string $nome): ?array {
    foreach (ler_json('usuarios.json') as $u) {
        if (strtolower($u['nome']) === strtolower($nome)) return $u;
    }
    return null;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome            = trim($_POST['nome']            ?? '');
    $email           = trim($_POST['email']           ?? '');
    $senha           = $_POST['senha']                ?? '';
    $confirmar_senha = $_POST['confirmar_senha']      ?? '';

    if (empty($nome) || empty($email) || empty($senha) || empty($confirmar_senha)) {
        $erro = 'Por favor, preencha todos os campos.';
    } elseif (strlen($nome) < 3) {
        $erro = 'O nome deve ter pelo menos 3 caracteres.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Informe um e-mail válido.';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres.';
    } elseif ($senha !== $confirmar_senha) {
        $erro = 'As senhas não coincidem.';
    } elseif (buscar_usuario_por_email($email)) {
        $erro = 'Este e-mail já está cadastrado.';
    } elseif (buscar_usuario_por_nome($nome)) {
        $erro = 'Este nome de usuário já está em uso.';
    } else {
        $usuarios   = ler_json('usuarios.json');
        $usuarios[] = [
            'id'        => gerar_id(),
            'nome'      => $nome,
            'email'     => $email,
            'senha'     => password_hash($senha, PASSWORD_DEFAULT),
            'foto'      => '',
            'criado_em' => date('Y-m-d H:i:s'),
        ];

        if (salvar_json('usuarios.json', $usuarios)) {
            header('Location: login.php?cadastro=ok');
            exit;
        } else {
            $erro = 'Erro ao salvar os dados. Tente novamente.';
        }
    }
}