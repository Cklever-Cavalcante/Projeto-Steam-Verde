# Steam Verde - Sistema de Loja de Jogos

O Steam Verde é um mini-sistema web responsivo desenvolvido com PHP, PDO e Bootstrap, que simula uma loja de jogos online inspirada na plataforma Steam. O sistema conta com múltiplos CRUDs (Create, Read, Update, Delete) para gerenciamento de usuários, jogos e carrinho de compras.

## Funcionalidades

### 1. Sistema de Usuários
- **Cadastro de usuários**: Novos usuários podem se registrar fornecendo nome, email e senha
- **Login de usuários**: Autenticação segura com validação de credenciais
- **Perfil de usuário**: Visualização e edição de dados pessoais
- **Exclusão de conta**: Opção para usuários removerem suas contas

### 2. Catálogo de Jogos
- **Visualização de jogos**: Listagem de jogos disponíveis com imagens, descrições e preços
- **Detalhes do jogo**: Página individual para cada jogo com informações completas
- **Seção de promoções**: Exibição dinâmica de jogos em promoção
- **Categorização**: Jogos organizados por gêneros e tipos

### 3. Carrinho de Compras
- **Adicionar ao carrinho**: Funcionalidade para adicionar jogos ao carrinho
- **Visualizar carrinho**: Lista de jogos selecionados com preços
- **Remover do carrinho**: Opção para remover itens do carrinho

### 4. Área Administrativa
- **Gerenciamento de usuários**: Administradores podem visualizar, editar e excluir usuários
- **Gerenciamento de jogos**: Adicionar, editar e remover jogos do catálogo
- **Controle de acesso**: Área restrita apenas para usuários com perfil de administrador

## Requisitos do Sistema

- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Servidor web (Apache/XAMPP)
- Navegador web moderno

## Instalação e Configuração

### 1. Configuração do Ambiente

1. Instale o XAMPP (ou outro servidor que inclua PHP, MySQL e Apache)
2. Inicie os serviços Apache e MySQL no painel de controle do XAMPP

### 2. Configuração do Banco de Dados

1. Abra o navegador e acesse `http://localhost/phpmyadmin`
2. Crie um novo banco de dados chamado `steam_verde`
3. Selecione o banco de dados recém-criado
4. Vá na aba "Importar"
5. Clique em "Escolher arquivo" e selecione o arquivo `steam_verde.sql` localizado na pasta `assets/database/` do projeto
6. Clique em "Executar" para importar a estrutura e dados iniciais

### 3. Configuração do Projeto

1. Extraia os arquivos do projeto para a pasta `htdocs` do seu XAMPP (geralmente localizada em `C:\xampp\htdocs\`)
2. Verifique se o arquivo de conexão com o banco de dados (`assets/database/conexao.php`) está configurado corretamente com as credenciais do seu ambiente

## Como Acessar e Testar o Sistema

### Acessando o Sistema

1. Abra seu navegador e acesse `http://localhost/Projeto-steam-verde/`
2. A página inicial do Steam Verde será exibida com o catálogo de jogos e promoções

### Testando as Funcionalidades

#### Cadastro e Login

1. Clique em "Cadastro" no menu superior
2. Preencha o formulário com nome, email e senha
3. Após o cadastro, você será redirecionado para a página de login
4. Insira o email e senha cadastrados para acessar sua conta

#### Navegação no Catálogo

1. Na página inicial, explore as seções de jogos em promoção
2. Clique em "Loja" no menu superior para ver o catálogo completo
3. Clique em qualquer jogo para ver seus detalhes completos

#### Usando o Carrinho

1. Na página de detalhes de um jogo, clique em "Adicionar ao Carrinho"
2. Acesse seu carrinho clicando em "Carrinho" no menu superior
3. Visualize os itens adicionados e o valor total
4. Teste a remoção de itens do carrinho

#### Gerenciando seu Perfil

1. Clique em "Meu Perfil" no menu superior
2. Explore as abas "Meus Dados", "Editar Perfil" e "Excluir Conta"
3. Teste a edição de suas informações pessoais

#### Acessando a Área Administrativa

1. Faça login com uma conta de administrador:
   - Email: admin@steamverde.com
   - Senha: admin123
2. Após o login, você verá o link "Área Administrativa" no menu superior
3. Clique neste link para acessar o painel administrativo
4. Teste o gerenciamento de usuários:
   - Visualize a lista de usuários cadastrados
   - Edite informações de um usuário
   - Exclua um usuário (crie uma conta de teste para isso)
5. Teste o gerenciamento de jogos:
   - Visualize a lista de jogos cadastrados
   - Adicione um novo jogo
   - Edite informações de um jogo existente
   - Exclua um jogo

## Estrutura do Projeto

- `index.php`: Página inicial com promoções e destaques
- `assets/`: Pasta com recursos do sistema
  - `CSS/`: Arquivos de estilo
  - `database/`: Arquivos de conexão e SQL
  - `imagens/`: Imagens dos jogos e elementos da interface
  - `js/`: Scripts JavaScript
  - `Pages/`: Páginas do sistema
    - `Admin/`: Área administrativa
    - `Detalhes/`: Páginas de detalhes dos jogos
    - `Loja/`: Páginas da loja
    - `Usuario/`: Páginas relacionadas ao usuário

## Observações Importantes

- O sistema utiliza Bootstrap para garantir a responsividade em diferentes dispositivos
- As senhas são armazenadas com hash seguro no banco de dados
- O sistema inclui validações de formulários para garantir a integridade dos dados
- A área administrativa é protegida e só pode ser acessada por usuários com perfil de administrador

## Tecnologias Utilizadas

- PHP com PDO para conexão segura com o banco de dados
- MySQL para armazenamento de dados
- HTML5 e CSS3 para estrutura e estilo
- Bootstrap para design responsivo
- JavaScript para interatividade

## Créditos

Desenvolvido como projeto acadêmico para demonstração de habilidades em desenvolvimento web com PHP, PDO e Bootstrap.
