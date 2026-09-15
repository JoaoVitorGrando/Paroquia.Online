# Paróquia Online - Sistema Web de Informações

Site institucional da Paróquia Nossa Senhora da Glória.

## Time

- **Product Owner:** Luis Gustavo Romanichen Domingues
- **Scrum Master:** João Vitor Grando
- **Desenvolvedor:** José Afonso Machado da Cruz
- **Desenvolvedor:** Gustavo Ferreira dos Santos

## Tecnologias

- PHP 8.x / Laravel 10
- MySQL
- Bootstrap 5
- Blade (template engine do Laravel)

## Como rodar o projeto

### Requisitos

- PHP >= 8.1
- Composer
- MySQL
- Node.js (opcional, para compilar assets)

### Instalação

```bash
git clone https://github.com/SEU_USUARIO/paroquia-online.git
cd paroquia-online

composer install

cp .env.example .env
php artisan key:generate
```

### Configurar banco de dados

Edite o `.env` com suas credenciais MySQL:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=paroquia_online
DB_USERNAME=root
DB_PASSWORD=
```

Depois rode as migrations e seeders:

```bash
php artisan migrate --seed
```

### Iniciar servidor

```bash
php artisan serve
```

Acesse em: http://localhost:8000

## Acesso administrador

O site é **informativo e sem cadastro público**: quem visita não cria conta. Quem
quer participar de um grupo ou de um evento fala com a paróquia pelo botão de
WhatsApp presente em todas as páginas. O único login do sistema é o do
administrador, que mantém horários, eventos, grupos e avisos atualizados.

As credenciais do administrador **não ficam no repositório**. Defina no seu `.env`:

```
ADMIN_EMAIL=seu-email@exemplo.com
ADMIN_SENHA=uma-senha-forte
```

Depois rode o seeder do admin (pode ser rodado de novo para redefinir a senha):

```bash
php artisan db:seed --class=AdminSeeder
```

O painel fica em: http://localhost:8000/admin

## Sprint 1 - Funcionalidades implementadas

- US001: Visualização de horários de missas
- US002: Visualização de eventos e festas da comunidade
- US003 e US004: autenticação (hoje restrita ao administrador)

## Sprint 2 - Funcionalidades implementadas

- US005 a US012: painel administrativo de eventos e avisos, Home, Sobre, Avisos e Contato

## Sprint 3 - Funcionalidades implementadas

- US013: CRUD admin de Grupos (criar, editar, ativar/desativar)
- US014: CRUD admin de Horários de Missas
- US015: Página de contato com os canais diretos da secretaria
- US016: Testes unitários e de feature (Auth, Grupo, Evento, Contato)
- US017: Refinos de UX: hero com imagem da igreja na home e footer fixo no final da página

### Executar testes

```bash
vendor/bin/phpunit
```

## Sprint 4 - Funcionalidades implementadas

- Correção da ordenação dos horários de missa (`Missa::indiceDia()`)
- Edição de avisos no painel administrativo
- Upload de fotos em grupos e eventos
- Contato por WhatsApp (links `wa.me`, sem custo e sem API)
- Páginas de Catequese e de Sacramentos
- Bloco "Onde estamos" com mapa e rodapé institucional com redes sociais
- Home, Missas, Avisos, Contato e painel admin reformulados

## Sprint 5 - Simplificação do produto

- Cadastro público, login de fiéis, inscrição em grupos e voluntariado **removidos**:
  a participação passa a ser combinada diretamente pelo WhatsApp
- Formulário de contato por e-mail **removido**: a paróquia não mantém servidor de
  e-mail, e o site passou a publicar apenas os canais diretos (WhatsApp, telefones,
  e-mail e endereço). O sistema não envia e-mail nenhum
- Login mantido apenas para o administrador, com recusa explícita de contas sem permissão
- Instalador unificado em um único `INICIAR.bat`
