<?php
session_start();
require_once 'controllers/con_quiz.php';
$titulo_pagina = 'Quiz ' . $nome_quiz;
?>
<?php require_once 'includes/header.php'; ?>

<main>
    <div class="area-quiz">

        <?php if ($resultado !== null): ?>
        <!-- Tela de resultado final -->
        <div class="area-resultado">
            <div class="cartao">
                <h2 class="resultado-titulo">Quiz <?= htmlspecialchars($nome_quiz) ?> — Resultado</h2>

                <div class="placar-final">
                    <?= $resultado['acertos'] ?><span>/<?= $resultado['total'] ?></span>
                </div>

                <div class="mensagem-desempenho"><?= htmlspecialchars($resultado['mensagem']) ?></div>

                <div class="estatisticas-resultado">
                    <div class="item-estatistica">
                        <strong class="cor-verde"><?= $resultado['acertos'] ?></strong>
                        <span>Acertos</span>
                    </div>
                    <div class="item-estatistica">
                        <strong class="cor-vermelho"><?= $resultado['erros'] ?></strong>
                        <span>Erros</span>
                    </div>
                    <div class="item-estatistica">
                        <strong><?= $resultado['percentual'] ?>%</strong>
                        <span>Aproveitamento</span>
                    </div>
                </div>

                <div class="resultado-botoes">
                    <a href="quiz.php?tipo=<?= $tipo ?>" class="botao botao-primario">Jogar novamente</a>
                    <a href="pg_inicial.php"             class="botao botao-contorno">Início</a>
                    <a href="resultados.php"             class="botao botao-contorno">Ver resultados</a>
                </div>
            </div>
        </div>

        <?php elseif ($feedback !== null): ?>
        <!-- Tela de feedback após cada resposta -->

        <div class="quiz-informacoes">
            <span>Pergunta <?= $feedback['indice'] + 1 ?> de <?= $total ?></span>
            <span>Pontuação: <?= $acertos * 10 ?> pts</span>
        </div>
        <div class="barra-progresso">
            <div class="barra-progresso-preenchimento" style="width:<?= round(($feedback['indice'] / $total) * 100) ?>%"></div>
        </div>

        <div class="cartao">
            <div class="feedback-icone">
                <div class="feedback-emoji"><?= $feedback['acertou'] ? '✅' : '❌' ?></div>
                <div class="<?= $feedback['acertou'] ? 'feedback-acertou' : 'feedback-errou' ?>">
                    <?= $feedback['acertou'] ? 'Você acertou!' : 'Você errou!' ?>
                </div>
            </div>

            <div class="texto-pergunta" style="text-align:center;">
                <?= htmlspecialchars($feedback['pergunta']) ?>
            </div>

            <div class="lista-alternativas" style="pointer-events:none;">
                <?php foreach ($feedback['alternativas'] as $i => $alt):
                    if ($i === $feedback['resposta_correta'])                      $classe = 'alternativa-certa';
                    elseif ($i === $feedback['resposta_usuario'] && !$feedback['acertou']) $classe = 'alternativa-errada';
                    else                                                            $classe = '';
                ?>
                    <label class="<?= $classe ?>">
                        <input type="radio" <?= $i === $feedback['resposta_usuario'] ? 'checked' : '' ?> disabled>
                        <span><?= htmlspecialchars($alt) ?></span>
                        <?php if ($i === $feedback['resposta_correta']): ?>
                            <span class="label-certa">✓ Certa</span>
                        <?php elseif ($i === $feedback['resposta_usuario'] && !$feedback['acertou']): ?>
                            <span class="label-errada">✗ Sua resposta</span>
                        <?php endif; ?>
                    </label>
                <?php endforeach; ?>
            </div>

            <form method="POST">
                <input type="hidden" name="proxima" value="1">
                <button type="submit" class="botao botao-primario botao-largura-total">
                    <?= ($_SESSION['quiz_proximo'] ?? 0) >= $total ? 'Ver resultado final' : 'Próxima pergunta' ?>
                </button>
            </form>
        </div>

        <?php else: ?>
        <!-- Tela de pergunta -->

        <div class="quiz-informacoes">
            <span>Pergunta <?= $indice_atual + 1 ?> de <?= $total ?></span>
            <span>Pontuação: <?= $acertos * 10 ?> pts</span>
        </div>
        <div class="barra-progresso">
            <div class="barra-progresso-preenchimento" style="width:<?= $progresso ?>%"></div>
        </div>

        <div class="cartao">
            <div class="texto-pergunta"><?= htmlspecialchars($pergunta_atual['pergunta']) ?></div>

            <form method="POST">
                <div class="lista-alternativas">
                    <?php foreach ($pergunta_atual['alternativas'] as $i => $alt): ?>
                        <label>
                            <input type="radio" name="resposta" value="<?= $i ?>" required>
                            <span><?= htmlspecialchars($alt) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
                <button type="submit" class="botao botao-primario botao-largura-total">Responder</button>
            </form>
        </div>

        <?php endif; ?>

    </div>
</main>

<?php require_once 'includes/footer.php'; ?>