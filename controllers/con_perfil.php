<?php
// ================================================
// con_perfil.php — Lógica da página de perfil
// Permite alterar nome, senha e foto de perfil
// ================================================

require_once __DIR__ . '/con_login.php';

// Exige que o usuário esteja logado
exigir_login();

$erro    = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $acao = $_POST['acao'] ?? '';

    // --- Alterar nome ---
    if ($acao === 'alterar_nome') {
        $novo_nome = trim($_POST['novo_nome'] ?? '');

        if (empty($novo_nome)) {
            $erro = 'O nome não pode ser vazio.';
        } elseif (strlen($novo_nome) < 3) {
            $erro = 'O nome deve ter pelo menos 3 caracteres.';
        } else {
            $existente = buscar_usuario_por_nome($novo_nome);
            if ($existente && $existente['id'] !== $_SESSION['usuario']['id']) {
                $erro = 'Este nome já está em uso.';
            } else {
                $usuarios = ler_json('usuarios.json');
                foreach ($usuarios as &$u) {
                    if ($u['id'] === $_SESSION['usuario']['id']) {
                        $u['nome'] = $novo_nome;
                        break;
                    }
                }
                unset($u);

                if (salvar_json('usuarios.json', $usuarios)) {
                    $_SESSION['usuario']['nome'] = $novo_nome;
                    $sucesso = 'Nome atualizado com sucesso!';
                } else {
                    $erro = 'Erro ao salvar. Tente novamente.';
                }
            }
        }
    }

    // --- Alterar senha ---
    elseif ($acao === 'alterar_senha') {
        $senha_atual   = $_POST['senha_atual']   ?? '';
        $nova_senha    = $_POST['nova_senha']     ?? '';
        $confirmar     = $_POST['confirmar_senha'] ?? '';

        $usuarios = ler_json('usuarios.json');
        $usuario_completo = null;

        foreach ($usuarios as $u) {
            if ($u['id'] === $_SESSION['usuario']['id']) {
                $usuario_completo = $u;
                break;
            }
        }

        if (!password_verify($senha_atual, $usuario_completo['senha'])) {
            $erro = 'Senha atual incorreta.';
        } elseif (strlen($nova_senha) < 6) {
            $erro = 'A nova senha deve ter pelo menos 6 caracteres.';
        } elseif ($nova_senha !== $confirmar) {
            $erro = 'As senhas não coincidem.';
        } else {
            foreach ($usuarios as &$u) {
                if ($u['id'] === $_SESSION['usuario']['id']) {
                    $u['senha'] = password_hash($nova_senha, PASSWORD_DEFAULT);
                    break;
                }
            }
            unset($u);

            if (salvar_json('usuarios.json', $usuarios)) {
                $sucesso = 'Senha alterada com sucesso!';
            } else {
                $erro = 'Erro ao salvar. Tente novamente.';
            }
        }
    }

    // --- Alterar foto de perfil ---
    elseif ($acao === 'alterar_foto') {

        if (empty($_FILES['foto']['name'])) {
            $erro = 'Selecione uma foto.';
        } else {
            $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'webp'];
            $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

            if (!in_array($extensao, $extensoes_permitidas)) {
                $erro = 'Formato inválido. Use JPG, PNG ou WEBP.';
            } elseif ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
                $erro = 'A foto deve ter no máximo 2MB.';
            } else {
                $nome_foto = gerar_id() . '.' . $extensao;
                $destino   = __DIR__ . '/../assets/img/' . $nome_foto;

                if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
                    // Remove foto antiga se existir
                    $foto_antiga = $_SESSION['usuario']['foto'] ?? '';
                    if ($foto_antiga && file_exists(__DIR__ . '/../assets/img/' . $foto_antiga)) {
                        unlink(__DIR__ . '/../assets/img/' . $foto_antiga);
                    }

                    // Atualiza no JSON
                    $usuarios = ler_json('usuarios.json');
                    foreach ($usuarios as &$u) {
                        if ($u['id'] === $_SESSION['usuario']['id']) {
                            $u['foto'] = $nome_foto;
                            break;
                        }
                    }
                    unset($u);

                    if (salvar_json('usuarios.json', $usuarios)) {
                        $_SESSION['usuario']['foto'] = $nome_foto;
                        $sucesso = 'Foto atualizada com sucesso!';
                    } else {
                        $erro = 'Erro ao salvar. Tente novamente.';
                    }
                } else {
                    $erro = 'Erro ao fazer upload da foto.';
                }
            }
        }
    }
}

// Busca os dados atualizados do usuário
$usuario = $_SESSION['usuario'];