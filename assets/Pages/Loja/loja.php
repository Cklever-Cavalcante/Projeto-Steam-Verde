<?php
session_start();

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

// Incluir arquivo de conexão
require_once '../../database/conexao.php';

// Buscar todos os jogos do banco de dados
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
    <title>Loja</title>
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
            <?php if (isset($_SESSION['usuario_papel']) && $_SESSION['usuario_papel'] === 'admin'): ?>
            <a href="../Admin/admin_usuarios.php"><li>Área Administrativa</li></a>
            <?php endif; ?>
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

      <section id="area1_promoção">
        <div class="content">

        
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
      <input type="text" name="" id=""  placeholder="Pesquisar..." class="input_pesquisa2">
      <button   class="button_pesquisa">
        <span class="material-symbols-outlined" style="color: cornflowerblue;">
          <span class="material-symbols-outlined">
            search
            </span>
          </button>
          
  
        </div>

        
    </div>
    <video src="https://shared.akamai.steamstatic.com/store_item_assets/steam/clusters/sale_summer2024/36a01fe4331ab0ca600ff205/webm_page_bg_brazilian.webm?t=1719599088"
          autoplay
          loop
          style="z-index:  -1; position: absolute;  width: 99%;"
          ></video>
       
    
    </section>

    <section id="promocao_area2">
                                  
        <div style="margin: 50px;">
            <h1 style="text-align: center; color: cornflowerblue;">Mais Vendidos</h1>

        </div>
                          
         <div style="display: flex; justify-content: center; align-items: center; gap: 1.1rem; flex-flow: wrap;">
         
         <?php foreach ($jogos as $jogo): ?>
          <div class="container_img2">
            <a href="../Detalhes/detalhes_jogo.php?id=<?php echo $jogo['id']; ?>" style="text-decoration: none;">
              <img src="../../../<?php echo htmlspecialchars($jogo['imagem']); ?>" alt="<?php echo htmlspecialchars($jogo['nome']); ?>">
              <div class="ctt">
                <h1 style="color: #fff; text-align: center; font-size: 17px;"><?php echo htmlspecialchars($jogo['nome']); ?></h1>
                <p style="color: greenyellow; text-align: center;">R$ <?php echo number_format($jogo['preco'], 2, ',', '.'); ?></p>
              </div>
            </a>
          </div>
         <?php endforeach; ?>
         
         </div>



         </div>


    </section>


    <section id="promocao_area3">

        <div class="promo_are3">
              <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; width: 97%; ">
                <h1 style="color: rgb(158, 16, 16); padding-left: 27px;">Grandes descontos em destaque</h1>
                <h2 style=" background: #fff; padding: 5px; border-radius: 15px; font-size: 20px; text-align: center; padding-right: 10px;">VER TUDO</h2>

              </div>

              <div style="display: flex; justify-content: center;  gap: 1rem; flex-flow: wrap;">
                <div  class="promo-area33">
            <img src="../../imagens/promocao/the-witcher-the-witcher-3-wild-hunt-video-games-witcher-wallpaper.jpg" alt="">
            <div class="promo3-descricao">
                <h1><strong style="background: greenyellow; color: black; margin: 5px; border-radius: 5px; padding: 2px; font-size: 19px;">-90%</strong>R$ 12,99</h1>
            </div>
           
       </div>

       <div  class="promo-area33">
        <img src="../../imagens/promocao/capsule_616x353.jpg" alt="">
        <div class="promo3-descricao">
            <h1><strong style="background: greenyellow; color: black; margin: 5px; border-radius: 5px; padding: 2px; font-size: 19px;">-90%</strong>R$ 24,90</h1>
        </div>
   </div>

   <div  class="promo-area33">
    <img src="../../imagens/promocao/b7b9fe80-c283-46df-bfe3-3c826a1f78a3Cover-600.webp" alt="">
    <div class="promo3-descricao">
        <h1><strong style="background: greenyellow; color: black; margin: 5px; border-radius: 5px; padding: 2px; font-size: 19px;">-90%</strong>R$ 14,99</h1>
    </div>
</div>

<div  class="promo-area33">
    <img src="../../imagens/promocao/blodtd6(1).jpg" alt="">
    <div class="promo3-descricao">
        <h1><strong style="background: greenyellow; color: black; margin: 5px; border-radius: 5px; padding: 2px; font-size: 19px;">-90%</strong>R$ 2,78</h1>
    </div>
</div>

<div  class="promo-area33">
    <img src="../../imagens/promocao/HK_Landscape_2560x1440-0233eae757a0e921c879b7be3a697d97.jpg" alt="">
    <div class="promo3-descricao">
        <h1><strong style="background: greenyellow; color: black; margin: 5px; border-radius: 5px; padding: 2px; font-size: 19px;">-90%</strong>R$ 15,90</h1>
    </div>
</div>

<div  class="promo-area33">
    <img src="../../imagens/promocao/GRT_STD_Edition_Store_Landscape_2560x1440_2560x1440-eb4ed54b646a5c0d98823bcb91807011.jpg" alt="">
    <div class="promo3-descricao">
        <h1><strong style="background: greenyellow; color: black; margin: 5px; border-radius: 5px; padding: 2px; font-size: 19px;">-90%</strong>R$ 17,90</h1>
    </div>
</div>

<div  class="promo-area33">
  <img src="../../imagens/promocao/blob_c978.jpg" alt="">
  <div class="promo3-descricao">
      <h1><strong style="background: greenyellow; color: black; margin: 5px; border-radius: 5px; padding: 2px; font-size: 19px;">-95%</strong>R$ 11,49</h1>
  </div>
</div>

<div  class="promo-area33">
  <img src="../../imagens/promocao/EGS_SidMeiersCivilizationVI_FiraxisGames_S1-2560x1440-2fcd1c150ac6d8cdc672ae042d2dd179.jpg" alt="">
  <div class="promo3-descricao">
      <h1><strong style="background: greenyellow; color: black; margin: 5px; border-radius: 5px; padding: 2px; font-size: 19px;">-95%</strong>R$ 6,45</h1>
  </div>
</div>

<div  class="promo-area33">
  <img src="../../imagens/promocao/bfv-web-1920x1080-definitiveedition.jpg.adapt.crop16x9.575p.jpg" alt="">
  <div class="promo3-descricao">
      <h1><strong style="background: greenyellow; color: black; margin: 5px; border-radius: 5px; padding: 2px; font-size: 19px;">-95%</strong>R$ 16,03</h1>
  </div>
</div>

<div  class="promo-area33">
  <img src="../../imagens/promocao/celeste.avif" alt="">
  <div class="promo3-descricao">
      <h1><strong style="background: greenyellow; color: black; margin: 5px; border-radius: 5px; padding: 2px; font-size: 19px;">-95%</strong>R$ 5,99</h1>
  </div>
</div>

   

        </div>
              
    </div>
       

    </section>


    <section id="loja_area3">
                 <div style="margin: 50px;">
                   <h1 style="color: cornflowerblue; text-align: center;">Séries em Destaques</h1>
                 </div>

      <div class="slider_loja">
              
        
              <div class="slider">
                   <img src="../../imagens/loja2/big-poster-gamer-assassins-creed-odyssey-lo09-tam-90x60-cm-geek.jpg" alt="">
                           <div  class="descricao-sli-loja">
                              <h1 style="color: #fff;">Assassin's Creed Séries</h1>
                           </div>
              </div>

              <div class="slider">
                <img src="../../imagens/loja2/BiS5QP6h4506JHyJlZlVzK9D.webp" alt="">
                        <div  class="descricao-sli-loja">
                           <h1 style="color: #fff;">Resident Evil Séries</h1>
                        </div>
           </div>


           <div class="slider">
            <img src="../../imagens/loja2/7e8f5248-2afc-48f8-912b-a6fe1303134e.avif" alt="">
                    <div  class="descricao-sli-loja">
                       <h1 style="color: #fff;">Dragon Ball Z Séries</h1>
                    </div>
       </div>

       <div class="slider">
        <img src="../../imagens/loja2/capsule_616x353.jpg" alt="">
                <div  class="descricao-sli-loja">
                   <h1 style="color: #fff;">Naruto Storm Séries</h1>
                </div>
   </div>

   <div class="slider">
    <img src="../../imagens/loja2/capsule_616x353 (1).jpg" alt="">
            <div  class="descricao-sli-loja">
               <h1 style="color: #fff;">The Evil Within Séries</h1>
            </div>

            
</div>    

<div class="slider">
  <img src="../../imagens/loja2/halo-infinite_hgkc.jpg" alt="">
          <div  class="descricao-sli-loja">
             <h1 style="color: #fff;">Halo Séries</h1>
          </div>

          
</div>    


<div class="slider">
  <img src="../../imagens/loja2/boordelands.avif" alt="">
          <div  class="descricao-sli-loja">
             <h1 style="color: #fff;">Boordelands Séries</h1>
          </div>

          
</div>    


<div class="slider">
  <img src="../../imagens/loja2/HITMAN-2-1.jpg" alt="">
          <div  class="descricao-sli-loja">
             <h1 style="color: #fff;">Hitman Séries</h1>
          </div>

          
</div>  


      </div>


    </section>


    <footer >
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

    

    <script src="../../js/js.js"></script>
</body>
</html>