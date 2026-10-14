# LiveShop — demonstração de Laravel Livewire

Projeto didático de cadastro e gerenciamento de produtos com Laravel 12 e Livewire 4.

## Onde o Livewire foi aplicado

- `app/Livewire/ProdutoIndex.php`: lista, pesquisa, paginação e exclusão de produtos.
- `resources/views/livewire/produto-index.blade.php`: tabela e busca reativa com `wire:model.live`.
- `app/Livewire/ProdutoForm.php`: validação e gravação de produtos novos ou editados.
- `resources/views/livewire/produto-form.blade.php`: formulário com `wire:model` e `wire:submit`.
- `resources/views/layouts/app.blade.php`: inclui `@livewireStyles` e `@livewireScripts`.
- `routes/web.php`: rotas protegidas por autenticação que exibem as páginas com os componentes Livewire.

## Como executar

Requisitos: PHP compatível com o Laravel do projeto, Composer, Node.js/npm e banco de dados configurado.

1. Instale as dependências:
   ```bash
   composer install
   npm install
   ```
2. Crie o arquivo `.env` a partir do `.env.example` e configure o banco de dados.
3. Gere a chave da aplicação e execute as migrations:
   ```bash
   php artisan key:generate
   php artisan migrate
   ```
4. Em um terminal, inicie o Laravel:
   ```bash
   php artisan serve
   ```
5. Em outro terminal, compile os recursos:
   ```bash
   npm run dev
   ```

Abra o endereço indicado pelo `artisan serve`, crie uma conta e entre para acessar o gerenciamento de produtos.

## Diretivas Livewire demonstradas

- `wire:model.live.debounce.300ms="busca"`: atualiza a pesquisa enquanto o usuário digita, com pequeno atraso.
- `wire:model="name"`: mantém o campo ligado à propriedade PHP.
- `wire:submit="salvar"`: chama o método PHP ao enviar o formulário.
- `wire:click="excluir(...)"`: chama o método de exclusão.
- `wire:loading`: mostra o estado de processamento durante a requisição.
- `wire:confirm`: solicita confirmação antes de excluir.

O Livewire já está listado nas dependências do Composer; `composer install` instala a versão registrada no `composer.lock`.
