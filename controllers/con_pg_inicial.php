<?php
// ================================================
// con_pg_inicial.php — Lógica da página inicial
// Verifica se o usuário está logado e prepara
// os dados para exibir na view
// ================================================

require_once __DIR__ . '/con_login.php';

// Inicia sessão se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pega os dados do usuário logado (ou null se não estiver logado)
$usuario = usuario_logado();