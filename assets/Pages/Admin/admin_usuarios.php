<?php
session_start();

// Verificar se o usuário está logado e é administrador
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../Usuario/login.php");
    exit;
}

// Incluir arquivo de conexão
require_once '../../database/conexao.php';

// Verificar se o usuário é administrador
if (!isset($_SESSION['usuario_papel']) || $_SESSION['usuario_papel'] !== 'admin') {
    $_SESSION['mensagem'] = "Acesso restrito a administradores.";
    $_SESSION['tipo_mensagem'] = "erro";
    header("Location: ../Usuario/perfil.php");
    exit;
}

$mensagem = '';
$tipo_mensagem = '';

// Processar exclusão de usuário
if (isset($_GET['excluir']) && is_numeric($_GET['excluir'])) {
    $id_usuario = $_GET['excluir'];
    
    // Não permitir que o administrador exclua a si mesmo
    if ($id_usuario == $_SESSION['usuario_id']) {
        $mensagem = "Você não pode excluir sua própria conta por aqui.";
        $tipo_mensagem = "erro";
    } else {
        try {
            // Verificar se o usuário existe
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE id = ?");
            $stmt->execute([$id_usuario]);
            
            if ($stmt->rowCount() > 0) {
                // Excluir o usuário
                $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
                $stmt->execute([$id_usuario]);
                
                $mensagem = "Usuário excluído com sucesso!";
                $tipo_mensagem = "sucesso";
            } else {
                $mensagem = "Usuário não encontrado.";
                $tipo_mensagem = "erro";
            }
        } catch(PDOException $e) {
            $mensagem = "Erro ao excluir usuário: " . $e->getMessage();
            $tipo_mensagem = "erro";
        }
    }
}

// Processar alteração de papel do usuário
if (isset($_GET['alterarPapel']) && is_numeric($_GET['alterarPapel']) && isset($_GET['papel'])) {
    $id_usuario = $_GET['alterarPapel'];
    $novo_papel = $_GET['papel'] === 'admin' ? 'admin' : 'usuario';
    
    try {
        // Verificar se o usuário existe
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE id = ?");
        $stmt->execute([$id_usuario]);
        
        if ($stmt->rowCount() > 0) {
            // Alterar o papel do usuário
            $stmt = $pdo->prepare("UPDATE usuarios SET papel = ? WHERE id = ?");
            $stmt->execute([$novo_papel, $id_usuario]);
            
            $mensagem = "Papel do usuário alterado para {$novo_papel} com sucesso!";
            $tipo_mensagem = "sucesso";
        } else {
            $mensagem = "Usuário não encontrado.";
            $tipo_mensagem = "erro";
        }
    } catch(PDOException $e) {
        $mensagem = "Erro ao alterar papel do usuário: " . $e->getMessage();
        $tipo_mensagem = "erro";
    }
}

// Processar adição ou edição de usuário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $papel = isset($_POST['papel']) ? trim($_POST['papel']) : 'usuario';
    $senha = trim($_POST['senha']);
    $confirmar_senha = trim($_POST['confirmar_senha']);
    
    // Validações básicas
    if (empty($nome) || empty($email)) {
        $mensagem = "Nome e email são obrigatórios.";
        $tipo_mensagem = "erro";
    } elseif (isset($_POST['id'])) { // Edição
        $id_usuario = $_POST['id'];
        
        try {
            // Verificar se o email já existe para outro usuário
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
            $stmt->execute([$email, $id_usuario]);
            
            if ($stmt->rowCount() > 0) {
                $mensagem = "Este email já está sendo usado por outro usuário.";
                $tipo_mensagem = "erro";
            } else {
                // Se a senha foi fornecida, atualizar a senha também
                if (!empty($senha)) {
                    if ($senha !== $confirmar_senha) {
                        $mensagem = "As senhas não coincidem.";
                        $tipo_mensagem = "erro";
                    } elseif (strlen($senha) < 6) {
                        $mensagem = "A senha deve ter pelo menos 6 caracteres.";
                        $tipo_mensagem = "erro";
                    } else {
                        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, papel = ?, senha = ? WHERE id = ?");
                        $stmt->execute([$nome, $email, $papel, $senha_hash, $id_usuario]);
                        
                        $mensagem = "Usuário atualizado com sucesso!";
                        $tipo_mensagem = "sucesso";
                    }
                } else {
                    // Atualizar apenas nome, email e papel
                    $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, papel = ? WHERE id = ?");
                    $stmt->execute([$nome, $email, $papel, $id_usuario]);
                    
                    $mensagem = "Usuário atualizado com sucesso!";
                    $tipo_mensagem = "sucesso";
                }
            }
        } catch(PDOException $e) {
            $mensagem = "Erro ao atualizar usuário: " . $e->getMessage();
            $tipo_mensagem = "erro";
        }
    } else { // Adição
        if (empty($senha)) {
            $mensagem = "Senha é obrigatória para novos usuários.";
            $tipo_mensagem = "erro";
        } elseif ($senha !== $confirmar_senha) {
            $mensagem = "As senhas não coincidem.";
            $tipo_mensagem = "erro";
        } elseif (strlen($senha) < 6) {
            $mensagem = "A senha deve ter pelo menos 6 caracteres.";
            $tipo_mensagem = "erro";
        } else {
            try {
                // Verificar se o email já existe
                $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
                $stmt->execute([$email]);
                
                if ($stmt->rowCount() > 0) {
                    $mensagem = "Este email já está cadastrado.";
                    $tipo_mensagem = "erro";
                } else {
                    // Hash da senha
                    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                    
                    // Inserir novo usuário
                    $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, papel) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$nome, $email, $senha_hash, $papel]);
                    
                    $mensagem = "Usuário cadastrado com sucesso!";
                    $tipo_mensagem = "sucesso";
                }
            } catch(PDOException $e) {
                $mensagem = "Erro ao cadastrar usuário: " . $e->getMessage();
                $tipo_mensagem = "erro";
            }
        }
    }
}

// Buscar usuário para edição
$usuario = null;
if (isset($_GET['editar']) && is_numeric($_GET['editar'])) {
    try {
        $stmt = $pdo->prepare("SELECT id, nome, email, papel, data_cadastro FROM usuarios WHERE id = ?");
        $stmt->execute([$_GET['editar']]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$usuario) {
            $mensagem = "Usuário não encontrado.";
            $tipo_mensagem = "erro";
        }
    } catch(PDOException $e) {
        $mensagem = "Erro ao buscar usuário: " . $e->getMessage();
        $tipo_mensagem = "erro";
    }
}

// Buscar todos os usuários
try {
    $stmt = $pdo->query("SELECT id, nome, email, papel, data_cadastro FROM usuarios ORDER BY nome");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $mensagem = "Erro ao buscar usuários: " . $e->getMessage();
    $tipo_mensagem = "erro";
    $usuarios = [];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração de Usuários - Steam Verde</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #1b2838;
            --secondary-color: #2a475e;
            --accent-color: #66c0f4;
            --text-color: #c7d5e0;
            --success-color: #5cb85c;
            --warning-color: #f0ad4e;
            --danger-color: #d9534f;
        }
        
        body {
            background-color: var(--primary-color);
            color: var(--text-color);
            font-family: Arial, sans-serif;
        }
        
        .navbar {
            background-color: var(--secondary-color);
        }
        
        .card {
            background-color: var(--secondary-color);
            border: none;
            margin-bottom: 20px;
        }
        
        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }
        
        .btn-primary:hover {
            background-color: #4b8ebd;
            border-color: #4b8ebd;
        }
        
        .table {
            color: var(--text-color);
        }
        
        .table thead th {
            background-color: var(--secondary-color);
            border-color: #3a5875;
        }
        
        .table tbody td {
            border-color: #3a5875;
        }
        
        .alert-success {
            background-color: var(--success-color);
            color: white;
        }
        
        .alert-danger {
            background-color: var(--danger-color);
            color: white;
        }
        
        .alert-info {
            background-color: var(--warning-color);
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="../../../index.php">
                <img src="../../imagens/icone-steam-vert.png" alt="Steam Verde" height="40">
                Steam Verde
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../../../index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_jogos.php">Gerenciar Jogos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin_usuarios.php">Gerenciar Usuários</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Usuario/perfil.php">Meu Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Usuario/logout.php">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php if (!empty($mensagem)): ?>
            <div class="alert alert-<?php echo $tipo_mensagem == 'sucesso' ? 'success' : ($tipo_mensagem == 'erro' ? 'danger' : 'info'); ?>">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5><?php echo $usuario ? 'Editar Usuário' : 'Adicionar Novo Usuário'; ?></h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <?php if ($usuario): ?>
                                <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome*</label>
                                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $usuario ? htmlspecialchars($usuario['nome']) : ''; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email*</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $usuario ? htmlspecialchars($usuario['email']) : ''; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="papel" class="form-label">Papel</label>
                                <select class="form-control" id="papel" name="papel">
                                    <option value="usuario" <?php echo ($usuario && $usuario['papel'] == 'usuario') ? 'selected' : ''; ?>>Usuário</option>
                                    <option value="admin" <?php echo ($usuario && $usuario['papel'] == 'admin') ? 'selected' : ''; ?>>Administrador</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="senha" class="form-label"><?php echo $usuario ? 'Nova Senha (deixe em branco para manter a atual)' : 'Senha*'; ?></label>
                                <input type="password" class="form-control" id="senha" name="senha" <?php echo $usuario ? '' : 'required'; ?>>
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirmar_senha" class="form-label"><?php echo $usuario ? 'Confirmar Nova Senha' : 'Confirmar Senha*'; ?></label>
                                <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" <?php echo $usuario ? '' : 'required'; ?>>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <?php echo $usuario ? 'Atualizar Usuário' : 'Adicionar Usuário'; ?>
                                </button>
                                <?php if ($usuario): ?>
                                    <a href="admin_usuarios.php" class="btn btn-secondary">Cancelar Edição</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Lista de Usuários</h5>
                        <div>
                            <input type="text" id="searchInput" class="form-control" placeholder="Buscar usuários...">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="usuariosTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Papel</th>
                                        <th>Data de Cadastro</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($usuarios as $u): ?>
                                        <tr>
                                            <td><?php echo $u['id']; ?></td>
                                            <td><?php echo htmlspecialchars($u['nome']); ?></td>
                                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                                            <td>
                                                <span class="badge <?php echo $u['papel'] == 'admin' ? 'bg-danger' : 'bg-primary'; ?>">
                                                    <?php echo htmlspecialchars($u['papel']); ?>
                                                </span>
                                                <?php if ($u['id'] != $_SESSION['usuario_id']): ?>
                                                    <?php if ($u['papel'] == 'usuario'): ?>
                                                        <a href="admin_usuarios.php?alterarPapel=<?php echo $u['id']; ?>&papel=admin" class="btn btn-sm btn-outline-light" title="Promover a Administrador" onclick="return confirm('Promover este usuário a administrador?')">
                                                            <i class="fas fa-arrow-up"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="admin_usuarios.php?alterarPapel=<?php echo $u['id']; ?>&papel=usuario" class="btn btn-sm btn-outline-light" title="Rebaixar para Usuário" onclick="return confirm('Rebaixar este administrador para usuário comum?')">
                                                            <i class="fas fa-arrow-down"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($u['data_cadastro'])); ?></td>
                                            <td>
                                                <a href="admin_usuarios.php?editar=<?php echo $u['id']; ?>" class="btn btn-sm btn-warning" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if ($u['id'] != $_SESSION['usuario_id']): ?>
                                                    <a href="admin_usuarios.php?excluir=<?php echo $u['id']; ?>" class="btn btn-sm btn-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($usuarios)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Nenhum usuário cadastrado.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filtro de busca para a tabela de usuários
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const table = document.getElementById('usuariosTable');
            const rows = table.getElementsByTagName('tr');
            
            for (let i = 1; i < rows.length; i++) { // Começar do 1 para pular o cabeçalho
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let found = false;
                
                for (let j = 0; j < cells.length; j++) {
                    const cellText = cells[j].textContent.toLowerCase();
                    
                    if (cellText.indexOf(searchValue) > -1) {
                        found = true;
                        break;
                    }
                }
                
                row.style.display = found ? '' : 'none';
            }
        });
    </script>
</body>
</html>