# LiveShop

Sistema de gestão de estoque desenvolvido com Laravel 12, PHP 8.2+ e Livewire 4. O projeto demonstra componentes reativos do Livewire junto a operações de produtos, categorias, vendas, compras e relatórios.

## Funcionalidades

- Cadastro, edição, listagem, busca, filtros por categoria, estoque e faixa de preços, paginação e lixeira de produtos.
- Cadastro e edição de categorias, com proteção contra exclusão enquanto houver produtos associados.
- Registro de vendas e compras em telas separadas, com seleção de vários produtos, quantidades, total atualizado e ajuste de estoque.
- Validação de estoque disponível antes de registrar vendas.
- Remoção lógica, restauração e exclusão permanente de produtos, categorias, vendas e compras, respeitando os vínculos existentes.
- Relatórios de vendas e compras por período, movimentações recentes e produtos com estoque baixo.
- Interface em português, inglês e espanhol.
- Autenticação para as telas de gestão.

## Tecnologias e arquitetura

- Laravel 12 e Eloquent ORM.
- Livewire para interfaces de listagem, formulários, filtros e interações sem recarregamento completo.
- Blade para layouts e composição modular das páginas juntamente com Bootstrap.
- Form Requests para regras reutilizáveis de validação HTTP e para validação de estado dos componentes Livewire.
- Migrations para usuários, categorias, produtos, vendas, compras e tabelas de associação com quantidades.
- SQLite por padrão, configurável pelo `.env`.

Os principais componentes ficam em `app/Livewire`; os modelos e relacionamentos em `app/Models`; as regras de validação em `app/Http/Requests`; as páginas e componentes Blade em `resources/views`; e as migrations e seeders em `database/`.

## Requisitos

- PHP 8.2 ou superior e extensões exigidas pelo Laravel.
- Composer.
- Node.js e npm.
- SQLite (configuração padrão) ou outro banco compatível configurado no `.env`.

## Instalação e execução

1. Clone o repositório e instale as dependências:

   ```sh
   git clone https://github.com/GabrielGMori/seminario-web-2.git
   cd seminario-web-2
   composer install
   npm install
   ```

2. Crie o `.env` com base no exemplo e gere a chave da aplicação:

   ```sh
   # Windows PowerShell
   Copy-Item .env.example .env
   php artisan key:generate
   ```

3. Crie as tabelas e os dados iniciais:

   ```sh
   php artisan migrate --seed
   ```

4. Compile os recursos e inicie o servidor:

   ```sh
   npm run build
   php artisan serve
   ```

5. Acesse o endereço informado pelo Artisan, registre um usuário e entre no sistema.
