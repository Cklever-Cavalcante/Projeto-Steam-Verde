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

// Processar a remoção de um item do carrinho
if (isset($_GET['remover']) && is_numeric($_GET['remover'])) {
    $id_item = $_GET['remover'];
    
    try {
        // Verificar se o item pertence ao usuário atual
        $stmt = $pdo->prepare("SELECT id FROM carrinho WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id_item, $_SESSION['usuario_id']]);
        
        if ($stmt->rowCount() > 0) {
            // Remover o item do carrinho
            $stmt = $pdo->prepare("DELETE FROM carrinho WHERE id = ?");
            $stmt->execute([$id_item]);
            
            $mensagem = "Item removido do carrinho com sucesso!";
            $tipo_mensagem = "sucesso";
        } else {
            $mensagem = "Item não encontrado no seu carrinho.";
            $tipo_mensagem = "erro";
        }
    } catch(PDOException $e) {
        $mensagem = "Erro ao remover item: " . $e->getMessage();
        $tipo_mensagem = "erro";
    }
}

// Processar o esvaziamento do carrinho
if (isset($_GET['esvaziar'])) {
    try {
        // Remover todos os itens do carrinho do usuário
        $stmt = $pdo->prepare("DELETE FROM carrinho WHERE usuario_id = ?");
        $stmt->execute([$_SESSION['usuario_id']]);
        
        $mensagem = "Carrinho esvaziado com sucesso!";
        $tipo_mensagem = "sucesso";
    } catch(PDOException $e) {
        $mensagem = "Erro ao esvaziar carrinho: " . $e->getMessage();
        $tipo_mensagem = "erro";
    }
}

// Buscar itens do carrinho do usuário
try {
    $stmt = $pdo->prepare("
        SELECT c.id, j.id AS jogo_id, j.nome, j.preco, j.imagem, c.data_adicao
        FROM carrinho c
        JOIN jogos j ON c.jogo_id = j.id
        WHERE c.usuario_id = ?
        ORDER BY c.data_adicao DESC
    ");
    $stmt->execute([$_SESSION['usuario_id']]);
    $itens_carrinho = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calcular o valor total
    $total = 0;
    foreach ($itens_carrinho as $item) {
        $total += $item['preco'];
    }
} catch(PDOException $e) {
    $mensagem = "Erro ao buscar itens do carrinho: " . $e->getMessage();
    $tipo_mensagem = "erro";
    $itens_carrinho = [];
    $total = 0;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Carrinho - Steam Verde</title>
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
            max-width: 900px;
        }
        .carrinho-vazio {
            text-align: center;
            padding: 30px;
        }
        .carrinho-item {
            display: flex;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            overflow: hidden;
        }
        .carrinho-item img {
            width: 120px;
            height: 120px;
            object-fit: cover;
        }
        .carrinho-item-info {
            flex: 1;
            padding: 15px;
            position: relative;
        }
        .carrinho-item-nome {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .carrinho-item-preco {
            font-size: 16px;
            color: #4CAF50;
            font-weight: bold;
        }
        .carrinho-item-remover {
            position: absolute;
            top: 15px;
            right: 15px;
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
        }
        .carrinho-item-remover:hover {
            text-decoration: underline;
        }
        .carrinho-total {
            text-align: right;
            margin-top: 20px;
            padding: 15px;
            background-color: #f1f1f1;
            border-radius: 5px;
        }
        .carrinho-total-valor {
            font-size: 24px;
            color: #4CAF50;
            font-weight: bold;
        }
        .carrinho-acoes {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .btn-continuar {
            background-color: #6c757d;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-continuar:hover {
            background-color: #5a6268;
        }
        .btn-esvaziar {
            background-color: #dc3545;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-esvaziar:hover {
            background-color: #c82333;
        }
        .btn-finalizar {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-finalizar:hover {
            background-color: #45a049;
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
                <?php if (isset($_SESSION['usuario_id'])): ?>
                <a href="perfil.php"><li>Meu Perfil</li></a>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Meu Carrinho</h2>
        
        <?php if (!empty($mensagem)): ?>
            <div class="mensagem <?php echo $tipo_mensagem; ?>">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>
        
        <?php if (empty($itens_carrinho)): ?>
            <div class="carrinho-vazio">
                <h3>Seu carrinho está vazio</h3>
                <p>Adicione jogos ao seu carrinho para continuar.</p>
                <a href="../../Pages/Loja/loja.html" class="btn-continuar">Continuar Comprando</a>
            </div>
        <?php else: ?>
            <?php foreach ($itens_carrinho as $item): ?>
                <div class="carrinho-item">
                    <img src="../../../<?php echo htmlspecialchars($item['imagem']); ?>" alt="<?php echo htmlspecialchars($item['nome']); ?>">
                    <div class="carrinho-item-info">
                        <h3 class="carrinho-item-nome"><?php echo htmlspecialchars($item['nome']); ?></h3>
                        <p class="carrinho-item-preco">R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></p>
                        <p>Adicionado em: <?php echo date('d/m/Y H:i', strtotime($item['data_adicao'])); ?></p>
                        <a href="?remover=<?php echo $item['id']; ?>" class="carrinho-item-remover" onclick="return confirm('Tem certeza que deseja remover este item do carrinho?')">Remover</a>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div class="carrinho-total">
                <h3>Total: <span class="carrinho-total-valor">R$ <?php echo number_format($total, 2, ',', '.'); ?></span></h3>
            </div>
            
            <div class="carrinho-acoes">
                <a href="../../Pages/Loja/loja.html" class="btn-continuar">Continuar Comprando</a>
                <a href="?esvaziar=1" class="btn-esvaziar" onclick="return confirm('Tem certeza que deseja esvaziar o carrinho?')">Esvaziar Carrinho</a>
                <a href="#" class="btn-finalizar">Finalizar Compra</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>