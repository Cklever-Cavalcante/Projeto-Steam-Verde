<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    // Redirecionar para a página de login com parâmetro de retorno
    header("Location: login.php?redirect=" . urlencode($_SERVER['HTTP_REFERER']));
    exit;
}

// Verificar se o ID do jogo foi fornecido
if (!isset($_GET['jogo_id']) || !is_numeric($_GET['jogo_id'])) {
    header("Location: ../../../index.html");
    exit;
}

$jogo_id = $_GET['jogo_id'];
$usuario_id = $_SESSION['usuario_id'];

// Incluir arquivo de conexão
require_once '../../database/conexao.php';

try {
    // Verificar se o jogo existe
    $stmt = $pdo->prepare("SELECT id FROM jogos WHERE id = ?");
    $stmt->execute([$jogo_id]);
    
    if ($stmt->rowCount() == 0) {
        // Jogo não encontrado
        $_SESSION['mensagem'] = "Jogo não encontrado.";
        $_SESSION['tipo_mensagem'] = "erro";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
    
    // Verificar se o jogo já está no carrinho do usuário
    $stmt = $pdo->prepare("SELECT id FROM carrinho WHERE usuario_id = ? AND jogo_id = ?");
    $stmt->execute([$usuario_id, $jogo_id]);
    
    if ($stmt->rowCount() > 0) {
        // Jogo já está no carrinho
        $_SESSION['mensagem'] = "Este jogo já está no seu carrinho.";
        $_SESSION['tipo_mensagem'] = "info";
    } else {
        // Adicionar jogo ao carrinho
        $stmt = $pdo->prepare("INSERT INTO carrinho (usuario_id, jogo_id) VALUES (?, ?)");
        $stmt->execute([$usuario_id, $jogo_id]);
        
        $_SESSION['mensagem'] = "Jogo adicionado ao carrinho com sucesso!";
        $_SESSION['tipo_mensagem'] = "sucesso";
    }
    
    // Redirecionar de volta para a página anterior ou para o carrinho
    if (isset($_GET['redirect']) && $_GET['redirect'] == 'carrinho') {
        header("Location: carrinho.php");
    } else {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
    exit;
    
} catch(PDOException $e) {
    $_SESSION['mensagem'] = "Erro ao adicionar ao carrinho: " . $e->getMessage();
    $_SESSION['tipo_mensagem'] = "erro";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
?>