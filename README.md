# CodeIgniter 4 Aplicativo Inicial

## O que é CodeIgniter?
CodeIgniter é um framework web full-stack para PHP, que se destaca por ser leve, rápido, flexível e seguro. Você pode encontrar mais informações no site oficial.

Este repositório contém um aplicativo inicial que pode ser instalado via Composer. Ele foi construído a partir do repositório de desenvolvimento.

Mais informações sobre os planos para a versão 4 podem ser encontradas em CodeIgniter 4 nos fóruns.

Você pode ler o guia do usuário correspondente à versão mais recente do framework.

## Instalação e Atualizações
Para instalar, use o comando composer create-project codeigniter4/appstarter. Para atualizações, execute composer update sempre que houver um novo lançamento do framework.

Ao atualizar, verifique as notas de lançamento para ver se há alguma mudança que você precise aplicar à sua pasta app. Os arquivos afetados podem ser copiados ou mesclados de vendor/codeigniter4/framework/app.

## Configuração
Copie o arquivo env para .env e personalize-o para sua aplicação, especialmente a baseURL e quaisquer configurações de banco de dados.

Mudança Importante com index.php
O arquivo index.php não está mais na raiz do projeto! Ele foi movido para dentro da pasta public, visando maior segurança e melhor separação de componentes.

Isso significa que você deve configurar seu servidor web para "apontar" para a pasta public do seu projeto, e não para a raiz do projeto. Uma prática melhor seria configurar um host virtual para apontar para lá. Uma prática ruim seria apontar seu servidor web para a raiz do projeto e esperar acessar public/..., pois o restante da sua lógica e do framework ficariam expostos.

Por favor, leia o guia do usuário para uma explicação mais detalhada de como o CI4 funciona!

## Requisitos do Servidor
É necessária a versão PHP 8.1 ou superior, com as seguintes extensões instaladas:

- intl
- mbstring

## Aviso:
- A data de fim de vida (EOL) do PHP 7.4 foi 28 de novembro de 2022.
- A data de fim de vida (EOL) do PHP 8.0 foi 26 de novembro de 2023.
- Se você ainda estiver usando PHP 7.4 ou 8.0, deve atualizar imediatamente.
- A data de fim de vida (EOL) do PHP 8.1 será 31 de dezembro de 2025.

Além disso, certifique-se de que as seguintes extensões estejam habilitadas em seu PHP:

- json (habilitada por padrão - não desative)
- mysqlnd se você planeja usar MySQL
- libcurl se você planeja usar a biblioteca HTTP\CURLRequest