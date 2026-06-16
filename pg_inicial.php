<?php
session_start();

require_once 'controllers/con_pg_inicial.php';

if (!$usuario) {
    header('Location: login.php');
    exit;
}

$titulo_pagina = 'Início';
?>
<?php require_once 'includes/header.php'; ?>

<main>

    <!-- Banner de destaque com saudação -->
    <section class="banner-destaque">
        <div class="conteudo-central">
            <h1>Olá, <span><?= htmlspecialchars($usuario['nome']) ?></span>!</h1>
            <p>Escolha um quiz abaixo e teste seu conhecimento sobre futebol.</p>
        </div>
    </section>

    <!-- Cartões dos dois quizzes -->
    <section class="area-pagina">
        <div class="conteudo-central">

            <div class="grade-quiz">

                <!-- Cartão: Brasileirão -->
                <div class="cartao-quiz">
                    <img src="assets/img/brasileirao.png" alt="Brasileirão" style="width:80px; height:80px; object-fit:contain;">
                    <h2>Quiz Brasileirão</h2>
                    <span class="etiqueta-dourada">10 perguntas</span>
                    <p>Clubes, títulos, artilheiros e recordes do maior campeonato do futebol brasileiro.</p>
                    <a href="quiz.php?tipo=brasileirao" class="botao botao-primario">Jogar agora</a>
                </div>

                <!-- Cartão: Copa do Mundo -->
                <div class="cartao-quiz">
                    <img src="assets/img/copa.png" alt="Copa do Mundo" style="width:80px; height:80px; object-fit:contain;">
                    <h2>Quiz Copa do Mundo</h2>
                    <span class="etiqueta-dourada">10 perguntas</span>
                    <p>Seleções campeãs, finais históricas, jogadores lendários e a participação do Brasil.</p>
                    <a href="quiz.php?tipo=copadomundo" class="botao botao-primario">Jogar agora</a>
                </div>

            </div>

        </div>
    </section>

</main>

<?php require_once 'includes/footer.php'; ?>