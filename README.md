# API Rest

API Rest feito em Laravel para Teste Programador

Aplicação com Sistema de Login e níveis de acesso.

Administrador é capaz de ver lista de usuários, deletar usuários e definir permissões para lidar com os recursos.<br>
Usuários comuns são capazes de ver as telas referentes aos recursos que eles possuam permissão.

Recursos: Produtos, Categorias e Marcas.

Back-end: Laravel, banco de dados Mysql.<br>
Autenticação: JWT Token.

## Instalação ##
1) Clone o repositório (git clone https://github.com/shineyder/API.git) e entre na pasta do projeto<br>
2) Execute o comando ```composer install``` para instalar as dependencias<br>
3) Copie o arquivo ```.env.example```, renomeie para ```.env``` e configure de acordo.<br>
4) Execute o comando ```php artisan key:generate``` para o Laravel gerar a chave dessa aplicação<br>
5) Execute o comando ```php artisan jwt:secret``` para gerar a chave JWT<br>
6) Execute o comando ```php artisan migrate --seed``` para gerar as tabelas no banco de dados<br>
7) Inicie a aplicação com o comando ```php artisan serve```<br>

OBS:<br>
Em ```database/seeders/UserSeeder.php``` estão os usuários criados para testes
