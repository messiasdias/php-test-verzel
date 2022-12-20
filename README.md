# Teste PHP Pleno

Teste Prático PHP Pleno para VERZEL, por [Messias Dias](https://github.com/messiasdias).

Aplicação modelo, catálogo de veículos constituído de PHP com Laravel no Back-end/API e Test com PHPUnit, Front-end Javascript com React.js e Banco de Dados mysql MariaDb.

## Instalação

Partido do princípio que esteja no diretório raiz, siga os comandos abaixo para baixar as dependencias do/s projeto/s.

```bash
# Para a api, entrar no diretorio referente
cd api

# Copia arquivo env exemplo com dados da aplicação
cp .env.example .env

# Instalar pacotes PHP
composer install

# Executa Scripts do Laravel
php artisan key:generate
php artisan storage:link
php artisan migrate [--seed] # --seed para prepopular as tabelas com dados de test (fakes)

# Para a user interface
cd ui
# Copia arquivo env exemplo com dados da aplicação
cp .env.example .env
# Instala os pacotes JS
npm install
```

## Dev
Partido do princípio que esteja no diretório raiz, siga os comandos abaixo para iniciar o ambiente de desenvolvimento.

> Os comandos abaixo devem ser executados em janelas de terminal distintas, pois espera que os scripts continuem em execução 

```bash
# Para a api, entrar no diretorio referente
cd api

# Executar servidor de desenvolvimento PHP
php artisan server

# Para a user interface
cd ui

# Executar servidor de desenvolvimento JS
npm start

# Para o banco de Dados, nesse caso utilizando do Docker Compose **:
docker-compose up
```

Após a execução correta dos comando acima citados, ambos os ambientes de desenvovimento estarão "Ups":

A API em [http://localhost:8000](http://localhost:8000),
a UI em [http://localhost:3000](http://localhost:3000) e o Banco de Dados em `localhost:3306` com username `root` e password `root`.

> ** Certifique se de que não esteja utilizando a porta 3306 (padrão mysql) antes de executar o container

##### Para mais informação consultar as respectivas documentações do [Laravel](https://laravel.com/docs/9.x) e do [React](https://reactjs.org/docs/getting-started.html)