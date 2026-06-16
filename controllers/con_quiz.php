<?php
// ================================================
// con_quiz.php — Lógica do quiz
// Lê as perguntas do perguntas.json (único arquivo
// dividido por categoria: brasileirao e copadomundo)
// ================================================

require_once __DIR__ . '/con_login.php';

// Exige que o usuário esteja logado
exigir_login();

// Pega o tipo do quiz vindo da URL
$tipo = $_GET['tipo'] ?? '';

// Valida o tipo
if (!in_array($tipo, ['brasileirao', 'copadomundo'])) {
    header('Location: pg_inicial.php');
    exit;
}

$nome_quiz = $tipo === 'brasileirao' ? 'Brasileirão' : 'Copa do Mundo';

// Carrega o JSON único e pega só a categoria correta
$todas = ler_json('perguntas.json');
$todas_perguntas = $todas[$tipo] ?? [];

if (empty($todas_perguntas)) {
    header('Location: pg_inicial.php');
    exit;
}

// Embaralha e salva 10 perguntas na sessão
if (empty($_SESSION['quiz_perguntas']) || ($_SESSION['quiz_tipo'] ?? '') !== $tipo) {
    shuffle($todas_perguntas);
    $_SESSION['quiz_perguntas'] = array_slice($todas_perguntas, 0, 10);
    $_SESSION['quiz_tipo']      = $tipo;
    $_SESSION['quiz_atual']     = 0;
    $_SESSION['quiz_acertos']   = 0;
}

$perguntas    = $_SESSION['quiz_perguntas'];
$total        = count($perguntas);
$indice_atual = $_SESSION['quiz_atual'];
$acertos      = $_SESSION['quiz_acertos'];
$resultado    = null;

// Guarda o feedback da última resposta (acertou ou errou)
$feedback = null;

// --- Processa a resposta enviada ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resposta'])) {

    $resposta_usuario = (int) $_POST['resposta'];
    $resposta_correta = (int) $perguntas[$indice_atual]['resposta'];
    $acertou          = $resposta_usuario === $resposta_correta;

    if ($acertou) {
        $_SESSION['quiz_acertos']++;
        $acertos = $_SESSION['quiz_acertos'];
    }

    // Monta o feedback para mostrar na tela
    $feedback = [
        'acertou'           => $acertou,
        'resposta_usuario'  => $resposta_usuario,
        'resposta_correta'  => $resposta_correta,
        'alternativas'      => $perguntas[$indice_atual]['alternativas'],
        'pergunta'          => $perguntas[$indice_atual]['pergunta'],
        'indice'            => $indice_atual,
    ];

    // Avança o índice só depois de mostrar o feedback
    $_SESSION['quiz_proximo'] = $indice_atual + 1;

} elseif (isset($_POST['proxima'])) {

    // Usuário clicou em "Próxima" após ver o feedback
    $_SESSION['quiz_atual'] = $_SESSION['quiz_proximo'] ?? ($indice_atual + 1);
    $indice_atual = $_SESSION['quiz_atual'];
    unset($_SESSION['quiz_proximo']);

    // Verifica se o quiz acabou
    if ($indice_atual >= $total) {

        $erros      = $total - $acertos;
        $percentual = round(($acertos / $total) * 100);

        if ($acertos <= 3) {
            $mensagem = 'Você pode melhorar! Continue treinando.';
        } elseif ($acertos <= 7) {
            $mensagem = 'Bom desempenho! Você conhece bastante de futebol.';
        } else {
            $mensagem = 'Excelente! Você é um verdadeiro craque do quiz!';
        }

        // Salva o resultado
        $resultados   = ler_json('resultado.json');
        $resultados[] = [
            'id'           => gerar_id(),
            'usuario_id'   => $_SESSION['usuario']['id'],
            'usuario_nome' => $_SESSION['usuario']['nome'],
            'tipo_quiz'    => $nome_quiz,
            'acertos'      => $acertos,
            'erros'        => $erros,
            'percentual'   => $percentual,
            'pontuacao'    => $acertos * 10,
            'data'         => date('d/m/Y'),
        ];
        salvar_json('resultado.json', $resultados);

        $resultado = compact('acertos', 'erros', 'percentual', 'mensagem', 'total');

        // Limpa a sessão do quiz
        unset($_SESSION['quiz_perguntas'], $_SESSION['quiz_tipo'],
              $_SESSION['quiz_atual'], $_SESSION['quiz_acertos'],
              $_SESSION['quiz_proximo']);
    }
}

$pergunta_atual = ($resultado === null && $feedback === null) ? $perguntas[$indice_atual] : null;
$progresso      = $resultado !== null ? 100 : round(($indice_atual / $total) * 100);