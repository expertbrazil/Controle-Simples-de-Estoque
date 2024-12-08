# Sistema de Controle de Estoque

Um sistema simples e eficiente para controle de estoque, desenvolvido com Laravel 10.

## Funcionalidades

- Gestão de Produtos
  - Cadastro com imagens
  - Controle de estoque
  - SKU e códigos de barras
  - Preços de venda e custo
  - Estoque mínimo

- Gestão de Categorias
  - Categorias e subcategorias
  - Status ativo/inativo
  - Organização hierárquica

- Vendas
  - PDV simples e intuitivo
  - Registro de vendas
  - Histórico de transações
  - Controle de estoque automático

## Requisitos

- PHP 8.1 ou superior
- Composer
- MySQL 5.7 ou superior
- Node.js e NPM (para assets)

## Instalação

1. Clone o repositório
```bash
git clone git@github.com:expertbrazil/Controle-Simples-de-Estoque.git
cd Controle-Simples-de-Estoque
```

2. Instale as dependências
```bash
composer install
npm install
```

3. Configure o ambiente
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure o banco de dados no arquivo .env
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=seu_banco
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

5. Execute as migrações
```bash
php artisan migrate
```

6. Crie um usuário administrador
```bash
php artisan create:default-user
```

7. Compile os assets
```bash
npm run dev
```

8. Inicie o servidor
```bash
php artisan serve
```

## Estrutura do Projeto

- `app/Http/Controllers` - Controladores da aplicação
- `app/Models` - Modelos do Eloquent
- `app/Services` - Serviços da aplicação
- `database/migrations` - Migrações do banco de dados
- `resources/views` - Views Blade
- `public/imagens/produtos` - Armazenamento de imagens de produtos

## Contribuindo

1. Faça um Fork do projeto
2. Crie uma Branch para sua Feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a Branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## Suporte

Para suporte, envie um email para expertbrazilweb@gmail.com ou abra uma issue no GitHub.
