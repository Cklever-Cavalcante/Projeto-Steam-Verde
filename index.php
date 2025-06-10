<?php
session_start();

// Verificar se há mensagens na sessão
$successMessage = '';
$errorMessage = '';
$infoMessage = '';

if (isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    $errorMessage = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

if (isset($_SESSION['info_message'])) {
    $infoMessage = $_SESSION['info_message'];
    unset($_SESSION['info_message']);
}

// Verificar se o usuário está logado
$isLoggedIn = isset($_SESSION['usuario_id']);

// Incluir arquivo de conexão
require_once 'assets/database/conexao.php';

// Buscar jogos do banco de dados para a seção de promoções
try {
    $stmt = $pdo->query("SELECT * FROM jogos ORDER BY nome LIMIT 12");
    $jogos_promocao = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $errorMessage = "Erro ao buscar jogos: " . $e->getMessage();
    $jogos_promocao = [];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Steam Verde</title>
  <link rel="stylesheet" href="./assets/CSS/style.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Akaya+Kanadaka&family=Jersey+20&family=Sancreek&display=swap" rel="stylesheet">
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <style>
    .message {
      padding: 10px;
      margin: 10px 0;
      border-radius: 5px;
      text-align: center;
    }
    .success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    .error {
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
      <?php if ($successMessage): ?>
        <div class="message success"><?php echo $successMessage; ?></div>
      <?php endif; ?>
      <?php if ($errorMessage): ?>
        <div class="message error"><?php echo $errorMessage; ?></div>
      <?php endif; ?>
      <?php if ($infoMessage): ?>
        <div class="message info"><?php echo $infoMessage; ?></div>
      <?php endif; ?>

      <header>
        <div style="display: flex; align-items: center; justify-content: center;  padding-right: 20px;">
           <a href="index.php"><img src="assets/imagens/icone-steam-vert.png" alt="Steam Verde"></a>
        </div>
     <nav>
        <ul>
           <a href="index.php"><li>Inicio</li></a>
          <a href="assets/Pages/Loja/loja.php"><li>Loja</li></a>
          <a href="assets/Pages/Comunidade/comunidade.html"><li>Novidades</li></a>
          <?php if ($isLoggedIn): ?>
            <a href="assets/Pages/Usuario/perfil.php"><li>Meu Perfil</li></a>
            <a href="assets/Pages/Usuario/carrinho.php"><li>Carrinho</li></a>
            <?php if (isset($_SESSION['usuario_papel']) && $_SESSION['usuario_papel'] === 'admin'): ?>
            <a href="assets/Pages/Admin/admin_usuarios.php"><li>Área Administrativa</li></a>
            <?php endif; ?>
          <?php else: ?>
            <a href="assets/Pages/Usuario/login.php"><li>Login</li></a>
            <a href="assets/Pages/Usuario/cadastro.php"><li>Cadastro</li></a>
          <?php endif; ?>
        </ul>
     </nav>
      </header>

      <section id="area1">
          <div>
            <ul id="teste1">
          <li id="AbrirLoja">Loja
              <ul id="loja">
                 <li style="font-size: 15px; text-align: start; padding-left: 10px;">Página Inicial</li>
                 <li style="font-size: 15px; text-align: start; padding-left: 10px;">Recomendações da comunidade</li>
                 <li style="font-size: 15px; text-align:start; padding-left: 10px;">Visualizados Recentemente</li>
                 <li style="font-size: 15px; text-align:start; padding-left: 10px;">Curadores Steam Verde</li>
              </ul>
          </li>
          <li id="AbrirLoja2">Novidades e Destaques
            <ul id="loja2">
                  <h3 style="color: cornflowerblue; text-align:start; padding-left: 15px; margin-bottom: 5px;">Populares</h3>
               <li style="font-size: 15px; text-align:start; padding-left: 15px;">Mais Vendidos</li>
               <li style="font-size: 15px; text-align:start; padding-left: 15px;">Mais Jogados</li>
               <li style="font-size: 15px; text-align:start; padding-left: 15px;">Novos lançamentos</li>
               <li style="font-size: 15px; text-align:start; padding-left: 15px;">Próximos Lançamentos</li>
                   
               <h3 style="color: cornflowerblue; text-align:start; padding-left: 15px; margin-bottom: 5px; margin-top: 5px;">Notícias e atualizações</h3>
               <li style="font-size: 15px; text-align:start; padding-left: 15px;">Recentemente atualizados</li>
            </ul>
          </li>
          <li id="AbrirLoja3">Categorias
            <ul id="loja3">
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">Ação</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">Arcade e Ritimo</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">Hack e Slash</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">Jogos de tiro em primeira  pessoa</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">Jogos de tiro em terceira pessoa</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">RPG de aventura</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">Luta e artes marciais</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">Plataformas e Runners</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">Aventura</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">Casual</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">RPG por Turnos</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">RPG de Grupo</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">Romance Visual</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">Simulador</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">Corrida</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">Pesca e caça</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">Espaço e aviação</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">Imersivo e vida</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">Historia Excelente</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">Simulador de corridas</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">Simulador de desportos</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">Hobbies e empregos</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">Construção e automoção</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">MetroidVania</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">Objetos escondidos</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">quebra-cabeças</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">Terror</li>
              <li style="font-size: 15px; text-align:start; padding-left: 15px;">Ficção científica</li>
            </ul>
          </li>
          <li>Loja de Pontos</li>
          <li>Notícias</li>
          <li>Laboratório</li>
        </ul> 
        <input type="text" name="" id=""  placeholder="Pesquisar..." class="input_pesquisa">
        <button class="button_pesquisa">
          <span class="material-symbols-outlined" style="color: cornflowerblue;">
            <span class="material-symbols-outlined">
              search
              </span>
            </button>
          </div>

        <div id="container_img">
        <a href="#"><img src="assets/imagens/Red-dead-redemption-2-posters-impress-es-abstratas-retrato-arte-da-parede-quadros-em-tela-para.jpg" alt=""></a>  
           <a href="#"><img src="assets/imagens/Poster-The-Legend-of-Zelda-Tears-of-the-Kingdom-Link-Unleashed-61x91-5cm-Pyramid-PP35325_f82aff08-1f77-4458-9175-f4f8dd0ee057.jpg" alt="Zelda"></a>
          <a href="#"><img src="assets/imagens/71m3WO+0Z8L._AC_UF894,1000_QL80_.jpg" alt="Horizon"></a>
           <a href="#"><img src="assets/imagens/s-l1200.jpg" alt=""></a>
          <a href="#"><img src="assets/imagens/Emwr1TNW4AEWcku.jpg" alt="God Of War"></a>
          <a href="#"><img src="assets/imagens/71oophq2KQL._AC_UF894,1000_QL80_DpWeblab_.jpg" alt="Ghost of tsushima"></a>
           <a href="#"><img src="assets/imagens/81YMKsZX8ZL._AC_UF894,1000_QL80_DpWeblab_.jpg" alt="Hallo"></a>
        </div>
      </section>

      <section id="are2">
        <div style="display: flex;">
        <div id="container_esquerdo">
            <div>
              <h3 style="color: cornflowerblue; padding-bottom: 10px;">Cartões Presentes Steam Verde</h3>
              <p>Ofereçe o prazer de jogar</p>
            </div>
            <div style="margin-top: 30px;">
              <h3 style="color: cornflowerblue; padding-bottom: 10px;">Recomendações</h3>
              <p>De amigos</p>
              <p>De curadores</p>
              <p>Marcadores</p>
            </div>

            <div style="margin-top: 30px;">
              <h3 style="color: cornflowerblue; padding-bottom: 10px;">Ver por Género</h3>
              <p>Gratis para jogar</p>
              <p>Acesso antecipado</p>
              <p>Aventura</p>
              <p>Ação</p>
              <p>Casual</p>
              <p>Corridas</p>
              <p>Desporto</p>
              <p>Estratégia</p>
              <p>Indíe</p>
              <p>Multijogador em massa</p>
              <p>RPG</p>
              <p>Simulação</p>
            </div>
        </div>
       
        <div id="container_slide">
          <div class="descricaoSlider">
            <h1 style="color: #fff;">Recomendados e em destaque</h1>
        </div>
                     
               <div >    
                <span class="material-symbols-outlined " id="right">
            chevron_right
            </span>
            <span class="material-symbols-outlined " id="left">
              chevron_left
              </span>
               </div>
          
              
      
            <div class="container-img">
              <div class="slide primeiro">
                <img src="assets/imagens/imagens slider/maxresdefault.webp" alt="" class="img_slide">
                <div class="descricao_item">
                  <h1 style="color: #fff;">Red Dead Redemption 2</h1>
                  <p style="padding-top: 9px; color: #fff; font-size: 14px;">Estados Unidos, 1899. O fim da era do velho oeste se aproxima, e os xerifes caçam as últimas gangues fora da lei. Quem não se rende ou sucumbe, acaba morto. Depois de tudo dar errado em um roubo na cidade de Blackwater, no faroeste, Arthur Morgan e a gangue Van der Linde são obrigados a fugir.</p>
              </div>
              </div> 
        </div>

        <div class="container-img">
            <div class="slide ">
              <img src="assets/imagens/imagens slider/ghost-of-tsushima-god-of-war-armor.jpg" alt="" class="img_slide">
              <div class="descricao_item">
                <h1 style="color: #fff;">GOD OF WAR 2018</h1>
                <p style="padding-top: 9px; color: #fff; font-size: 14px">O jogo começa após a morte da segunda esposa de Kratos e mãe de Atreus, Faye. Seu último desejo era que suas cinzas fossem espalhadas no pico mais alto dos nove reinos nórdicos. Antes de iniciar sua jornada, Kratos é confrontado por um homem misterioso com poderes divinos.</p>
            </div>
            </div>
           
      </div>

      <div class="container-img">
        <div class="slide ">
          <img src="assets/imagens/imagens slider/zelda-featured-trailer.jpg" alt="" class="img_slide">
          <div class="descricao_item">
            <h1 style="color: #fff;">zelda tears of the kingdom</h1>
            <p style="padding-top: 9px; color: #fff; font-size: 14px">The Legend of Zelda: Tears of the Kingdom começa com Link e Zelda investigando uma praga misteriosa que parece ter sua fonte no subterrâneo do castelo de Hyrule. No final da caverna, a dupla encontra a múmia do maior vilão da série: Ganondorf.</p>
        </div>
        </div>
       
  </div>


  <div class="container-img">
    <div class="slide ">
      <img src="assets/imagens/imagens slider/HORIZON-FORBIDDEN-WEST.jpg" alt="" class="img_slide">
      <div class="descricao_item">
        <h1 style="color: #fff;">Horizon Forbidden West</h1>
        <p style="padding-top: 9px; color: #fff; font-size: 14px">O título continua a história de Aloy, uma caçadora que vive em um mundo pós-apocalíptico dominado por máquinas. Ela terá que enfrentar novos perigos e mistérios em uma jornada épica pelas terras proibidas, um vasto território que se estende desde o que era Utah até o Oceano Pacífico.</p>
    </div>
    </div>
   
</div>

<div class="container-img">
  <div class="slide ">
    <img src="assets/imagens/imagens slider/Resident-Evil-4-Remake-ultrapassa-5-milhoes-de-copias-vendidas.jpg" alt="" class="img_slide">
    <div class="descricao_item">
      <h1 style="color: #fff;">Resident Evil 4 Remake</h1>
      <p style="padding-top: 9px; color: #fff; font-size: 14px">Seis anos após os eventos de Resident Evil 2, Leon Kennedy, sobrevivente de Raccoon City, foi enviado a um vilarejo isolado na Europa para investigar o desaparecimento da filha do presidente dos Estados Unidos. O que ele descobre lá é diferente de tudo o que ele já enfrentou antes.</p>
  </div>
  </div>
 
</div>

<div class="container-img">
  <div class="slide ">
    <img src="assets/imagens/imagens slider/78683d87f12356c571e4541b2ef649e3bd608285139704087c552171f715e399.jpg" alt="" class="img_slide">
    <div class="descricao_item">
      <h1 style="color: #fff;">Mario Kart 8</h1>
      <p style="padding-top: 9px; color: #fff; font-size: 14px">Queime o asfalto nas pistas do Reino Cogumelo! Debaixo d'água, no céu, no espaço ou de cabeça para baixo a caminho da vitória! Acelere no modo multijogador local*, em torneios online**, no modo de batalha renovado e muito mais!</p>
  </div>
  </div>
 
</div>

<div class="container-img">
  <div class="slide ">
    <img src="assets/imagens/imagens slider/starfield-lleoslima.png" alt="" class="img_slide">
    <div class="descricao_item">
      <h1 style="color: #fff;">Starfield</h1>
      <p style="padding-top: 9px; color: #fff; font-size: 14px">A história de Starfield se passa no ano de 2330, quando as viagens espaciais já eram uma realidade para os humanos, o que fez esvaziar a Terra. Apesar disso, é em meio à exploração de uma mina que a trama tem início. O título tem como personagem principal um trabalhador da Argos Extractors.</p>
  </div>
  </div>
 
</div>

      <div class="manual_input">
        <input type="radio" name="btn-radio" id="radio1" class="input" >
        <input type="radio" name="btn-radio" id="radio2" class="input">
        <input type="radio" name="btn-radio" id="radio3" class="input">
        <input type="radio" name="btn-radio" id="radio4" class="input">
        <input type="radio" name="btn-radio" id="radio5" class="input">
        <input type="radio" name="btn-radio" id="radio6" class="input">
        <input type="radio" name="btn-radio" id="radio7" class="input">
       </div>

       <div class="navigation_auto">
            <div class="auto_btn1"></div>
            <div class="auto_btn2"></div>
            <div class="auto_btn3"></div>
            <div class="auto_btn4"></div>
       </div>

       <div class="manual_navigation">
        <label for="radio1" class="manual_btn"></label>
        <label for="radio2" class="manual_btn"></label>
        <label for="radio3" class="manual_btn"></label>
        <label for="radio4" class="manual_btn"></label>
       </div>
      </div>
     </section>

      <section id="area 3" style="margin-top: 100px; margin-bottom: 50px; overflow: hidden;">
        <h1 class="h1_promocao">Promoções</h1>
              <div class="slides_container">
                <?php foreach ($jogos_promocao as $jogo): ?>
                <div class="slide_promocao">
                  <a href="./assets/Pages/Detalhes/detalhes_jogo.php?id=<?php echo $jogo['id']; ?>"> 
                    <img src="<?php echo htmlspecialchars($jogo['imagem']); ?>" alt="<?php echo htmlspecialchars($jogo['nome']); ?>">
                  </a>
                  <div class="promocao_descricao">
                         <h2 style="font-size: 18px; color: #fff;"><?php echo htmlspecialchars($jogo['nome']); ?></h2>
                         <p style="color: #ccc;"><?php echo !empty($jogo['genero']) ? htmlspecialchars($jogo['genero']) : 'Jogo Base'; ?></p>
                         <p style="color: #fff;">Preço R$: <strong style="background-color: cornflowerblue; padding: 5px; border-radius: 5px;"><?php echo number_format($jogo['preco'], 2, ',', '.'); ?></strong></p>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
      </section>

     <section style="margin-bottom: 50px;" id="area4">
         <h1 class="h1_fretoplay">Jogos Free To Play</h1>

         <div class="slides_container">
          <div class="slide_promocao">
            <img src="assets/imagens/imagens free to play/HTB1R9DuXzzuK1Rjy0Fpq6yEpFXaD.jpg_640x640Q90.jpg_.webp" alt="">
            <div class="promocao_descricao">
                   <h2 style="font-size: 20px; color: #fff; ">PUBG Playerunknowns Battlegrounds</h2>
                   <p style="color: greenyellow; padding: 5px 0px;" >Free To Play</p>
            </div>
          </div>

          <div class="slide_promocao">
            <img src="assets/imagens/imagens free to play/635238435dc762094c218093-genshin-impact-poster-gaming-poster.jpg" alt="">
            <div class="promocao_descricao">
                   <h2 style="font-size: 20px; color: #fff; ">Genshin Inpact</h2>
                   <p style="color: greenyellow; padding: 5px 0px;">Free To Play</p>
            </div>
          </div>

          <div class="slide_promocao">
            <img src="assets/imagens/imagens free to play/71KAkuPPSJS._AC_SL1500_.jpg" alt="">
            <div class="promocao_descricao">
                   <h2 style="font-size: 20px; color: #fff; ">Fortnite</h2>
                   <p style="color: greenyellow; padding: 5px 0px;">Free To Play</p>
            </div>
          </div>

          <div class="slide_promocao">
            <img src="assets/imagens/imagens free to play/MV5BNzAxZTNlNTktNjRmMy00OTg1LTlkODUtYjQwODJhN2UzYzFjXkEyXkFqcGdeQXVyMTA0MTM5NjI2._V1_.jpg" alt="">
            <div class="promocao_descricao">
                   <h2 style="font-size: 20px; color: #fff; ">Call Of Duty War Zone</h2>
                   <p style="color: greenyellow; padding: 5px 0px;">Free To Play</p>
            </div>
          </div>

          <div class="slide_promocao">
            <img src="assets/imagens/imagens free to play/710I2Vgqs5L._AC_UF894,1000_QL80_DpWeblab_.jpg" alt="">
            <div class="promocao_descricao">
                   <h2 style="font-size: 20px; color: #fff; ">The Sims 4</h2>
                   <p style="color: greenyellow; padding: 5px 0px;">Free To Play</p>
            </div>
          </div>

          <div class="slide_promocao">
            <img src="assets/imagens/imagens free to play/EGS_FallGuys_Mediatonic_S2_1200x1600-6ea0c038d654d7b6dc06bf86a1522f21.jpg" alt="">
            <div class="promocao_descricao">
                   <h2 style="font-size: 20px; color: #fff; ">Fall Guys</h2>
                   <p style="color: greenyellow; padding: 5px 0px;">Free To Play</p>
            </div>
          </div>

          <div class="slide_promocao">
            <img src="assets/imagens/imagens free to play/61U-wBBtdBL._AC_UF894,1000_QL80_.jpg" alt="">
            <div class="promocao_descricao">
                   <h2 style="font-size: 20px; color: #fff; ">Rocket League</h2>
                   <p style="color: greenyellow; padding: 5px 0px;">Free To Play</p>
            </div>
          </div>

          <div class="slide_promocao">
            <img src="assets/imagens/imagens free to play/0a0feffdf3bb91897a691d3ba6c902fa.jpg" alt="">
            <div class="promocao_descricao">
                   <h2 style="font-size: 20px; color: #fff; ">Rogue Company</h2>
                   <p style="color: greenyellow; padding: 5px 0px;">Free To Play</p>
            </div>
          </div>

          <div class="slide_promocao">
            <img src="assets/imagens/imagens free to play/shop-titans-1pr0c.png" alt="">
            <div class="promocao_descricao">
                   <h2 style="font-size: 20px; color: #fff; ">Shop Titans</h2>
                   <p style="color: greenyellow; padding: 5px 0px;">Free To Play</p>
            </div>
          </div>

          <div class="slide_promocao">
            <img src="assets/imagens/imagens free to play/honkai_impact_3rd_poster_design_by_nnrarchive_dewtwd3-fullview.jpg" alt="">
            <div class="promocao_descricao">
                   <h2 style="font-size: 20px; color: #fff; ">Honkai Inpact 3rd</h2>
                   <p style="color: greenyellow; padding: 5px 0px;">Free To Play</p>
            </div>
          </div>

          <div class="slide_promocao">
            <img src="assets/imagens/imagens free to play/karos-7md97.png" alt="">
            <div class="promocao_descricao">
                   <h2 style="font-size: 20px; color: #fff; ">Karos</h2>
                   <p style="color: greenyellow; padding: 5px 0px;">Free To Play</p>
            </div>
          </div>

          <div class="slide_promocao">
            <img src="assets/imagens/imagens free to play/bae3d3128890551.615f3847aa8aa.png" alt="">
            <div class="promocao_descricao">
                   <h2 style="font-size: 20px; color: #fff; ">Grande Chase</h2>
                   <p style="color: greenyellow; padding: 5px 0px;">Free To Play</p>
            </div>
          </div>

          <div class="slide_promocao">
            <img src="assets/imagens/imagens free to play/MV5BNTQzZmQ3OTYtNWU3NS00YmQzLTk1Y2YtODA2NTYyZDJmNDM0XkEyXkFqcGdeQXVyNjU3NzY4MTU@._V1_.jpg" alt="">
            <div class="promocao_descricao">
                   <h2 style="font-size: 20px; color: #fff; ">Armored Warfare</h2>
                   <p style="color: greenyellow; padding: 5px 0px;">Free To Play</p>
            </div>
          </div>

          <div class="slide_promocao">
            <img src="assets/imagens/imagens free to play/FaiFmUFUEAA-as4.jpg" alt="">
            <div class="promocao_descricao">
                   <h2 style="font-size: 20px; color: #fff; ">Century: Age Of Ashes</h2>
                   <p style="color: greenyellow; padding: 5px 0px;">Free To Play</p>
            </div>
          </div>
         </div>
     </section>

     <section id="are5" style="margin-bottom: 50px;">
      <div class="jogos-section">
        <h2 style="color: #ccc;">Mais Vendidos</h2>
        <div class="jogos-list">
           <div class="jogo">
            <img src="assets/imagens/mais vendidos/big-poster-gamer-red-dead-redemption-2-lo01-tamanho-90x60-cm-poster-de-games.jpg" alt="" >
           </div>
           <div class="detalhesJogados">
            <h3 style="color: #fff;">Red Dead Redemption 2</h3>
            <p style="color: #fff; padding-top: 20px;">Preço R$: <strong style="background-color: cornflowerblue; padding: 5px; border-radius: 5px;">299,90</strong></p>
           </div>
        </div>

        <div class="jogos-list">
          <div class="jogo">
           <img src="assets/imagens/mais vendidos/6000701g.jpg" alt="" >
          </div>
          <div class="detalhesJogados">
           <h3 style="color: #fff;">FC 24</h3>
           <p style="color: #fff; padding-top: 20px;">Preço R$: <strong style="background-color: cornflowerblue; padding: 5px; border-radius: 5px;">359,90</strong></p>
          </div>
       </div>

       <div class="jogos-list">
        <div class="jogo">
         <img src="assets/imagens/mais vendidos/aa16d9bcae36d800859345d02b5921fd.jpg" alt="" >
        </div>
        <div class="detalhesJogados">
         <h3 style="color: #fff;">Grand Theft Auto V</h3>
         <p style="color: #fff; padding-top: 20px;">Preço R$: <strong style="background-color: cornflowerblue; padding: 5px; border-radius: 5px;">82,42</strong></p>
        </div>
     </div>

     <div class="jogos-list">
      <div class="jogo">
       <img src="assets/imagens/mais vendidos/mL3882_1024x1024.jpg" alt="" >
      </div>
      <div class="detalhesJogados">
       <h3 style="color: #fff;">Star Wars Jedi Fallen Order</h3>
       <p style="color: #fff; padding-top: 20px;">Preço R$: <strong style="background-color: cornflowerblue; padding: 5px; border-radius: 5px;">199,00</strong></p>
      </div>
   </div>

   <div class="jogos-list">
    <div class="jogo">
     <img src="assets/imagens/mais vendidos/s-l1200.webp" alt="" >
    </div>
    <div class="detalhesJogados">
     <h3 style="color: #fff;">Elden Ring</h3>
     <p style="color: #fff; padding-top: 20px;">Preço R$: <strong style="background-color: cornflowerblue; padding: 5px; border-radius: 5px;">229,90</strong></p>
    </div>
 </div>
      </div>

      <div class="jogos-section" style="padding-left: 210px;">
        <h2 style="color: #ccc;">Mais Jogados</h2>
        <div class="jogos-list">
          <div class="jogo">
           <img src="assets/imagens/imagens free to play/71KAkuPPSJS._AC_SL1500_.jpg" alt="" >
          </div>
          <div class="detalhesJogados">
           <h3 style="color: #fff;">Fortnite</h3>
           <p style="color: #fff; padding-top: 20px;"><strong style="background-color: cornflowerblue; padding: 5px; border-radius: 5px;">Gratuito</strong></p>
          </div>
        </div>

        <div class="jogos-list">
          <div class="jogo">
           <img src="assets/imagens/mais vendidos/aa16d9bcae36d800859345d02b5921fd.jpg" alt="" >
          </div>
          <div class="detalhesJogados">
           <h3 style="color: #fff;">Grand Theft Auto V</h3>
           <p style="color: #fff; padding-top: 20px;">Preço R$: <strong style="background-color: cornflowerblue; padding: 5px; border-radius: 5px;">82,42</strong></p>
          </div>
        </div>

        <div class="jogos-list">
          <div class="jogo">
           <img src="assets/imagens/mais jogados/Sda9f41ce823c44b591164a2356cb36c0u.jpg_640x640Q90.jpg_.webp" alt="" >
          </div>
          <div class="detalhesJogados">
           <h3 style="color: #fff;">Dota 2</h3>
           <p style="color: #fff; padding-top: 20px;"><strong style="background-color: cornflowerblue; padding: 5px; border-radius: 5px;">Gratuito</strong></p>
          </div>
        </div>

        <div class="jogos-list">
          <div class="jogo">
           <img src="assets/imagens/mais jogados/co49r0.jpg" alt="" >
          </div>
          <div class="detalhesJogados">
           <h3 style="color: #fff;">7 Days To Die</h3>
           <p style="color: #fff; padding-top: 20px;">Preço R$: <strong style="background-color: cornflowerblue; padding: 5px; border-radius: 5px;">44,99</strong></p>
          </div>
        </div>

        <div class="jogos-list">
          <div class="jogo">
           <img src="assets/imagens/mais jogados/big-poster-gamer-monster-hunter-lo005-tamanho-90x60-cm-game.jpg" alt="" >
          </div>
          <div class="detalhesJogados">
           <h3 style="color: #fff;">Moster Hunter World</h3>
           <p style="color: #fff; padding-top: 20px;">Preço R$: <strong style="background-color: cornflowerblue; padding: 5px; border-radius: 5px;">99,90</strong></p>
          </div>
        </div>
      </div>

      <div class="jogos-section" style="padding-left: 210px;">
        <h2 style="color: #ccc;">Em Breve</h2>
        <div class="jogos-list">
          <div class="jogo">
           <img src="assets/imagens/mais jogados/black-myth-wukong-1cm8h.png" alt="" >
          </div>
          <div class="detalhesJogados">
           <h3 style="color: #fff;">Black Myth: Wukong</h3>
           <p style="color: #fff; padding-top: 20px;"><strong style="background-color: cornflowerblue; padding: 5px; border-radius: 5px;">Disponivel em 20/08/2024</strong></p>
          </div>
        </div>

        <div class="jogos-list">
          <div class="jogo">
           <img src="assets/imagens/mais jogados/EGS_Frostpunk2_11bitstudios_S2_1200x1600-8b452490754cb4a4fe1a983c533cfb5d.jpg" alt="" >
          </div>
          <div class="detalhesJogados">
           <h3 style="color: #fff;"> Frostpunk 2</h3>
           <p style="color: #fff; padding-top: 20px;"><strong style="background-color: cornflowerblue; padding: 5px; border-radius: 5px;">Disponivel em 25/07/2024</strong></p>
          </div>
        </div>

        <div class="jogos-list">
          <div class="jogo">
           <img src="assets/imagens/mais jogados/wuthering-waves-uw6vy.jpg" alt="" >
          </div>
          <div class="detalhesJogados">
           <h3 style="color: #fff;">Wuthering Waves</h3>
           <p style="color: #fff; padding-top: 20px;"><strong style="background-color: cornflowerblue; padding: 5px; border-radius: 5px;">Disponivel em 2024</strong></p>
          </div>
        </div>

        <div class="jogos-list">
          <div class="jogo">
           <img src="assets/imagens/mais jogados/zenless-zone-zero-uhlki.jpg" alt="" >
          </div>
          <div class="detalhesJogados">
           <h3 style="color: #fff;">Zenless Zone Zero</h3>
           <p style="color: #fff; padding-top: 20px;"><strong style="background-color: cornflowerblue; padding: 5px; border-radius: 5px;">Em Breve</strong></p>
          </div>
        </div>

        <div class="jogos-list">
          <div class="jogo">
           <img src="assets/imagens/mais jogados/etmqmxuxgm1b1.jpg" alt="" >
          </div>
          <div class="detalhesJogados">
           <h3 style="color: #fff;">Hades 2</h3>
           <p style="color: #fff; padding-top: 20px;"><strong style="background-color: cornflowerblue; padding: 5px; border-radius: 5px;">Em Breve</strong></p>
          </div>
        </div>
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
                  <div style="display: inline-block;  border: 1px solid #fff; height: 15px; margin-left: 15px; margin-right: 15px;"></div>
                  <div class="ul_final"><p>Apoio</p></div>
                  <div style="display: inline-block;  border: 1px solid #fff; height: 15px; margin-left: 15px; margin-right: 15px;"></div>
                  <div class="ul_final"><p>SteamVerdeworks</p></div>
                  <div style="display: inline-block;  border: 1px solid #fff; height: 15px; margin-left: 15px; margin-right: 15px;"></div>
                  <div class="ul_final"><p>Cartões-Presente</p></div>
                 </div>
     </footer>

      <script src="assets/js/js.js"></script>

<script>
  const slides = document.querySelectorAll('.container-img .slide')
  const radioButtons = document.querySelectorAll('.manual_input input[type="radio"]');
  
  let currentIndex = 0

  showSlide(currentIndex)

  const interval = setInterval(() =>{
    currentIndex = (currentIndex + 1) % slides.length
    showSlide(currentIndex)
  }, 5000)
 
  radioButtons.forEach((radio, index) =>{
    radio.addEventListener('click', () =>{
      currentIndex = index
      showSlide(currentIndex)
    })
  })

    document.getElementById('left').addEventListener('click', () => {
      clearInterval(interval)
      currentIndex = (currentIndex - 1 + slides.length) % slides.length
      showSlide(currentIndex)
    })

    document.getElementById('right').addEventListener('click', () =>{
      clearInterval(interval)
      currentIndex = (currentIndex + 1) % slides.length
      showSlide(currentIndex)
    })
 
  function showSlide(index){
    slides.forEach((slide, i) =>{
      if(i === index){
        radioButtons[i].checked = true; // Marcar o radio button correspondente
        slide.style.display = 'block';
        slide.classList.add('active')
      }else{
        slide.style.display = 'none'
        slide.classList.remove('active')
      }
    })
  }
</script>
</body>
</html>