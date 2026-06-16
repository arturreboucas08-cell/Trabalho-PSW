<?php
session_start();

if (!empty($_SESSION['usuario'])) {
    header('Location: pg_inicial.php');
    exit;
}

require_once 'controllers/con_registro.php';

$titulo_pagina = 'Cadastro';
?>
<?php require_once 'includes/header.php'; ?>

<main>
    <div class="area-autenticacao">
        <div class="cartao-autenticacao">

            <h1>Criar conta</h1>
            <p class="texto-subtitulo">Preencha os dados para se cadastrar</p>

            <?php if (!empty($erro)): ?>
                <div class="mensagem-alerta mensagem-erro"><?= htmlspecialchars($erro) ?></div>
            <?php endif; ?>

            <form method="POST">

                <div class="campo-formulario">
                    <label for="nome">Nome de usuário</label>
                    <input type="text" id="nome" name="nome" class="campo-entrada"
                        placeholder="Ex: joaosilva"
                        value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required>
                </div>

                <div class="campo-formulario">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" class="campo-entrada"
                        placeholder="seu@email.com"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>

                <div class="campo-formulario">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" class="campo-entrada"
                        placeholder="Mínimo 6 caracteres" required>
                </div>

                <div class="campo-formulario">
                    <label for="confirmar_senha">Confirmar senha</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha"
                        class="campo-entrada" placeholder="Repita a senha" required>
                </div>

                <button type="submit" class="botao botao-primario botao-largura-total">Cadastrar</button>

            </form>

            <span class="link-alternativo">
                Já tem conta? <a href="login.php">Faça login</a>
            </span>

        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>