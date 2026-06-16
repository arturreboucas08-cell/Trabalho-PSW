<?php
// ================================================
// con_resultados.php — Lógica da página de resultados
// Busca o histórico de quizzes do usuário logado
// ================================================

require_once __DIR__ . '/con_login.php';

// Exige que o usuário esteja logado
exigir_login();

// Carrega todos os resultados do JSON
$todos_resultados = ler_json('resultado.json');

// Filtra apenas os resultados do usuário logado
$meus_resultados = array_filter($todos_resultados, function($r) {
    return $r['usuario_id'] === $_SESSION['usuario']['id'];
});

// Ordena do mais recente para o mais antigo
$meus_resultados = array_values(array_reverse($meus_resultados));

// Calcula totais para exibir no resumo
$total_jogos    = count($meus_resultados);
$total_acertos  = array_sum(array_column($meus_resultados, 'acertos'));
$total_pontos   = array_sum(array_column($meus_resultados, 'pontuacao'));
$media_percentual = $total_jogos > 0
    ? round(array_sum(array_column($meus_resultados, 'percentual')) / $total_jogos)
    : 0;