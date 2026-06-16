<?php
session_start();
require_once 'controllers/con_ranking.php';
$titulo_pagina = 'Ranking';
?>
<?php require_once 'includes/header.php'; ?>

<main>
    <div class="area-pagina">
        <div class="conteudo-central">

            <h1 class="titulo-pagina">Ranking Geral</h1>
            <p class="subtitulo-pagina">Os jogadores com maior pontuação acumulada</p>
            <div class="divisor-dourado"></div>

            <?php if (empty($ranking)): ?>

                <div class="cartao cartao-vazio">
                    <div class="cartao-vazio-icone">🏅</div>
                    <p>Nenhum quiz foi jogado ainda.<br>Seja o primeiro a entrar no ranking!</p>
                    <div class="cartao-vazio-botoes">
                        <a href="quiz.php?tipo=brasileirao"  class="botao botao-primario">Quiz Brasileirão</a>
                        <a href="quiz.php?tipo=copadomundo" class="botao botao-contorno">Quiz Copa do Mundo</a>
                    </div>
                </div>

            <?php else: ?>

                <!-- Pódio top 3 -->
                <?php if (count($ranking) >= 3):
                    $lugares = [
                        ['emoji' => '🥇', 'classe' => 'ouro',   'borda' => 'borda-ouro',   'idx' => 0],
                        ['emoji' => '🥈', 'classe' => 'prata',  'borda' => 'borda-prata',  'idx' => 1],
                        ['emoji' => '🥉', 'classe' => 'bronze', 'borda' => 'borda-bronze', 'idx' => 2],
                    ];
                ?>
                <div class="podio">
                    <?php foreach ($lugares as $l):
                        $j    = $ranking[$l['idx']];
                        $foto = !empty($j['foto']) ? 'assets/img/' . htmlspecialchars($j['foto']) : 'assets/img/avatar_padrao.svg';
                    ?>
                        <div class="cartao podio-cartao">
                            <div class="podio-emoji"><?= $l['emoji'] ?></div>
                            <img src="<?= $foto ?>" alt="Foto" class="podio-foto <?= $l['borda'] ?>">
                            <div class="podio-nome"><?= htmlspecialchars($j['usuario_nome']) ?></div>
                            <div class="podio-pontuacao <?= $l['classe'] ?>"><?= $j['pontuacao'] ?> pts</div>
                            <div class="podio-quizzes"><?= $j['total_quizzes'] ?> quiz(zes) jogado(s)</div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Tabela completa -->
                <table class="tabela-ranking">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th colspan="2">Jogador</th>
                            <th>Pontuação</th>
                            <th>Quizzes</th>
                            <th>Acertos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ranking as $pos => $j):
                            $foto = !empty($j['foto']) ? 'assets/img/' . htmlspecialchars($j['foto']) : 'assets/img/avatar_padrao.svg';
                            $meu  = $j['usuario_id'] === $id_usuario_logado;

                            if ($pos === 0)     $classe_pos = 'posicao-ranking primeiro-lugar';
                            elseif ($pos === 1) $classe_pos = 'posicao-ranking segundo-lugar';
                            elseif ($pos === 2) $classe_pos = 'posicao-ranking terceiro-lugar';
                            else                $classe_pos = 'posicao-ranking';

                            $medalha = $pos === 0 ? '🥇' : ($pos === 1 ? '🥈' : ($pos === 2 ? '🥉' : $pos + 1));
                        ?>
                            <tr class="<?= $meu ? 'linha-usuario-logado' : '' ?>">
                                <td><span class="<?= $classe_pos ?>"><?= $medalha ?></span></td>
                                <td><img src="<?= $foto ?>" alt="Foto" class="foto-tabela-ranking"></td>
                                <td class="nome-ranking">
                                    <?= htmlspecialchars($j['usuario_nome']) ?>
                                    <?php if ($meu): ?>
                                        <span class="etiqueta-voce">você</span>
                                    <?php endif; ?>
                                </td>
                                <td class="pontuacao-ranking"><?= $j['pontuacao'] ?> pts</td>
                                <td class="quizzes-ranking"><?= $j['total_quizzes'] ?></td>
                                <td class="acertos-ranking"><?= $j['total_acertos'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            <?php endif; ?>

        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>