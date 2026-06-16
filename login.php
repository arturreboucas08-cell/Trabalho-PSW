<?php
session_start();

if (!empty($_SESSION['usuario'])) {
    header('Location: pg_inicial.php');
    exit;
}

require_once 'controllers/con_login.php';

$titulo_pagina = 'Login';
?>
<?php require_once 'includes/header.php'; ?>

<main>
    <div class="area-autenticacao">
        <div class="cartao-autenticacao">

            <h1>Entrar</h1>
            <p class="texto-subtitulo">Acesse sua conta para jogar o quiz</p>

            <?php if (!empty($sucesso)): ?>
                <div class="mensagem-alerta mensagem-sucesso"><?= htmlspecialchars($sucesso) ?></div>
            <?php endif; ?>

            <?php if (!empty($erro)): ?>
                <div class="mensagem-alerta mensagem-erro"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <form method="POST">

                <div class="campo-formulario">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" class="campo-entrada"
                        placeholder="seu@email.com"
                        value="<?= htmlspecialchars($email_salvo) ?>" required>
                </div>

                <div class="campo-formulario">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" class="campo-entrada"
                        placeholder="Sua senha" required>
                </div>

                <div class="campo-formulario" style="display:flex; align-items:center; gap:8px;">
                    <input type="checkbox" id="lembrar" name="lembrar"
                        style="accent-color:var(--verde-medio); width:15px; height:15px;"
                        <?= $email_salvo ? 'checked' : '' ?>>
                    <label for="lembrar" style="font-weight:400; font-size:0.875rem; color:var(--cinza-suave); margin:0;">
                        Lembrar meu e-mail
                    </label>
                </div>

                <button type="submit" class="botao botao-primario botao-largura-total">Entrar</button>

            </form>

            <span class="link-alternativo">
                Não tem conta? <a href="registro.php">Cadastre-se</a>
            </span>

        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>