<?php
session_start();

// Verificar se o ID do jogo foi fornecido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: ../Loja/loja.php");
    exit;
}

$jogo_id = $_GET['id'];

// Incluir arquivo de conexão
require_once '../../database/conexao.php';

// Buscar dados do jogo
try {
    $stmt = $pdo->prepare("SELECT * FROM jogos WHERE id = ?");
    $stmt->execute([$jogo_id]);
    $jogo = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$jogo) {
        // Jogo não encontrado
        $_SESSION['mensagem'] = "Jogo não encontrado.";
        $_SESSION['tipo_mensagem'] = "erro";
        header("Location: ../Loja/loja.php");
        exit;
    }
} catch(PDOException $e) {
    $_SESSION['mensagem'] = "Erro ao buscar dados do jogo: " . $e->getMessage();
    $_SESSION['tipo_mensagem'] = "erro";
    header("Location: ../Loja/loja.php");
    exit;
}

// Verificar se há mensagem de sessão
$mensagem = '';
$tipo_mensagem = '';
if (isset($_SESSION['mensagem']) && isset($_SESSION['tipo_mensagem'])) {
    $mensagem = $_SESSION['mensagem'];
    $tipo_mensagem = $_SESSION['tipo_mensagem'];
    
    // Limpar as mensagens da sessão
    unset($_SESSION['mensagem']);
    unset($_SESSION['tipo_mensagem']);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($jogo['nome']); ?> - Steam Verde</title>
    <link rel="stylesheet" href="../../CSS/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Akaya+Kanadaka&family=Jersey+20&family=Sancreek&display=swap" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <style>
        .mensagem {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            text-align: center;
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
        .info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
    </style>
</head>
<body>

    <header>
        <div style="display: flex; align-items: center; justify-content: center;  padding-right: 20px;">
           <a href="../../../index.php"><img src="../../imagens/icone-steam-vert.png" alt="Steam Verde"></a>
        </div>
        <nav>
            <ul>
                <a href="../../../index.php"><li>Inicio</li></a>
                <a href="../Loja/loja.php"><li>Loja</li></a>
                <a href="../Comunidade/comunidade.html"><li>Novidades</li></a>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <a href="../Usuario/perfil.php"><li>Meu Perfil</li></a>
                    <a href="../Usuario/carrinho.php"><li>Carrinho</li></a>
                <?php else: ?>
                    <a href="../Usuario/login.php"><li>Login</li></a>
                    <a href="../Usuario/cadastro.php"><li>Cadastro</li></a>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <?php if (!empty($mensagem)): ?>
        <div class="mensagem <?php echo $tipo_mensagem; ?>">
            <?php echo $mensagem; ?>
        </div>
    <?php endif; ?>

    <section id="detalhes-area1">
        <div style="margin-top: 50px; margin-left: 210px;">
            <h1 style="color: #fff; font-size: 40px;"><?php echo htmlspecialchars($jogo['nome']); ?></h1>
            <ul style="display: flex; gap: 2rem; list-style: none;">
                <li style="color: #ccc; padding-top: 5px;">4.5</li>
                <li style="color: #ccc; padding-top: 5px;"><?php echo htmlspecialchars($jogo['genero']); ?></li>
                <li style="color: #ccc; padding-top: 5px;"><?php echo htmlspecialchars($jogo['plataforma']); ?></li>
            </ul>
        </div>
                      
        <div style="display: flex; gap: 20rem;">
            <div class="container-detalhes">
                <!-- Aqui poderia ser um vídeo do jogo, mas vamos usar a imagem por enquanto -->
                <img src="../../../<?php echo htmlspecialchars($jogo['imagem']); ?>" alt="<?php echo htmlspecialchars($jogo['nome']); ?>" style="width: 900px; height: 500px; object-fit: cover;">
            </div>

            <div style="display: block;">
                <div style="position: relative;">
                    <!-- Logo do jogo, usando a mesma imagem por enquanto -->
                    <img src="../../../<?php echo htmlspecialchars($jogo['imagem']); ?>" alt="<?php echo htmlspecialchars($jogo['nome']); ?>" style="width: 250px; height: 150px; object-fit: cover;">
                         
                    <h3 style="margin-top: 10px; color: #ccc;">Jogo Base</h3>
                    <h2 style="color: greenyellow;">R$ <?php echo number_format($jogo['preco'], 2, ',', '.'); ?></h2>
                    <div style='background: cornflowerblue; margin-top: 10px; border-radius: 5px;'>
                        <h1 style="color: #fff; text-align: center; padding: 5px;">Compre Agora</h1>
                    </div>
                    <div style='background: #424141; margin-top: 10px; border-radius: 5px;'>
                        <a href="../Usuario/adicionar_ao_carrinho.php?jogo_id=<?php echo $jogo['id']; ?>" style="text-decoration: none;">
                            <h2 style="color: #fff; text-align: center; padding: 5px;">Adicionar ao Carrinho</h2>
                        </a>
                    </div>

                    <div style='background: #424141; margin-top: 10px; border-radius: 5px;'>
                        <h2 style="color: #fff; text-align: center; padding: 5px;">Para a Lista de Desejos</h2>
                    </div>

                    <div style="margin-top: 25px;">
                        <ul style="list-style: none; columns: 2;">
                            <li style="color: #ccc;">Recompensas Steam-Verde</li>
                            <li style="color: #fff;">Ganhe 5% de volta</li>
                        </ul>
                        <div style="height: 1px; background: #fff; margin-top: 20px; margin-bottom: 20px;"></div>
                        <ul style="list-style: none; columns: 2;">
                            <li style="color: #ccc;">Tipo de reembolso</li>
                            <li style="color: #fff;">Autorreembolsável</li>
                        </ul>
                        <div style="height: 1px; background: #fff; margin-top: 20px; margin-bottom: 20px;"></div>

                        <ul style="list-style: none; columns: 2;">
                            <li style="color: #ccc;">Desenvolvedor</li>
                            <li style="color: #fff;"><?php echo htmlspecialchars($jogo['desenvolvedor']); ?></li>
                        </ul>
                        <div style="height: 1px; background: #fff; margin-top: 20px; margin-bottom: 20px;"></div>

                        <ul style="list-style: none; columns: 2;">
                            <li style="color: #ccc;">Editora</li>
                            <li style="color: #fff;"><?php echo htmlspecialchars($jogo['editora']); ?></li>
                        </ul>
                        <div style="height: 1px; background: #fff; margin-top: 20px; margin-bottom: 20px;"></div>

                        <ul style="list-style: none; columns: 2;">
                            <li style="color: #ccc;">Data de lançamento</li>
                            <li style="color: #fff;"><?php echo date('d/m/Y', strtotime($jogo['data_lancamento'])); ?></li>
                        </ul>
                        <div style="height: 1px; background: #fff; margin-top: 20px; margin-bottom: 20px;"></div>
                    </div>

                    <ul style="list-style: none; columns: 2;">
                        <li style="color: #ccc;">Plataforma</li>
                        <li style="color: #fff;">
                            <?php if (strpos(strtolower($jogo['plataforma']), 'windows') !== false || strpos(strtolower($jogo['plataforma']), 'pc') !== false): ?>
                                <ion-icon name="logo-windows"></ion-icon>
                            <?php endif; ?>
                            <?php if (strpos(strtolower($jogo['plataforma']), 'mac') !== false): ?>
                                <ion-icon name="logo-apple"></ion-icon>
                            <?php endif; ?>
                            <?php if (strpos(strtolower($jogo['plataforma']), 'linux') !== false): ?>
                                <ion-icon name="logo-tux"></ion-icon>
                            <?php endif; ?>
                        </li>
                    </ul>
                    <div style="height: 1px; background: #fff; margin-top: 20px; margin-bottom: 20px;"></div>
                </div>
            </div>
        </div>

        <div style="margin-left: 210px;">
            <h1 style="text-align: center; color: #fff; width: 55%;"><?php echo htmlspecialchars($jogo['descricao']); ?></h1>
        </div>

        <div style="margin: 50px; width: 60%; margin-left: 100px;">
            <p style="color: #ccc; text-align: center; padding: 10px; font-size: 20px;">Gênero: <?php echo htmlspecialchars($jogo['genero']); ?></p>
        </div>
    </section>

    <footer>
        <div style="display: flex;">
            <ion-icon name="logo-instagram" class="icone_svg"></ion-icon>
            <ion-icon name="logo-twitter" class="icone_svg"></ion-icon>
            <ion-icon name="logo-facebook" class="icone_svg"></ion-icon>
        </div>
        <div style="margin-top: 30px;">
            <h4 style="color: cornflowerblue;">Recursos</h4>
            <ul class="ul_footer">
                <li>Apoie-um-criador</li>
                <li>Distribuir na Steam Verde</li>
                <li>Carreiras</li>
                <li>Política de Conteúdo de Fãs</li>
                <li>Empresa</li>
                <li>Pesquisa de Usuário</li>
                <li>EULA da Loja</li>
                <li>Serviços Online</li>
                <li>Regras da Comunidade</li>
                <li>Sala de Imprensa da Steam Verde</li>
            </ul>
        </div>
        <div style="display: inline-block; width: 90%; border: 1px solid #fff;"></div>

        <div style="margin-top: 15px;">
            <p style="color: #cccccccc; width: 700px; line-height: 1.3rem;">© 2024 Steam Verde Corporation. Todos os direitos reservados. Todas as marcas comerciais são propriedade dos respetivos proprietários nos E.U.A. e outros países.
                IVA incluído em todos os preços onde aplicável.</p>
        </div>

        <div style="display: inline-block; width: 90%; border: 1px solid #fff;"></div>

        <div style="margin-top: 15px;">
            <div class="ul_final"><p>Acerca da Steam Verde</p></div>
            <div style="display: inline-block; border: 1px solid #fff; height: 15px; margin-left: 15px; margin-right: 15px;"></div>
            <div class="ul_final"><p>Apoio</p></div>
            <div style="display: inline-block; border: 1px solid #fff; height: 15px; margin-left: 15px; margin-right: 15px;"></div>
            <div class="ul_final"><p>SteamVerdeworks</p></div>
            <div style="display: inline-block; border: 1px solid #fff; height: 15px; margin-left: 15px; margin-right: 15px;"></div>
            <div class="ul_final"><p>Cartões-Presente</p></div>
        </div>
    </footer>
</body>
</html>