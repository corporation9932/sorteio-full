# Sistema de Rifas - Gêmeos Brasil

Sistema completo de rifas online com integração PIX via NitroPagamentos.

## Características

- ✅ Sistema completo de rifas/campanhas
- ✅ Painel administrativo
- ✅ Integração com API PIX NitroPagamentos
- ✅ Banco de dados MySQL
- ✅ Sistema de usuários
- ✅ Ranking de participantes
- ✅ Sistema de descontos
- ✅ Geração automática de números
- ✅ Interface responsiva
- ✅ Design idêntico ao site de referência
- ✅ Valor mínimo R$ 5,00 e máximo 500 números
- ✅ Todas as telas funcionais
- ✅ Sistema de contatos
- ✅ Página de ganhadores
- ✅ Termos de uso
- ✅ Relatórios completos

## Instalação

1. **Configurar Banco de Dados**
   ```bash
   # Importar o schema do banco
   mysql -u root -p < database/rifas_system.sql
   ```

2. **Configurar API NitroPagamentos**
   - Edite o arquivo `config/nitro_api.php`
   - Adicione sua API Key da NitroPagamentos

3. **Configurar Banco de Dados**
   - Edite o arquivo `config/database.php`
   - Configure as credenciais do MySQL

4. **Permissões**
   ```bash
   chmod 755 -R .
   chmod 777 uploads/
   ```

## Estrutura do Sistema

### Frontend
- `index.php` - Página principal (idêntica ao site de referência)
- `compra/index.php` - Página de checkout com PIX
- `meus-numeros.php` - Visualização dos números do usuário
- `campanhas.php` - Lista todas as campanhas ativas
- `ganhadores.php` - Lista dos ganhadores
- `termos.php` - Termos de uso
- `info.php` - Página de confirmação de cadastro

### Backend
- `classes/` - Classes PHP (Campaign, User, Order, Main)
- `config/` - Configurações (Database, NitroAPI)
- `admin/` - Painel administrativo

### Banco de Dados
- `campaigns` - Campanhas/rifas
- `users` - Usuários
- `orders` - Pedidos
- `raffle_numbers` - Números das rifas
- `discounts` - Descontos por quantidade
- `ranking` - Ranking de participantes
- `admins` - Administradores
- `contacts` - Mensagens de contato

## Painel Administrativo

Acesse: `/admin/`
- **Usuário:** admin
- **Senha:** admin123

### Funcionalidades Admin:
- Dashboard com estatísticas
- Gerenciar campanhas
- Visualizar pedidos
- Gerenciar usuários
- Relatórios
- Gerenciar contatos

## API NitroPagamentos

O sistema utiliza a API da NitroPagamentos para:
- Gerar códigos PIX
- Gerar QR Codes
- Verificar status de pagamentos
- Webhook de confirmação

## Funcionalidades

### Para Usuários:
- Visualizar campanhas ativas
- Selecionar quantidade de números
- Sistema de descontos automáticos
- Pagamento via PIX
- Acompanhar status do pedido
- Visualizar números adquiridos
- Ranking de participantes
- Consultar ganhadores
- Entrar em contato
- Visualizar termos de uso

### Para Administradores:
- Criar/editar campanhas
- Definir preços e descontos
- Acompanhar vendas em tempo real
- Gerenciar usuários
- Relatórios de faturamento
- Controle de números vendidos
- Gerenciar contatos dos usuários
- Relatórios detalhados por período
- **Valor mínimo por número:** R$ 5,00
- **Máximo de números por pessoa:** 500 por rifa
- **Pagamento:** Exclusivamente via PIX
- **Tempo para pagamento:** 30 minutos
- **Geração de números:** Automática após confirmação do pagamento
- **Sorteios:** Realizados nas datas programadas

## Segurança

- Senhas criptografadas com bcrypt
- Validação de dados de entrada
- Proteção contra SQL Injection
- Sessões seguras
- Tokens únicos para pedidos

## Personalização

O sistema mantém o design idêntico ao site https://www.gemeosbrasil.me/ e pode ser facilmente personalizado:

- Cores e estilos em `js/style.css`
- Logos e imagens em `js/`
- Textos e conteúdos nos arquivos PHP
- Configurações de pagamento em `config/nitro_api.php`

## Suporte

Para suporte técnico, entre em contato via Telegram: @TXT_JPGI1