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

// Processar exclusão de jogo
if (isset($_GET['excluir']) && is_numeric($_GET['excluir'])) {
    $id_jogo = $_GET['excluir'];
    
    try {
        // Verificar se o jogo existe
        $stmt = $pdo->prepare("SELECT id FROM jogos WHERE id = ?");
        $stmt->execute([$id_jogo]);
        
        if ($stmt->rowCount() > 0) {
            // Excluir o jogo
            $stmt = $pdo->prepare("DELETE FROM jogos WHERE id = ?");
            $stmt->execute([$id_jogo]);
            
            $mensagem = "Jogo excluído com sucesso!";
            $tipo_mensagem = "sucesso";
        } else {
            $mensagem = "Jogo não encontrado.";
            $tipo_mensagem = "erro";
        }
    } catch(PDOException $e) {
        $mensagem = "Erro ao excluir jogo: " . $e->getMessage();
        $tipo_mensagem = "erro";
    }
}

// Processar adição ou edição de jogo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $preco = trim($_POST['preco']);
    $desenvolvedor = trim($_POST['desenvolvedor']);
    $editora = trim($_POST['editora']);
    $data_lancamento = trim($_POST['data_lancamento']);
    $plataforma = trim($_POST['plataforma']);
    $genero = trim($_POST['genero']);
    
    // Validações básicas
    if (empty($nome) || empty($descricao) || empty($preco)) {
        $mensagem = "Nome, descrição e preço são obrigatórios.";
        $tipo_mensagem = "erro";
    } else {
        // Processar upload de imagem
        $imagem_path = '';
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            $allowed = array('jpg', 'jpeg', 'png', 'webp');
            $filename = $_FILES['imagem']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            
            if (in_array(strtolower($ext), $allowed)) {
                $new_filename = uniqid() . '.' . $ext;
                $upload_dir = '../../imagens/promocao/';
                
                // Criar diretório se não existir
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $upload_path = $upload_dir . $new_filename;
                
                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $upload_path)) {
                    $imagem_path = 'assets/imagens/promocao/' . $new_filename;
                } else {
                    $mensagem = "Erro ao fazer upload da imagem.";
                    $tipo_mensagem = "erro";
                    goto skip_save; // Pular o salvamento
                }
            } else {
                $mensagem = "Formato de imagem não permitido. Use jpg, jpeg, png ou webp.";
                $tipo_mensagem = "erro";
                goto skip_save; // Pular o salvamento
            }
        }
        
        try {
            // Verificar se é uma edição ou adição
            if (isset($_POST['id']) && is_numeric($_POST['id'])) {
                // Edição de jogo existente
                $id_jogo = $_POST['id'];
                
                // Se uma nova imagem foi enviada, atualizar o caminho da imagem
                if (!empty($imagem_path)) {
                    $stmt = $pdo->prepare("UPDATE jogos SET nome = ?, descricao = ?, preco = ?, imagem = ?, desenvolvedor = ?, editora = ?, data_lancamento = ?, plataforma = ?, genero = ? WHERE id = ?");
                    $stmt->execute([$nome, $descricao, $preco, $imagem_path, $desenvolvedor, $editora, $data_lancamento, $plataforma, $genero, $id_jogo]);
                } else {
                    // Manter a imagem existente
                    $stmt = $pdo->prepare("UPDATE jogos SET nome = ?, descricao = ?, preco = ?, desenvolvedor = ?, editora = ?, data_lancamento = ?, plataforma = ?, genero = ? WHERE id = ?");
                    $stmt->execute([$nome, $descricao, $preco, $desenvolvedor, $editora, $data_lancamento, $plataforma, $genero, $id_jogo]);
                }
                
                $mensagem = "Jogo atualizado com sucesso!";
                $tipo_mensagem = "sucesso";
            } else {
                // Adição de novo jogo
                if (empty($imagem_path)) {
                    $imagem_path = 'assets/imagens/promocao/default.jpg'; // Imagem padrão
                }
                
                $stmt = $pdo->prepare("INSERT INTO jogos (nome, descricao, preco, imagem, desenvolvedor, editora, data_lancamento, plataforma, genero) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nome, $descricao, $preco, $imagem_path, $desenvolvedor, $editora, $data_lancamento, $plataforma, $genero]);
                
                $mensagem = "Jogo adicionado com sucesso!";
                $tipo_mensagem = "sucesso";
            }
        } catch(PDOException $e) {
            $mensagem = "Erro ao salvar jogo: " . $e->getMessage();
            $tipo_mensagem = "erro";
        }
    }
    
    skip_save: // Label para pular o salvamento em caso de erro
}

// Buscar jogo para edição
$jogo = null;
if (isset($_GET['editar']) && is_numeric($_GET['editar'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM jogos WHERE id = ?");
        $stmt->execute([$_GET['editar']]);
        $jogo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$jogo) {
            $mensagem = "Jogo não encontrado.";
            $tipo_mensagem = "erro";
        }
    } catch(PDOException $e) {
        $mensagem = "Erro ao buscar jogo: " . $e->getMessage();
        $tipo_mensagem = "erro";
    }
}

// Buscar todos os jogos
try {
    $stmt = $pdo->query("SELECT * FROM jogos ORDER BY nome");
    $jogos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $mensagem = "Erro ao buscar jogos: " . $e->getMessage();
    $tipo_mensagem = "erro";
    $jogos = [];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração de Jogos - Steam Verde</title>
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
                        <a class="nav-link" href="admin_usuarios.php">Gerenciar Usuários</a>
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
                        <h5><?php echo $jogo ? 'Editar Jogo' : 'Adicionar Novo Jogo'; ?></h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <?php if ($jogo): ?>
                                <input type="hidden" name="id" value="<?php echo $jogo['id']; ?>">
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome do Jogo*</label>
                                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $jogo ? htmlspecialchars($jogo['nome']) : ''; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição*</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="3" required><?php echo $jogo ? htmlspecialchars($jogo['descricao']) : ''; ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="preco" class="form-label">Preço (R$)*</label>
                                <input type="number" step="0.01" class="form-control" id="preco" name="preco" value="<?php echo $jogo ? htmlspecialchars($jogo['preco']) : ''; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="imagem" class="form-label">Imagem <?php echo $jogo ? '(deixe em branco para manter a atual)' : ''; ?></label>
                                <input type="file" class="form-control" id="imagem" name="imagem">
                                <?php if ($jogo && !empty($jogo['imagem'])): ?>
                                    <div class="mt-2">
                                        <img src="../../../<?php echo htmlspecialchars($jogo['imagem']); ?>" alt="<?php echo htmlspecialchars($jogo['nome']); ?>" class="img-thumbnail" style="max-height: 100px;">
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label for="desenvolvedor" class="form-label">Desenvolvedor</label>
                                <input type="text" class="form-control" id="desenvolvedor" name="desenvolvedor" value="<?php echo $jogo ? htmlspecialchars($jogo['desenvolvedor']) : ''; ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="editora" class="form-label">Editora</label>
                                <input type="text" class="form-control" id="editora" name="editora" value="<?php echo $jogo ? htmlspecialchars($jogo['editora']) : ''; ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="data_lancamento" class="form-label">Data de Lançamento</label>
                                <input type="date" class="form-control" id="data_lancamento" name="data_lancamento" value="<?php echo $jogo ? htmlspecialchars($jogo['data_lancamento']) : ''; ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="plataforma" class="form-label">Plataforma</label>
                                <input type="text" class="form-control" id="plataforma" name="plataforma" value="<?php echo $jogo ? htmlspecialchars($jogo['plataforma']) : 'Windows'; ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label for="genero" class="form-label">Gênero</label>
                                <select class="form-control" id="genero" name="genero">
                                    <option value="">Selecione um gênero</option>
                                    <option value="Ação" <?php echo ($jogo && $jogo['genero'] == 'Ação') ? 'selected' : ''; ?>>Ação</option>
                                    <option value="Aventura" <?php echo ($jogo && $jogo['genero'] == 'Aventura') ? 'selected' : ''; ?>>Aventura</option>
                                    <option value="Ação/Aventura" <?php echo ($jogo && $jogo['genero'] == 'Ação/Aventura') ? 'selected' : ''; ?>>Ação/Aventura</option>
                                    <option value="RPG" <?php echo ($jogo && $jogo['genero'] == 'RPG') ? 'selected' : ''; ?>>RPG</option>
                                    <option value="Estratégia" <?php echo ($jogo && $jogo['genero'] == 'Estratégia') ? 'selected' : ''; ?>>Estratégia</option>
                                    <option value="Simulação" <?php echo ($jogo && $jogo['genero'] == 'Simulação') ? 'selected' : ''; ?>>Simulação</option>
                                    <option value="Esportes" <?php echo ($jogo && $jogo['genero'] == 'Esportes') ? 'selected' : ''; ?>>Esportes</option>
                                    <option value="Corrida" <?php echo ($jogo && $jogo['genero'] == 'Corrida') ? 'selected' : ''; ?>>Corrida</option>
                                    <option value="FPS" <?php echo ($jogo && $jogo['genero'] == 'FPS') ? 'selected' : ''; ?>>FPS</option>
                                    <option value="Indie" <?php echo ($jogo && $jogo['genero'] == 'Indie') ? 'selected' : ''; ?>>Indie</option>
                                </select>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <?php echo $jogo ? 'Atualizar Jogo' : 'Adicionar Jogo'; ?>
                                </button>
                                <?php if ($jogo): ?>
                                    <a href="admin_jogos.php" class="btn btn-secondary">Cancelar Edição</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Lista de Jogos</h5>
                        <div>
                            <input type="text" id="searchInput" class="form-control" placeholder="Buscar jogos...">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="jogosTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Imagem</th>
                                        <th>Nome</th>
                                        <th>Preço</th>
                                        <th>Gênero</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($jogos as $j): ?>
                                        <tr>
                                            <td><?php echo $j['id']; ?></td>
                                            <td>
                                                <?php if (!empty($j['imagem'])): ?>
                                                    <img src="../../../<?php echo htmlspecialchars($j['imagem']); ?>" alt="<?php echo htmlspecialchars($j['nome']); ?>" class="img-thumbnail" style="max-height: 50px;">
                                                <?php else: ?>
                                                    <span class="text-muted">Sem imagem</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($j['nome']); ?></td>
                                            <td>R$ <?php echo number_format($j['preco'], 2, ',', '.'); ?></td>
                                            <td><?php echo htmlspecialchars($j['genero'] ?? 'N/A'); ?></td>
                                            <td>
                                                <a href="admin_jogos.php?editar=<?php echo $j['id']; ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="admin_jogos.php?excluir=<?php echo $j['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este jogo?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($jogos)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center">Nenhum jogo cadastrado.</td>
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
        // Filtro de busca para a tabela de jogos
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const table = document.getElementById('jogosTable');
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