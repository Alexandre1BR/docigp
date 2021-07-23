# DOCIGP - Descentralização Orçamentária de Custeio Individualizado para Gabinete Parlamentar 

## [https://docigp.alerj.rj.gov.br/](https://docigp.alerj.rj.gov.br/)

## Regulamentos (regras de negócio)

- Atos 641/2019, 645/2019, 647/2019, 648/2019, 664/2021
- https://alerj.sharepoint.com/:f:/s/arquivos/EpLENmE3SStMr0zuiI530dsBmqIi8-KPeQvszRgYti1a3Q?e=9cte95

## Características da aplicação

- [Git](https://git-scm.com/docs/user-manual.html)
- [PHP 7.2 ou superior](http://php.net/)
- [Composer](https://getcomposer.org/)
- [Redis](https://redis.io/topics/quickstart)
- [PostgreSQL](https://www.postgresql.org/)
- [Pusher](https://pusher.com/)

### Instalação e atualização

#### Instalação

- Clonar o repositório (branch: staging [homologação] or production [produção])
- Configurar servidor web para apontar para a **`<pasta-aonde-o-site-foi-instalado>`/public**
- Instalar certificado SSL (precisamos que a página seja acessível **via https apenas**)
- Criar o banco do dados.
- Entrar na `<pasta-aonde-o-site-foi-instalado>`
- Configurar o arquivo `.env`
    - Copiar o arquivo `.env.example` para `.env`
    - Configurar todos dados do sistema
    - Alterar a variável `APP_ENV` para o ambiente correto (local, testing, staging, production)
    - Configurar banco de dados
    - Configurar o Pusher (criar uma conta, se necessário)
    - Configurar o serviço de e-mail (Outlook, Mailtrap, ou MAIL_DRIVER=log)
- Executar o comando `composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev` para instalar todas as dependências da aplicação.
    - Caso estiver no ambiente de desenvolvimento, executar `composer install`
- Executar o comando `php artisan migrate` para criar/atualizar a estrutura de Banco de dados
- Linkar a pasta storage, executando o comando `php artisan storage:link`
- Criar uma chave para a aplicação, executando o comando `php artisan key:generate`
- Criar o primeiro usuário administrador
```
php artisan docigp:users:create admin@alerj.rj.gov.br Admin
php artisan docigp:sync:roles
php artisan docigp:role:assign administrator admin@alerj.rj.gov.br
```
- Resetar a senha para o usuário administrador criado
- Executar os seguintes comandos para sincronizar os dados e popular o banco de dados
```
php artisan docigp:sync:parties 
php artisan docigp:sync:congressmen
php artisan docigp:sync:departments
php artisan docigp:sync:roles
php artisan docigp:budget:generate
```
- Criar os usuários restantes e dar suas respectivas permissões através da sessão do usuário administrador

### Atualização

- Entrar na `<pasta-aonde-o-site-foi-instalado>`
- Baixar as atualizações de código fonte usando Git (git pull)
- Executar os comandos em sequência:
```
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan migrate --force
php artisan docigp:sync:roles
```
- Reiniciar o Horizon
- Dar permissão de owner do usuário web(exemplo: www-data) para a pasta do projeto

#### Passos extras específicos desta aplicação

##### Configurar [scheduler](https://laravel.com/docs/5.8/scheduling)
Colocar no cron a seguinte linha de comando, respeitando o path da aplicação:
```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

##### Configurar o [Laravel Horizon](https://laravel.com/docs/5.8/horizon)
Configurar o Supervisor para manter o Horizon rodando o seguinte deamon
```
php artisan horizon
```

### Comandos disponíveis

#### Gerar orçamento mensal 

- O comando é utilizado para gerar orçamentos mensais para os deputados.

- `php artisan docigp:budget:generate {baseDate?} {congressmanName?}`
    - `{baseDate?}` é a data de referência que será usada. Caso o argumento não seja especificado, o mês usado será o mês atual.
    - `{congressmanName?}` é o nome do deputado para o qual se deseja criar o orçamento mensal. Caso não seja especificado, todos os deputados serão considerados.

##### Exemplos

- Para gerar o orçamento mensal de março de 2021 para o deputado João da Silva, executar o comando `docigp:budget:generate 2021-03-01 "João da Silva"`

- Para gerar os orçamentos mensais de maio de 2023 para todos os deputados, executar o comando `docigp:budget:generate 2023-05-01`

- Para gerar os orçamentos mensais do mês atual para todos os deputados, executar o comando `docigp:budget:generate`

#### Conceder ou retirar permissões de um usuário

- Os comandos `docigp:role:assign {role} {email}` e `docigp:role:retract {role} {email}` são usados para conceder ou retirar permissões, respectivamente.

- O argumento `{role}` se refere ao perfil de referência.
    - Perfis disponíveis: `administrator`, `financial`, `aci`

##### Exemplos

- Para conceder permissão de administrator para o usuário admin@alerj.rj.gov.br, executar o comando `php artisan docigp:role:assign administrator admin@alerj.rj.gov.br`

- Para retirar permissão de administrator para o usuário admin@alerj.rj.gov.br, executar o comando `php artisan docigp:role:retract administrator admin@alerj.rj.gov.br`

#### Comandos de sincronização de dados

```
php artisan docigp:sync:parties 
php artisan docigp:sync:congressmen
php artisan docigp:sync:departments
php artisan docigp:sync:roles 
```

### Como inserir um novo deputado

### Perfis de permissionamento
- As regras de permissionamento são guardadas em `config/roles.php`.
- Caso haja alguma alteração nesse arquivo, é necessário rodar o comando `php artisan docigp:sync:roles`.

#### Administrador 
    - Todas as permissões
#### Deputado
    - Lançamentos
        - Verificar
        - Criar / Editar / Apagar
    - Documentos
        - Criar / Apagar
        - Verificar
        - Publicar
    - Comentários
        - Criar (do deputado)
        - Apagar (do deputado)
        - Editar (do deputado)
    - Mês
        - Fechar
        - Porcentagem
        - Depositar
#### ACI
    - Ver todos os botões
    - Lançamentos
        - Analisar
        - Publicar
    - Fornecedores
        - Criar / Editar
    - Documentos
        - Analisar
    - Comentários
      - Criar (da ACI)
      - Apagar (da ACI)
      - Editar (da ACI)
    - Mês
        - Reabrir
        - Analisar
        - Publicar

#### Visualizador
    - Ver lançamentos de todos os deputados, inclusive os não públicos
    
### Comandos para popular um banco de testes
 
```
- Budget de Fevereiro e Março
a migrate:fresh -vvv --force; a docigp:sync:parties -vvv; a docigp:sync:congressmen -vvv; a docigp:sync:departments; a docigp:sync:roles; a docigp:budget:generate 2019-02-01; a docigp:budget:generate 2019-03-01;                                a db:seed -vvv --force; a docigp:budget:generate -vvv; 
- Budget de Fevereiro, Março e Abril
a migrate:fresh -vvv --force; a docigp:sync:parties -vvv; a docigp:sync:congressmen -vvv; a docigp:sync:departments; a docigp:sync:roles; a docigp:budget:generate 2019-02-01; a docigp:budget:generate 2019-03-01; a docigp:budget:generate -vvv; a db:seed -vvv --force;

a migrate:fresh -vvv --force; a docigp:sync:roles -vvv; a docigp:budget:generate 2019-04-01 -vvv; a docigp:budget:generate -vvv
```
