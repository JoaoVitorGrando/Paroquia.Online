# Paróquia Online

Site institucional da **Paróquia Nossa Senhora da Glória**, de Pitanga (PR), da Igreja
Católica Ucraniana de rito bizantino.

O sistema mantém horários de missa, eventos, grupos, catequese e avisos sempre atualizados
e fáceis de consultar pelo celular, sem depender de redes sociais. A secretaria da paróquia
atualiza todo o conteúdo por um painel administrativo, sem precisar de ajuda técnica.

Aplicação Laravel 10 em PHP 8.1, renderizada no servidor, com Blade e Bootstrap 5.

No ar em **https://paroquia-online.site.je**

## Projeto

| | |
| --- | --- |
| Instituição | Centro Universitário Campo Real, Guarapuava, PR |
| Curso | Engenharia de Software |
| Disciplina | Projeto de Extensão |
| Professor | Gabriel Dalpozzo |
| Instituição parceira | Paróquia Nossa Senhora da Glória, Pitanga, PR |

**Equipe:** Luis Gustavo Romanichen Domingues (Product Owner), João Vitor Grando
(Scrum Master), José Afonso Machado da Cruz e Gustavo Ferreira dos Santos.

## Como rodar

### Windows, em um clique

Dê dois cliques em **`INICIAR.bat`**. Ele localiza o PHP, baixa uma cópia portátil se não
encontrar, instala as dependências, cria o banco com os dados iniciais e abre o site em
`http://localhost:8000`.

### Qualquer sistema

Requisitos: PHP 8.1 ou superior com `pdo_sqlite`, e Composer.

```bash
git clone https://github.com/JoaoVitorGrando/Paroquia.Online.git
cd Paroquia.Online

composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

O painel administrativo fica em `/admin`. A conta de acesso é criada pelo `AdminSeeder`
a partir do arquivo `.env`, que não é versionado. O procedimento está no Manual de
Implementação.

## Testes

```bash
vendor/bin/phpunit
```

São 75 testes automatizados cobrindo as páginas públicas, o painel administrativo, as
regras de negócio e a segurança do login.

## Documentação

A documentação completa do projeto é entregue em três documentos:

| Documento | Conteúdo |
| --- | --- |
| **Guia do Usuário** | Como a secretaria opera o painel, tela por tela |
| **Manual de Implementação** | Requisitos, instalação, configuração, publicação e manutenção |
| **Documentação de Arquitetura** | Padrão arquitetural, modelagem, tecnologias e decisões de projeto |

Acompanham os três diagramas do sistema: arquitetura em camadas, casos de uso e modelo
de dados.

## Estrutura

```
app/Http/Controllers/   Recebem as requisições e escolhem a resposta
app/Models/             Entidades do domínio
app/Http/Middleware/    Controle de acesso ao painel
config/paroquia.php     Dados institucionais da paróquia, em um lugar só
database/               Migrations e seeders
resources/views/        Telas do site e do painel
routes/web.php          Rotas da aplicação
tests/                  Testes automatizados
```

## Histórico de sprints

| Sprint | Entrega |
| --- | --- |
| 1 | Horários de missa, eventos e autenticação |
| 2 | Painel administrativo de eventos e avisos; páginas Home, Sobre, Avisos e Contato |
| 3 | Gerenciamento de grupos e de horários; testes automatizados |
| 4 | Upload de fotos, páginas de Catequese e Sacramentos, contato por WhatsApp |
| 5 | Simplificação do produto, acessibilidade, responsividade e publicação |

## Licença e uso

Projeto acadêmico sem fins lucrativos, desenvolvido para a Paróquia Nossa Senhora da
Glória e doado a ela, que pode usar, alterar e manter o sistema livremente.
