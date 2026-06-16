<?php
session_start();
require_once 'controllers/con_resultados.php';
$titulo_pagina = 'Resultados';
?>
<?php require_once 'includes/header.php'; ?>

<main>
    <div class="area-pagina">
        <div class="conteudo-central">

            <h1 class="titulo-pagina">Meus Resultados</h1>
            <p class="subtitulo-pagina">Histórico de todos os quizzes que você jogou</p>
            <div class="divisor-dourado"></div>

            <?php if ($total_jogos === 0): ?>

                <div class="cartao cartao-vazio">
                    <div class="cartao-vazio-icone">🏆</div>
                    <p>Você ainda não jogou nenhum quiz.<br>Que tal começar agora?</p>
                    <div class="cartao-vazio-botoes">
                        <a href="quiz.php?tipo=brasileirao"  class="botao botao-primario">Quiz Brasileirão</a>
                        <a href="quiz.php?tipo=copadomundo" class="botao botao-contorno">Quiz Copa do Mundo</a>
                    </div>
                </div>

            <?php else: ?>

                <!-- Resumo geral -->
                <div class="grade-resumo">
                    <div class="cartao cartao-resumo">
                        <strong><?= $total_jogos ?></strong>
                        <span>Quizzes jogados</span>
                    </div>
                    <div class="cartao cartao-resumo">
                        <strong class="destaque-verde"><?= $total_acertos ?></strong>
                        <span>Total de acertos</span>
                    </div>
                    <div class="cartao cartao-resumo">
                        <strong class="destaque-dourado"><?= $total_pontos ?></strong>
                        <span>Pontuação total</span>
                    </div>
                    <div class="cartao cartao-resumo">
                        <strong><?= $media_percentual ?>%</strong>
                        <span>Média geral</span>
                    </div>
                </div>

                <!-- Tabela de histórico -->
                <table class="tabela-ranking">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Quiz</th>
                            <th>Acertos</th>
                            <th>Erros</th>
                            <th>Aproveitamento</th>
                            <th>Pontuação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($meus_resultados as $r): ?>
                            <?php
                                $nivel = $r['percentual'] >= 80 ? 'alta' : ($r['percentual'] >= 50 ? 'media' : 'baixa');
                                $etiqueta = $r['tipo_quiz'] === 'Brasileirão' ? 'etiqueta-brasileirao' : 'etiqueta-copa';
                            ?>
                            <tr>
                                <td class="celula-data"><?= htmlspecialchars($r['data']) ?></td>
                                <td><span class="<?= $etiqueta ?>"><?= htmlspecialchars($r['tipo_quiz']) ?></span></td>
                                <td class="celula-acertos"><?= $r['acertos'] ?>/<?= $r['acertos'] + $r['erros'] ?></td>
                                <td class="celula-erros"><?= $r['erros'] ?></td>
                                <td>
                                    <div class="barra-aproveitamento">
                                        <div class="barra-aproveitamento-fundo">
                                            <div class="barra-aproveitamento-fill <?= $nivel ?>" style="width:<?= $r['percentual'] ?>%"></div>
                                        </div>
                                        <span><?= $r['percentual'] ?>%</span>
                                    </div>
                                </td>
                                <td class="celula-pontuacao"><?= $r['pontuacao'] ?> pts</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            <?php endif; ?>

        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>