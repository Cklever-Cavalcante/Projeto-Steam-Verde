-- Criação das tabelas para o sistema de usuários e carrinho de compras

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    papel VARCHAR(20) DEFAULT 'usuario' NOT NULL, -- Adicionado campo papel para distinguir admin/usuario
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de jogos
CREATE TABLE IF NOT EXISTS jogos (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    imagem VARCHAR(255),
    desenvolvedor VARCHAR(100),
    editora VARCHAR(100),
    data_lancamento DATE,
    plataforma VARCHAR(50),
    genero VARCHAR(50) -- Adicionado campo gênero para filtros
);

-- Tabela de carrinho
CREATE TABLE IF NOT EXISTS carrinho (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT(11) NOT NULL,
    jogo_id INT(11) NOT NULL,
    data_adicao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (jogo_id) REFERENCES jogos(id) ON DELETE CASCADE
);

-- Inserir alguns jogos de exemplo
INSERT INTO jogos (nome, descricao, preco, imagem, desenvolvedor, editora, data_lancamento, plataforma, genero) VALUES
('Red Dead Redemption 2', 'Vencedor de mais de 175 prêmios de Jogo do Ano e avaliado com mais de 250 notas máximas, Red Dead Redemption 2 é uma história épica de honra e lealdade no alvorecer dos tempos modernos.', 299.90, 'assets/imagens/promocao/71xnh5WM+GL._AC_SL1200_.jpg', 'Rockstar Games', 'Rockstar Games', '2019-11-05', 'Windows', 'Ação/Aventura'),
('Ghost of Tsushima', 'No final do século XIII, o império mongol devastou nações inteiras durante sua campanha para conquistar o Oriente. A Ilha de Tsushima é tudo o que está entre o Japão continental e uma enorme frota invasora mongol.', 249.90, 'assets/imagens/promocao/s-l1600.jpg', 'Sucker Punch Productions', 'Sony Interactive Entertainment', '2020-07-17', 'Windows', 'Ação/Aventura'),
('Horizon Forbidden West', 'Junte-se a Aloy enquanto ela enfrenta um mundo de novas ameaças e descobre segredos enterrados do passado que ameaçam destruir a vida na Terra.', 299.90, 'assets/imagens/promocao/forbiden.png', 'Guerrilla Games', 'Sony Interactive Entertainment', '2022-02-18', 'Windows', 'RPG'),
('The Last Of Us Part 1', 'Em uma civilização devastada, onde infectados e sobreviventes veteranos estão à solta, Joel, um protagonista amargurado, é contratado para tirar uma garota de 14 anos, Ellie, de uma zona de quarentena militar.', 249.90, 'assets/imagens/promocao/aZKLRcjaZ8HL03ODxYMZDfaH.webp', 'Naughty Dog', 'Sony Interactive Entertainment', '2022-09-02', 'Windows', 'Ação/Aventura'),
('Black Myth: Wukong', 'Black Myth: Wukong é um jogo de ação e RPG baseado na Jornada ao Oeste, uma das quatro grandes obras clássicas da literatura chinesa.', 349.90, 'assets/imagens/promocao/black_myth_wukong_de.jpg', 'Game Science', 'Game Science', '2024-08-20', 'Windows', 'RPG'),
('Resident Evil Village', 'Ambientado alguns anos após os eventos de Resident Evil 7, Ethan Winters e sua esposa Mia vivem pacificamente em um novo local, livres dos pesadelos do passado. Mas quando a tragédia os atinge novamente, Ethan deve enfrentar um novo pesadelo.', 139.90, 'assets/imagens/imagens prmoção/flat,750x,075,f-pad,750x1000,f8f8f8.u3.jpg', 'Capcom', 'Capcom', '2021-05-07', 'Windows', 'Terror/Ação'),
('The Last Of Us Parte 1', 'Em uma civilização devastada, onde infectados e sobreviventes veteranos estão à solta, Joel, um protagonista amargurado, é contratado para tirar uma garota de 14 anos, Ellie, de uma zona de quarentena militar.', 249.90, 'assets/imagens/imagens prmoção/the-last-of-us-remastered-us-poster_a3-ssay764s1v.jpg', 'Naughty Dog', 'Sony Interactive Entertainment', '2022-09-02', 'Windows', 'Ação/Aventura'),
('Assassins Creed Mirage', 'Experimente a história de Basim, um astuto ladrão de rua com visões de pesadelo, que busca respostas e justiça enquanto navega pelas ruas agitadas da Bagdá do século IX.', 249.99, 'assets/imagens/imagens prmoção/61wgujVHwNL._AC_UF894,1000_QL80_DpWeblab_.jpg', 'Ubisoft', 'Ubisoft', '2023-10-05', 'Windows', 'Ação/Aventura'),
('Assassins Creed Valhalla', 'Torne-se Eivor, um lendário guerreiro viking em busca de glória. Explore um mundo aberto dinâmico ambientado contra o brutal pano de fundo da Inglaterra na Era Viking.', 199.99, 'assets/imagens/imagens prmoção/81PYKLkWWLL._AC_UF894,1000_QL80_.jpg', 'Ubisoft', 'Ubisoft', '2020-11-10', 'Windows', 'Ação/RPG'),
('Watch Dogs Legion', 'Construa uma resistência para reclamar uma Londres futurista que está à beira do colapso. Recrutando qualquer pessoa que você vê na rua, cada personagem tem uma história, personalidade e conjunto de habilidades únicas.', 274.99, 'assets/imagens/imagens prmoção/poster-watch-dogs-legion-b-8905b319.jpg', 'Ubisoft', 'Ubisoft', '2020-10-29', 'Windows', 'Ação/Aventura'),
('One Piece Pirate Warriors 4', 'A série PIRATE WARRIORS traz uma nova experiência com ambientes, movimentos e habilidades mais realistas do que nunca! Siga Luffy e os Chapéus de Palha desde o início, enquanto eles vivem suas jornadas, com amigos e inimigos ao longo do caminho.', 154.90, 'assets/imagens/imagens prmoção/mL4258_1024x1024.jpg', 'Koei Tecmo', 'Bandai Namco', '2020-03-27', 'Windows', 'Ação/Aventura'),
('Resident Evil 2 Remake', 'Um vírus mortal toma conta dos residentes de Raccoon City em setembro de 1998, mergulhando a cidade no caos enquanto zumbis comedores de carne vagam pelas ruas em busca dos poucos sobreviventes restantes.', 169.90, 'assets/imagens/imagens prmoção/212614701d0ec94669f.jpg', 'Capcom', 'Capcom', '2019-01-25', 'Windows', 'Terror/Ação'),
('Resident Evil 3 Remake', 'Jill Valentine é uma das últimas sobreviventes em Raccoon City, que está sendo consumida pelo apocalipse zumbi. A cidade se tornou um pesadelo do qual apenas ela pode escapar, perseguida implacavelmente por uma nova arma biológica: Nemesis.', 139.90, 'assets/imagens/imagens prmoção/23879068447b60e5d2d.jpg', 'Capcom', 'Capcom', '2020-04-03', 'Windows', 'Terror/Ação'),
('Assassins Creed IV: Black Flag', 'É 1715. Piratas governam o Caribe e estabeleceram sua própria república sem lei. Entre esses foras da lei está um jovem capitão bravo e imprudente chamado Edward Kenway.', 119.99, 'assets/imagens/imagens prmoção/Assassin27sCreedIVBlackFlag.jpg', 'Ubisoft', 'Ubisoft', '2013-10-29', 'Windows', 'Ação/Aventura'),
('The Witcher 3', 'Você é Geralt de Rívia, mercenário caçador de monstros. Diante de você está um continente devastado pela guerra e infestado de monstros para você explorar à vontade. Seu contrato atual? Rastrear Ciri — a Criança da Profecia.', 159.99, 'assets/imagens/imagens prmoção/EGS_TheWitcher3WildHuntCompleteEdition_CDPROJEKTRED_S2_1200x1600-53a8fb2c0201cd8aea410f2a049aba3f.jpg', 'CD Projekt Red', 'CD Projekt', '2015-05-19', 'Windows', 'RPG'),
('NARUTO SHIPPUDEN: Ultimate Ninja STORM 4', 'Com o retorno do sistema de luta em equipe, prepare-se para mergulhar em batalhas mais profundas e emocionantes do que nunca, com gráficos incríveis que só a última geração pode oferecer.', 77.50, 'assets/imagens/s-l1200.jpg', 'CyberConnect2', 'Bandai Namco', '2016-02-04', 'Windows', 'Luta'),
('God Of War 2018', 'Com a vingança contra os deuses do Olimpo em um passado distante, Kratos agora vive como um homem no reino dos deuses e monstros nórdicos. É neste mundo duro e implacável que ele deve lutar para sobreviver e ensinar seu filho a fazer o mesmo.', 199.90, 'assets/imagens/imagens prmoção/61-87CAXS9L.jpg', 'Santa Monica Studio', 'Sony Interactive Entertainment', '2022-01-14', 'Windows', 'Ação/Aventura'),
('The Evil Within', 'Enquanto investiga a cena de um terrível assassinato em massa, o detetive Sebastian Castellanos e seus parceiros encontram uma força misteriosa e poderosa. Após ser emboscado e nocauteado, Sebastian acorda em um mundo deformado onde criaturas horríveis vagam entre os mortos.', 155.00, 'assets/imagens/imagens prmoção/0628EV_3.jpg', 'Tango Gameworks', 'Bethesda Softworks', '2014-10-14', 'Windows', 'Terror/Ação');

-- Inserir um usuário administrador para testes
INSERT INTO usuarios (nome, email, senha, papel) VALUES
('Administrador', 'admin@steamverde.com', '$2y$10$lOTtUob8.U6GqwdE41PxtO3itpUj4xyWhoNel7MtUEEQx.XuQQ2uq', 'admin'); -- Senha: admin123
