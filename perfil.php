<?php
session_start();

require_once 'controllers/con_perfil.php';

$titulo_pagina = 'Perfil';
?>
<?php require_once 'includes/header.php'; ?>

<main>
    <div class="area-pagina">
        <div class="conteudo-central">
            <div class="area-perfil">

                <!-- Foto e nome do usuário -->
                <div class="perfil-cabecalho">
                    <?php
                        $foto = !empty($usuario['foto'])
                            ? 'assets/img/' . htmlspecialchars($usuario['foto'])
                            : 'assets/img/avatar_padrao.svg';
                    ?>
                    <img src="<?= $foto ?>" alt="Foto de perfil" class="foto-perfil-grande">
                    <div class="perfil-nome-usuario"><?= htmlspecialchars($usuario['nome']) ?></div>
                    <div class="perfil-email-usuario"><?= htmlspecialchars($usuario['email']) ?></div>
                    <a href="logout.php" class="botao botao-perigo botao-pequeno" style="margin-top:6px;">Sair da conta</a>
                </div>

                <?php if (!empty($sucesso)): ?>
                    <div class="mensagem-alerta mensagem-sucesso"><?= htmlspecialchars($sucesso) ?></div>
                <?php endif; ?>
                <?php if (!empty($erro)): ?>
                    <div class="mensagem-alerta mensagem-erro"><?= htmlspecialchars($erro) ?></div>
                <?php endif; ?>

                <!-- Alterar foto -->
                <div class="cartao" style="margin-bottom:16px;">
                    <div class="cartao-titulo">Foto de perfil</div>
                    <div class="cartao-subtitulo">Adicione ou troque sua foto</div>
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="acao" value="alterar_foto">
                        <div class="campo-formulario">
                            <label for="foto">Selecionar imagem</label>
                            <input type="file" id="foto" name="foto" class="campo-entrada"
                                accept="image/jpeg,image/png,image/webp">
                        </div>
                        <button type="submit" class="botao botao-primario">Salvar foto</button>
                    </form>
                </div>

                <!-- Alterar nome -->
                <div class="cartao" style="margin-bottom:16px;">
                    <div class="cartao-titulo">Alterar nome</div>
                    <div class="cartao-subtitulo">Seu nome atual: <strong><?= htmlspecialchars($usuario['nome']) ?></strong></div>
                    <form method="POST">
                        <input type="hidden" name="acao" value="alterar_nome">
                        <div class="campo-formulario">
                            <label for="novo_nome">Novo nome</label>
                            <input type="text" id="novo_nome" name="novo_nome" class="campo-entrada"
                                placeholder="Digite o novo nome" required>
                        </div>
                        <button type="submit" class="botao botao-primario">Salvar nome</button>
                    </form>
                </div>

                <!-- Alterar senha -->
                <div class="cartao">
                    <div class="cartao-titulo">Alterar senha</div>
                    <div class="cartao-subtitulo">Mínimo 6 caracteres</div>
                    <form method="POST">
                        <input type="hidden" name="acao" value="alterar_senha">
                        <div class="campo-formulario">
                            <label for="senha_atual">Senha atual</label>
                            <input type="password" id="senha_atual" name="senha_atual"
                                class="campo-entrada" placeholder="Senha atual" required>
                        </div>
                        <div class="campo-formulario">
                            <label for="nova_senha">Nova senha</label>
                            <input type="password" id="nova_senha" name="nova_senha"
                                class="campo-entrada" placeholder="Nova senha" required>
                        </div>
                        <div class="campo-formulario">
                            <label for="confirmar_senha">Confirmar nova senha</label>
                            <input type="password" id="confirmar_senha" name="confirmar_senha"
                                class="campo-entrada" placeholder="Repita a nova senha" required>
                        </div>
                        <button type="submit" class="botao botao-primario">Salvar senha</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>