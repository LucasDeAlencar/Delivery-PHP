# Configuração HTTPS - CodeIgniter 4

## 🚀 Status Atual
O projeto está configurado para **HTTP** (desenvolvimento local).

## 📋 Scripts Disponíveis

### 1. Testar Conectividade
```bash
php test_site.php
```
Verifica se o site está acessível via HTTP e/ou HTTPS.

### 2. Habilitar HTTPS
```bash
php enable_https.php
```
Ativa todas as configurações HTTPS no projeto.

### 3. Desabilitar HTTPS
```bash
php disable_https.php
```
Desativa HTTPS e volta para HTTP (desenvolvimento).

## 🔧 Como Usar

### Para Desenvolvimento Local (HTTP)
1. Execute: `php disable_https.php`
2. Inicie o servidor: `php spark serve --host=localhost --port=8080`
3. Acesse: `http://localhost:8080`

### Para Produção ou Desenvolvimento com SSL (HTTPS)
1. Configure um certificado SSL
2. Execute: `php enable_https.php`
3. Inicie o servidor com SSL
4. Acesse: `https://localhost:8080`

## 🔒 Configuração de Certificado SSL (Desenvolvimento)

### Opção 1: mkcert (Recomendado)
```bash
# Instalar mkcert
brew install mkcert  # macOS
# ou
sudo apt install mkcert  # Ubuntu

# Criar certificado
mkcert -install
mkcert localhost 127.0.0.1 ::1

# Usar com servidor PHP
php -S localhost:8080 -t public/
```

### Opção 2: Certificado Auto-assinado
```bash
openssl req -x509 -newkey rsa:4096 -keyout key.pem -out cert.pem -days 365 -nodes
```

### Opção 3: Proxy Reverso (nginx/Apache)
Configure um proxy reverso com SSL terminando no nginx/Apache.

## 📁 Arquivos Modificados

- `.env` - Configuração base URL e força HTTPS
- `app/Config/App.php` - Configurações do framework
- `app/Config/Security.php` - Configurações CSRF (padrão CodeIgniter)
- `app/Config/HTTPSSecurity.php` - Configurações HTTPS personalizadas
- `app/Config/Filters.php` - Filtros de segurança
- `app/Filters/ForceHTTPS.php` - Filtro personalizado HTTPS
- `public/.htaccess` - Redirecionamento servidor web
- `app/Helpers/https_helper.php` - Funções auxiliares

## 🛠️ Resolução de Problemas

### "Não é possível acessar o site"
1. Execute: `php test_site.php`
2. Se HTTP falhar: Verifique se o servidor está rodando
3. Se HTTPS falhar: Execute `php disable_https.php`

### "Undefined property: cookieName"
1. Execute: `php verify_fix.php`
2. Certifique-se de que `app/Config/Security.php` existe
3. O arquivo deve conter todas as propriedades CSRF necessárias

### Certificado SSL Inválido
1. Use `mkcert` para desenvolvimento local
2. Ou configure seu navegador para aceitar certificados auto-assinados

### Redirecionamento Infinito
1. Execute: `php disable_https.php`
2. Verifique configurações do servidor web

## 📞 Comandos Úteis

```bash
# Iniciar servidor desenvolvimento
php spark serve --host=localhost --port=8080

# Verificar status
php test_site.php

# Verificar se erro foi corrigido
php verify_fix.php

# Alternar para HTTP
php disable_https.php

# Alternar para HTTPS
php enable_https.php
```

## ⚠️ Notas Importantes

- **Desenvolvimento**: Use HTTP para simplicidade
- **Produção**: Sempre use HTTPS com certificado válido
- **Teste**: Execute `test_site.php` após mudanças
- **Certificados**: Necessários para HTTPS funcionar corretamente