<?php
// ================================================
// funcoes.php — Funções utilitárias do projeto
// Compartilhadas por todos os controllers
// Localização: includes/funcoes.php
// ================================================

// --- Caminho base para os arquivos JSON ---
define('CAMINHO_DADOS', dirname(__DIR__) . '/controllers/data/');

// ------------------------------------------------
// Lê um arquivo JSON e retorna como array PHP
// Se o arquivo não existir, retorna array vazio
// ------------------------------------------------
function ler_json(string $arquivo): array {
    $caminho = CAMINHO_DADOS . $arquivo;
    if (!file_exists($caminho)) return [];
    $dados = json_decode(file_get_contents($caminho), true);
    return is_array($dados) ? $dados : [];
}

// ------------------------------------------------
// Salva um array PHP como JSON no arquivo indicado
// Substitui o INSERT/UPDATE do banco de dados
// ------------------------------------------------
function salvar_json(string $arquivo, array $dados): bool {
    $caminho = CAMINHO_DADOS . $arquivo;
    return file_put_contents(
        $caminho,
        json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    ) !== false;
}

// ------------------------------------------------
// Sanitiza string para exibição segura no HTML
// Evita ataques XSS (injeção de código HTML/JS)
// ------------------------------------------------
function limpar(string $texto): string {
    return htmlspecialchars(trim($texto), ENT_QUOTES, 'UTF-8');
}

// ------------------------------------------------
// Gera um ID único baseado em timestamp
// Usado para identificar usuários e resultados
// ------------------------------------------------
function gerar_id(): string {
    return uniqid('u_', true);
}

// ------------------------------------------------
// Verifica se o usuário está logado
// Se não estiver, redireciona para o login
// Usada no topo de todas as páginas protegidas
// ------------------------------------------------
function exigir_login(): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['usuario'])) {
        header('Location: login.php?aviso=precisa_login');
        exit;
    }
}

// ------------------------------------------------
// Retorna os dados do usuário logado ou null
// Valida se a sessão contém um array válido
// ------------------------------------------------
function usuario_logado(): ?array {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $usuario = $_SESSION['usuario'] ?? null;
    if (!is_array($usuario)) {
        unset($_SESSION['usuario']);
        return null;
    }
    return $usuario;
}

// ------------------------------------------------
// Inicia a sessão do usuário após login bem-sucedido
// A senha NUNCA é salva na sessão por segurança
// ------------------------------------------------
function iniciar_sessao_usuario(array $usuario): void {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['usuario'] = [
        'id'    => $usuario['id'],
        'nome'  => $usuario['nome'],
        'email' => $usuario['email'],
        'foto'  => $usuario['foto'] ?? '',
    ];
}

// ------------------------------------------------
// Busca um usuário pelo e-mail no usuarios.json
// Retorna o usuário encontrado ou null
// Usada no login e no cadastro
// ------------------------------------------------
function buscar_usuario_por_email(string $email): ?array {
    foreach (ler_json('usuarios.json') as $u) {
        if (strtolower($u['email']) === strtolower($email)) return $u;
    }
    return null;
}