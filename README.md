# Paróquia Online

Site institucional da **Paróquia Nossa Senhora da Glória**, de Pitanga (PR), da Igreja
Católica Ucraniana de rito bizantino.

O objetivo é simples: manter horários de missa, eventos, grupos, catequese e avisos
sempre atualizados e fáceis de consultar pelo celular, sem depender de redes sociais.
A secretaria da paróquia atualiza tudo por um painel administrativo, sem precisar de
ajuda técnica.

Projeto desenvolvido na disciplina de Projeto de Extensão do curso de Engenharia de
Software, com o sistema doado à paróquia ao final.

## Equipe

| Função | Nome |
| --- | --- |
| Product Owner | Luis Gustavo Romanichen Domingues |
| Scrum Master | João Vitor Grando |
| Desenvolvedor | José Afonso Machado da Cruz |
| Desenvolvedor | Gustavo Ferreira dos Santos |

## O que o sistema faz

### Para quem visita o site

Não existe cadastro nem login para o visitante. Todas as páginas são abertas.

- **Início**: próxima celebração, agenda da semana, avisos em destaque, próximos
  eventos e os grupos da paróquia
- **Missas**: horários por dia da semana, com local e observações
- **Eventos**: festas e celebrações, separadas entre as que ainda vão acontecer e as
  que já aconteceram
- **Grupos**: pastorais e movimentos, com responsável, dia e horário de reunião
- **Grupo Folclórico Ucraniano Kyiv**: página própria, com galeria de fotos e o
  formulário de matrícula
- **Catequese** e **Sacramentos**: horários, turmas e documentos necessários
- **Avisos**: comunicados da secretaria
- **Sobre**: história da comunidade ucraniana em Pitanga e linha do tempo
- **Contato**: WhatsApp, telefones, e-mail, endereço com rota no mapa e redes sociais

Quem quer participar de um grupo, ser voluntário ou marcar um batizado fala direto com
a secretaria pelo botão de WhatsApp, presente em todas as páginas. Essa foi uma decisão
de escopo: evita cadastro, senha e guarda de dados pessoais de terceiros.

### Para a secretaria da paróquia

Um único login, com acesso ao painel administrativo:

- Painel com os números do site (eventos, avisos, grupos e missas)
- Cadastro, edição e exclusão de horários de missa, eventos, grupos e avisos
- Ativar e desativar grupos e horários sem precisar excluir
- Envio de fotos para grupos e eventos
- Destacar avisos na página inicial

## Tecnologias

| Camada | Tecnologia |
| --- | --- |
| Linguagem | PHP 8.1 ou superior |
| Framework | Laravel 10 |
| Banco de dados | SQLite (padrão) ou MySQL |
| Telas | Blade, o sistema de templates do Laravel |
| Interface | Bootstrap 5 e Bootstrap Icons |
| Testes | PHPUnit |
| Dependências | Composer |

O sistema não envia e-mail e não usa API paga. O contato por WhatsApp é feito com links
`wa.me`, que não têm custo.

## Como rodar

### Windows, em um clique

Dê dois cliques em **`INICIAR.bat`**.

O script procura o PHP no computador e, se não encontrar, baixa uma cópia portátil na
pasta do projeto, sem instalar nada no Windows. Depois instala as dependências, cria o
banco de dados com os dados iniciais e abre o site em `http://localhost:8000`.

Na primeira vez leva alguns minutos. Nas próximas, sobe em segundos.

### Manual, em qualquer sistema

Requisitos: PHP 8.1 ou superior com a extensão `pdo_sqlite`, e Composer.

```bash
git clone https://github.com/JoaoVitorGrando/Paroquia.Online.git
cd Paroquia.Online

composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

O site abre em `http://localhost:8000`.

## Acesso ao painel

O painel fica em `http://localhost:8000/admin`.

As credenciais **não ficam no repositório**. Defina no arquivo `.env`:

```
ADMIN_EMAIL=email-da-secretaria@exemplo.com
ADMIN_SENHA=uma-senha-forte
```

Depois rode:

```bash
php artisan db:seed --class=AdminSeeder
```

O mesmo comando serve para trocar a senha depois: basta alterar o `.env` e rodar de
novo. Contas sem permissão de administrador são recusadas no login.

## Dados da paróquia

Nome, CNPJ, endereço, telefones, e-mail, redes sociais e links externos ficam todos em
`config/paroquia.php` e podem ser alterados pelo `.env`, sem mexer em código. O site
inteiro lê desse arquivo, então trocar um telefone é mudar uma linha.

## Banco de dados

O padrão é **SQLite**, que não exige instalar servidor: o banco é o arquivo
`database/database.sqlite`. Para usar MySQL, altere no `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=paroquia_online
DB_USERNAME=usuario
DB_PASSWORD=senha
```

E rode `php artisan migrate --seed`.

São cinco tabelas, sem relacionamento entre elas: `users`, `missas`, `eventos`,
`grupos` e `avisos`. As fotos enviadas ficam em `public/uploads/`; o banco guarda
apenas o caminho do arquivo.

## Testes

```bash
vendor/bin/phpunit
```

São **75 testes automatizados** cobrindo as páginas públicas, o painel administrativo,
as regras de negócio e a segurança do login. Rodar os testes antes de publicar qualquer
alteração é a forma mais rápida de saber se algo quebrou.

## Acessibilidade

O site foi verificado com o **axe-core**, a mesma ferramenta usada pelo Lighthouse, em
telas de 320px, 390px e 1280px. As onze páginas públicas passam sem nenhuma violação.

O que foi feito:

- Estrutura de títulos correta em todas as páginas, para leitores de tela
- Link "Pular para o conteúdo", que aparece ao usar a tecla Tab
- Botões e links com nome descrito, inclusive os que só têm ícone
- Contraste de cores dentro do mínimo recomendado
- Áreas de toque de pelo menos 44 pixels e espaçamento entre os botões no celular
- Respeito à opção do sistema de reduzir animações

## Estrutura do projeto

```
app/Http/Controllers/    Recebem as requisições e escolhem a resposta
app/Models/              Entidades do sistema (Missa, Evento, Grupo, Aviso, User)
app/Http/Middleware/     Verificação de acesso ao painel
config/paroquia.php      Dados da paróquia em um lugar só
database/migrations/     Estrutura das tabelas
database/seeders/        Dados iniciais
resources/views/         Telas do site e do painel
resources/views/components/  Trechos reaproveitados (cartões, botão de WhatsApp)
resources/views/errors/  Páginas de erro (404, 403, 500) com o layout do site
routes/web.php           Endereços do site
tests/                   Testes automatizados
```

## Hospedagem

O sistema roda em qualquer servidor com PHP 8.1 ou superior. Três pontos de atenção
para quem for publicar:

- A raiz do servidor precisa apontar para a pasta `public/`. Apontar para a pasta do
  projeto deixaria o arquivo `.env` acessível pela internet.
- No `.env` do servidor, `APP_DEBUG` tem que ficar em `false` e `APP_URL` tem que ser
  o endereço real do site. Quando o endereço começa com `https`, o sistema passa a
  gerar todos os links em `https` sozinho.
- O arquivo do banco (`database/database.sqlite`) e a pasta `public/uploads/` precisam
  ficar em um disco que não seja apagado a cada publicação. Em hospedagens que zeram o
  disco a cada deploy, use MySQL ou PostgreSQL e um serviço de armazenamento para as
  fotos.

## Histórico das entregas

**Sprint 1**
- US001: horários de missas
- US002: eventos e festas da comunidade
- US003 e US004: autenticação, hoje restrita ao administrador

**Sprint 2**
- US005 a US012: painel administrativo de eventos e avisos, Home, Sobre, Avisos e Contato

**Sprint 3**
- US013: gerenciamento de grupos, com criar, editar e ativar/desativar
- US014: gerenciamento de horários de missa
- US015: página de contato com os canais diretos da secretaria
- US016: testes automatizados
- US017: melhorias de navegação e apresentação

**Sprint 4**
- Correção da ordenação dos horários de missa
- Edição de avisos no painel
- Envio de fotos em grupos e eventos
- Contato por WhatsApp em todas as páginas
- Páginas de Catequese e Sacramentos
- Bloco "Onde estamos" com mapa

**Sprint 5, simplificação e entrega**
- Cadastro público, login de fiéis, inscrição em grupos e voluntariado removidos: a
  participação passou a ser combinada por WhatsApp
- Formulário de e-mail removido, já que a paróquia não mantém servidor de e-mail
- Dados da paróquia centralizados em um arquivo de configuração
- Revisão de segurança: limite de tentativas de login e proteção da permissão de
  administrador
- Revisão de layout e responsividade, com componentes reaproveitados
- Revisão de acessibilidade
- Instalador unificado em um único `INICIAR.bat`
- Preparo para publicação: páginas de erro com o layout do site, ícone da aba,
  prévia do link ao compartilhar, `robots.txt` e HTTPS automático em produção

## Licença e uso

Projeto acadêmico sem fins lucrativos, desenvolvido para a Paróquia Nossa Senhora da
Glória e doado a ela, que pode usar, alterar e manter o sistema livremente.
