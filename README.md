# Paróquia Online - Sistema Web de Informações

Site institucional da Paróquia Nossa Senhora da Glória.

## Time

- **Product Owner:** Luis Gustavo Romanichen Domingues
- **Scrum Master:** João Vitor Grando
- **Desenvolvedor:** José Afonso Machado da Cruz
- **Desenvolvedor:** Gustavo Ferreira dos Santos

## Tecnologias

- PHP 8.x / Laravel 10
- SQLite (padrão) ou MySQL
- Bootstrap 5
- Blade (template engine do Laravel)

## Como rodar o projeto

### Jeito rápido (Windows)

Clique duas vezes em **INICIAR.bat**. Ele cuida de tudo sozinho: encontra o PHP
(ou baixa uma cópia portátil), instala as dependências com o Composer, cria o
`.env` e a `APP_KEY`, monta o banco com migrations e seeders e sobe o servidor
em http://localhost:8000.

### Manual

Requisitos: PHP >= 8.1 com a extensão `pdo_sqlite` e Composer.

```bash
git clone https://github.com/JoaoVitorGrando/Paroquia.Online.git
cd Paroquia.Online

composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Acesse em: http://localhost:8000

### Banco de dados

O `.env.example` já vem com `DB_CONNECTION=sqlite`, que não exige instalar nada.
Para usar MySQL, edite o `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=paroquia_online
DB_USERNAME=root
DB_PASSWORD=
```

e rode `php artisan migrate --seed`.

## Acesso administrador

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
- US003: Cadastro de usuário
- US004: Login de usuário

## Sprint 2 - Funcionalidades implementadas

- US005 a US012: Inscrição em grupos, voluntariado, painel admin de eventos e avisos, Home, Sobre, Avisos e Contato

## Sprint 3 - Funcionalidades implementadas

- US013: CRUD admin de Grupos (criar, editar, ativar/desativar, listar inscritos)
- US014: CRUD admin de Horários de Missas
- US015: Envio real de e-mail no formulário de contato (via SMTP)
- US016: Testes unitários e de feature (Auth, Grupo, Evento, Contato)
- US017: Refinos de UX — hero com imagem da igreja na home e footer fixo no final da página

### Executar testes

```bash
php artisan test
```

## Sprint 4 - Funcionalidades implementadas

- Correção da ordenação dos horários de missa (`Missa::indiceDia()`)
- Edição de avisos no painel administrativo
- Visualização dos voluntários de cada evento
- Upload de fotos em grupos e eventos
- Contato por WhatsApp (links `wa.me`, sem custo e sem API)
- Páginas de Catequese e de Sacramentos
- Bloco "Onde estamos" com mapa e rodapé institucional com redes sociais
- Home, Missas, Avisos, Contato e painel admin reformulados
