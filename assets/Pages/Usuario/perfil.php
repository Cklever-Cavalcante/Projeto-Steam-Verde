<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Incluir arquivo de conexão
require_once '../../database/conexao.php';

$mensagem = '';
$tipo_mensagem = '';

// Buscar dados atualizados do usuário
try {
    $stmt = $pdo->prepare("SELECT id, nome, email, data_cadastro FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['usuario_id']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$usuario) {
        // Se o usuário não for encontrado, fazer logout
        session_destroy();
        header("Location: login.php");
        exit;
    }
} catch(PDOException $e) {
    $mensagem = "Erro ao buscar dados do usuário: " . $e->getMessage();
    $tipo_mensagem = "erro";
}

// Processar o formulário de atualização quando enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['atualizar'])) {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha_atual = trim($_POST['senha_atual']);
    $nova_senha = trim($_POST['nova_senha']);
    $confirmar_senha = trim($_POST['confirmar_senha']);
    
    // Validações básicas
    if (empty($nome) || empty($email)) {
        $mensagem = "Nome e email são obrigatórios.";
        $tipo_mensagem = "erro";
    } else {
        try {
            // Verificar se o email já existe para outro usuário
            if ($email !== $usuario['email']) {
                $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
                $stmt->execute([$email, $_SESSION['usuario_id']]);
                
                if ($stmt->rowCount() > 0) {
                    $mensagem = "Este email já está sendo usado por outro usuário.";
                    $tipo_mensagem = "erro";
                    goto skip_update; // Pular a atualização
                }
            }
            
            // Se a senha atual foi fornecida, verificar e atualizar a senha
            if (!empty($senha_atual)) {
                // Verificar a senha atual
                $stmt = $pdo->prepare("SELECT senha FROM usuarios WHERE id = ?");
                $stmt->execute([$_SESSION['usuario_id']]);
                $hash_senha = $stmt->fetchColumn();
                
                if (!password_verify($senha_atual, $hash_senha)) {
                    $mensagem = "Senha atual incorreta.";
                    $tipo_mensagem = "erro";
                    goto skip_update; // Pular a atualização
                }
                
                // Verificar se a nova senha foi fornecida e se coincide com a confirmação
                if (empty($nova_senha)) {
                    $mensagem = "Nova senha não pode estar vazia.";
                    $tipo_mensagem = "erro";
                    goto skip_update; // Pular a atualização
                } elseif ($nova_senha !== $confirmar_senha) {
                    $mensagem = "Nova senha e confirmação não coincidem.";
                    $tipo_mensagem = "erro";
                    goto skip_update; // Pular a atualização
                } elseif (strlen($nova_senha) < 6) {
                    $mensagem = "A nova senha deve ter pelo menos 6 caracteres.";
                    $tipo_mensagem = "erro";
                    goto skip_update; // Pular a atualização
                }
                
                // Atualizar nome, email e senha
                $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id = ?");
                $stmt->execute([$nome, $email, $senha_hash, $_SESSION['usuario_id']]);
            } else {
                // Atualizar apenas nome e email
                $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ? WHERE id = ?");
                $stmt->execute([$nome, $email, $_SESSION['usuario_id']]);
            }
            
            // Atualizar dados da sessão
            $_SESSION['usuario_nome'] = $nome;
            $_SESSION['usuario_email'] = $email;
            
            $mensagem = "Perfil atualizado com sucesso!";
            $tipo_mensagem = "sucesso";
            
            // Atualizar os dados do usuário na página
            $usuario['nome'] = $nome;
            $usuario['email'] = $email;
            
        } catch(PDOException $e) {
            $mensagem = "Erro ao atualizar perfil: " . $e->getMessage();
            $tipo_mensagem = "erro";
        }
    }
    
    skip_update: // Label para pular a atualização em caso de erro
}

// Processar a exclusão da conta
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['excluir'])) {
    $senha = trim($_POST['senha_exclusao']);
    
    if (empty($senha)) {
        $mensagem = "Digite sua senha para confirmar a exclusão.";
        $tipo_mensagem = "erro";
    } else {
        try {
            // Verificar a senha
            $stmt = $pdo->prepare("SELECT senha FROM usuarios WHERE id = ?");
            $stmt->execute([$_SESSION['usuario_id']]);
            $hash_senha = $stmt->fetchColumn();
            
            if (password_verify($senha, $hash_senha)) {
                // Excluir a conta
                $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
                $stmt->execute([$_SESSION['usuario_id']]);
                
                // Destruir a sessão
                session_destroy();
                
                // Redirecionar para a página inicial com mensagem
                header("Location: login.php?mensagem=conta_excluida");
                exit;
            } else {
                $mensagem = "Senha incorreta. Não foi possível excluir a conta.";
                $tipo_mensagem = "erro";
            }
        } catch(PDOException $e) {
            $mensagem = "Erro ao excluir conta: " . $e->getMessage();
            $tipo_mensagem = "erro";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - Steam Verde</title>
    <link rel="stylesheet" href="../../CSS/style.css">
    <link rel="stylesheet" href="../../CSS/formlario.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Akaya+Kanadaka&family=Jersey+20&family=Sancreek&display=swap" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <style>
        .mensagem {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .sucesso {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .erro {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .container {
            max-width: 800px;
        }
        .tabs {
            display: flex;
            margin-bottom: 20px;
        }
        .tab {
            padding: 10px 20px;
            background-color: #f1f1f1;
            border: 1px solid #ccc;
            border-bottom: none;
            cursor: pointer;
            border-radius: 5px 5px 0 0;
            margin-right: 5px;
        }
        .tab.active {
            background-color: #4CAF50;
            color: white;
        }
        .tab-content {
            display: none;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 0 5px 5px 5px;
            background-color: #fff;
        }
        .tab-content.active {
            display: block;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            display: block;
            width: 100%;
            margin-top: 10px;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            display: block;
            width: 100%;
            margin-top: 10px;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .actions a {
            flex: 1;
            margin: 0 5px;
            text-align: center;
            text-decoration: none;
            color: white;
            padding: 10px;
            border-radius: 4px;
        }
        .actions .btn-carrinho {
            background-color: #4CAF50;
        }
        .actions .btn-admin {
            background-color: #007bff;
        }
        .actions .btn-logout {
            background-color: #6c757d;
        }
    </style>
</head>
<body>
    <header>
        <div style="display: flex; align-items: center; justify-content: center; padding-right: 20px;">
            <a href="../../../index.php"><img src="../../imagens/icone-steam-vert.png" alt="Steam Verde"></a>
        </div>
        <nav>
            <ul>
                <a href="../../../index.php"><li>Inicio</li></a>
                <a href="../../Pages/Loja/loja.php"><li>Loja</li></a>
                <a href="../../Pages/Comunidade/comunidade.html"><li>Novidades</li></a>
                <a href="../../Pages/Usuario/carrinho.php"><li>Carrinho</li></a>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Meu Perfil</h2>
        
        <?php if (!empty($mensagem)): ?>
            <div class="mensagem <?php echo $tipo_mensagem; ?>">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        
        <div class="tabs">
            <div class="tab active" onclick="openTab(event, 'perfil')">Meus Dados</div>
            <div class="tab" onclick="openTab(event, 'editar')">Editar Perfil</div>
            <div class="tab" onclick="openTab(event, 'excluir')">Excluir Conta</div>
        </div>
        
        <div id="perfil" class="tab-content active">
            <h3>Dados do Usuário</h3>
            <p><strong>Nome:</strong> <?php echo htmlspecialchars($usuario['nome']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></p>
            <p><strong>Data de Cadastro:</strong> <?php echo date('d/m/Y H:i', strtotime($usuario['data_cadastro'])); ?></p>
            
            <div class="actions">
                <a href="carrinho.php" class="btn-carrinho">Meu Carrinho</a>
                <?php if (isset($_SESSION['usuario_papel']) && $_SESSION['usuario_papel'] === 'admin'): ?>
                <a href="../Admin/admin_usuarios.php" class="btn-admin">Área Administrativa</a>
                <?php endif; ?>
                <a href="logout.php" class="btn-logout">Sair</a>
            </div>
        </div>
        
        <div id="editar" class="tab-content">
            <h3>Editar Perfil</h3>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['nome']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                </div>
                
                <h4>Alterar Senha (opcional)</h4>
                
                <div class="form-group">
                    <label for="senha_atual">Senha Atual:</label>
                    <input type="password" id="senha_atual" name="senha_atual">
                </div>
                
                <div class="form-group">
                    <label for="nova_senha">Nova Senha:</label>
                    <input type="password" id="nova_senha" name="nova_senha">
                </div>
                
                <div class="form-group">
                    <label for="confirmar_senha">Confirmar Nova Senha:</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha">
                </div>
                
                <button type="submit" name="atualizar">Atualizar Perfil</button>
            </form>
        </div>
        
        <div id="excluir" class="tab-content">
            <h3>Excluir Conta</h3>
            <p>Atenção: Esta ação é irreversível. Todos os seus dados serão excluídos permanentemente.</p>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.')">
                <div class="form-group">
                    <label for="senha_exclusao">Digite sua senha para confirmar:</label>
                    <input type="password" id="senha_exclusao" name="senha_exclusao" required>
                </div>
                
                <button type="submit" name="excluir" class="btn-danger">Excluir Minha Conta</button>
            </form>
        </div>
    </div>
    
    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            
            // Esconder todos os conteúdos das abas
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].classList.remove("active");
            }
            
            // Remover a classe "active" de todas as abas
            tablinks = document.getElementsByClassName("tab");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("active");
            }
            
            // Mostrar o conteúdo da aba atual e adicionar a classe "active" ao botão que abriu a aba
            document.getElementById(tabName).classList.add("active");
            evt.currentTarget.classList.add("active");
        }
    </script>
</body>
</html>