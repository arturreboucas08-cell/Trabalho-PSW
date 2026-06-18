<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$usuario_logado = $_SESSION['usuario'] ?? null;
$pagina_atual   = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titulo_pagina) ? htmlspecialchars($titulo_pagina) . ' — Quiz Futebol' : 'Quiz Futebol' ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header class="cabecalho-site">
    <div class="cabecalho-linha-interna">

        <!-- Logo colada à esquerda -->
        <a href="pg_inicial.php" class="cabecalho-logo">
            <span>Fala<em>Torcedor!</em></span>
        </a>

        <?php if ($usuario_logado): ?>
            <!-- Menu centralizado — sem link de perfil -->
            <nav class="cabecalho-menu">
                <a href="pg_inicial.php" class="<?= $pagina_atual === 'pg_inicial.php' ? 'pagina-ativa' : '' ?>">Início</a>
                <a href="ranking.php"    class="<?= $pagina_atual === 'ranking.php'    ? 'pagina-ativa' : '' ?>">Ranking</a>
                <a href="resultados.php" class="<?= $pagina_atual === 'resultados.php' ? 'pagina-ativa' : '' ?>">Resultados</a>
            </nav>

            <!-- Foto e nome clicáveis que levam ao perfil -->
            <div class="cabecalho-usuario">
                <?php
                    $foto = !empty($usuario_logado['foto'])
                        ? 'assets/img/' . htmlspecialchars($usuario_logado['foto'])
                        : 'assets/img/avatar_padrao.svg';
                ?>
                <a href="perfil.php" style="display:flex; align-items:center; gap:8px; text-decoration:none;">
                    <img src="<?= $foto ?>" alt="Foto de perfil">
                    <span class="cabecalho-usuario-nome"><?= htmlspecialchars($usuario_logado['nome']) ?></span>
                </a>
            </div>

        <?php else: ?>
            <!-- Não logado: botões de acesso no canto direito -->
            <div class="cabecalho-usuario">
                <a href="login.php"    class="botao-cabecalho botao-cabecalho-contorno">Entrar</a>
                <a href="registro.php" class="botao-cabecalho botao-cabecalho-dourado">Cadastrar</a>
            </div>
        <?php endif; ?>

    </div>
</header>