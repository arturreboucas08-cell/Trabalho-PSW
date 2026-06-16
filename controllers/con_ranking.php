<?php
// ================================================
// con_ranking.php — Lógica da página de ranking
// Agrupa os resultados por usuário e ordena
// pela pontuação total acumulada
// ================================================

require_once __DIR__ . '/con_login.php';

// Exige que o usuário esteja logado
exigir_login();

// Carrega todos os resultados
$todos_resultados = ler_json('resultado.json');

// Carrega todos os usuários para buscar a foto
$todos_usuarios = ler_json('usuarios.json');

// Cria um mapa de usuários por ID para acesso rápido
$mapa_usuarios = [];
foreach ($todos_usuarios as $u) {
    $mapa_usuarios[$u['id']] = $u;
}

// Agrupa os resultados por usuário somando pontuação e contando quizzes
$ranking = [];
foreach ($todos_resultados as $r) {
    $id = $r['usuario_id'];

    if (!isset($ranking[$id])) {
        $ranking[$id] = [
            'usuario_id'    => $id,
            'usuario_nome'  => $r['usuario_nome'],
            'foto'          => $mapa_usuarios[$id]['foto'] ?? '',
            'pontuacao'     => 0,
            'total_quizzes' => 0,
            'total_acertos' => 0,
        ];
    }

    $ranking[$id]['pontuacao']     += $r['pontuacao'];
    $ranking[$id]['total_quizzes'] += 1;
    $ranking[$id]['total_acertos'] += $r['acertos'];
}

// Converte para array simples e ordena pela pontuação (maior primeiro)
$ranking = array_values($ranking);
usort($ranking, fn($a, $b) => $b['pontuacao'] - $a['pontuacao']);

// ID do usuário logado para destacar na tabela
$id_usuario_logado = $_SESSION['usuario']['id'];