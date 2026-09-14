# Como rodar o Paroquia Online

## Jeito rapido (1 clique)

Clique duas vezes em **INICIAR.bat**.

O script faz tudo sozinho:

1. Procura o PHP no seu computador. Se nao achar, baixa uma copia
   portatil para a pasta `php-portatil\` (nao instala nada no Windows).
2. Baixa o Composer (`composer.phar`) e instala as dependencias do Laravel.
3. Cria o `.env` e gera a `APP_KEY`.
4. Cria o banco SQLite (`database/database.sqlite`) e roda migrations + seeders.
5. Sobe o servidor e abre o navegador.

Na primeira vez leva alguns minutos (download das dependencias).
Nas proximas, sobe em segundos.

## Enderecos

- Site: http://localhost:8000
- Painel admin: http://localhost:8000/admin
- Login admin: `admin@paroquia.com` / `admin123`
  (defina outra senha em `ADMIN_SENHA` no `.env` e rode
  `php artisan db:seed --class=AdminSeeder`)

## Banco de dados

O `.env` foi configurado com **SQLite** para funcionar sem instalar MySQL.
Se preferir MySQL, edite o `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=paroquia_online
DB_USERNAME=root
DB_PASSWORD=
```

e rode `php artisan migrate --seed`.

## Parar o servidor

Feche a janela preta ou aperte Ctrl+C.
