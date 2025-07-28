# Correção do Erro 404 ao Clicar em Categorias

## Problema Identificado
O erro 404 estava ocorrendo porque **não existiam rotas definidas para categorias** no arquivo `app/Config/Routes.php`.

## Correções Realizadas

### 1. Adicionadas Rotas para Categorias
**Arquivo:** `app/Config/Routes.php`

Adicionadas as seguintes rotas no grupo 'admin':
```php
// Rotas de categorias
$routes->get('categorias', 'Admin\Categorias::index');
$routes->get('categorias/criar', 'Admin\Categorias::criar');
$routes->post('categorias/cadastrar', 'Admin\Categorias::cadastrar');
$routes->get('categorias/editar/(:num)', 'Admin\Categorias::editar/$1');
$routes->post('categorias/atualizar/(:num)', 'Admin\Categorias::atualizar/$1');
$routes->get('categorias/excluir/(:num)', 'Admin\Categorias::excluir/$1');
$routes->post('categorias/deletar/(:num)', 'Admin\Categorias::deletar/$1');
$routes->post('categorias/desfazer-exclusao/(:num)', 'Admin\Categorias::desfazerExclusao/$1');
$routes->get('categorias/(:num)', 'Admin\Categorias::show/$1'); // Esta é a rota principal que estava faltando
```

### 2. Adicionado JavaScript na View de Categorias
**Arquivo:** `app/Views/Admin/Categorias/index.php`

Adicionado o JavaScript para tornar as linhas clicáveis:
```javascript
<script>
    $(document).ready(function () {
        // Torna as linhas da tabela clicáveis
        $('.categoria-row').on('click', function () {
            var categoriaId = $(this).data('id');
            window.location.href = '<?= base_url('admin/categorias/') ?>' + categoriaId;
        });

        // Adiciona efeito hover nas linhas
        $('.categoria-row').hover(
                function () {
                    $(this).addClass('table-active');
                },
                function () {
                    $(this).removeClass('table-active');
                }
        );
    });
</script>
```

### 3. Corrigido Controller de Categorias
**Arquivo:** `app/Controllers/Admin/Categorias.php`

- Corrigidas as mensagens de erro (de "usuário" para "categoria")
- Adicionado `withDeleted(true)` no método `show()` para buscar categorias excluídas também
- Corrigido o nome da variável no array de dados (de 'usuario' para 'categoria')
- Removido `.php` da view no método `show()`

### 4. Corrigida View de Detalhes da Categoria
**Arquivo:** `app/Views/Admin/Categorias/show.php`

- Reescrita completamente para mostrar informações de categoria em vez de usuário
- Adicionados campos específicos de categoria (nome, descrição, status, datas)
- Corrigidos os botões de ação para categorias
- Adicionada lógica para mostrar botão de restaurar para categorias excluídas

### 5. Correções Menores
- Removida linha HTML duplicada (`</tr>`) na view index de categorias
- Corrigida mensagem de erro no método `buscaCategoriaOu404()`

## Como Testar

1. Acesse a página de listagem de categorias: `http://localhost:8080/admin/categorias`
2. Clique em qualquer linha da tabela de categorias
3. Você deve ser redirecionado para a página de detalhes da categoria: `http://localhost:8080/admin/categorias/{id}`
4. A página deve carregar sem erro 404

## Rotas Disponíveis Agora

- `GET admin/categorias` - Lista todas as categorias
- `GET admin/categorias/{id}` - Mostra detalhes de uma categoria específica
- `GET admin/categorias/criar` - Formulário para criar nova categoria
- `POST admin/categorias/cadastrar` - Processa criação de categoria
- `GET admin/categorias/editar/{id}` - Formulário para editar categoria
- `POST admin/categorias/atualizar/{id}` - Processa atualização de categoria
- `GET admin/categorias/excluir/{id}` - Página de confirmação de exclusão
- `POST admin/categorias/deletar/{id}` - Processa exclusão de categoria
- `POST admin/categorias/desfazer-exclusao/{id}` - Restaura categoria excluída

## Observações

- O JavaScript agora funciona corretamente e redireciona para `admin/categorias/{id}`
- A rota `admin/categorias/(:num)` está mapeada para `Admin\Categorias::show/$1`
- O controller está preparado para lidar com categorias excluídas (soft delete)
- A view de detalhes mostra informações específicas de categorias

O erro 404 foi resolvido e o sistema de categorias agora funciona de forma similar ao sistema de usuários.