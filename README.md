# Gerenciador de clientes
Aplicação para gerenciar dados coletados de clientes. Construída na linguagem PHP utilizando o Framework laravel, servidor web Nginx e SGBD MySQL.
Cada cliente possui nome e e-mail e deve ser possível marcá-lo em tags diversas. Aplicação para cadastrar, listar, alterar e remover um cliente.

## REST API
O sistema conta com uma API para realizar os CRUDS. 
A API segue o protocolo Rest de comunicação, onde é possível realizar uma comunicação com o servidor para obter ou incluir os dados. 
A API disponibiliza de endpoints para se registrar, login e logout. Além dos enpoints para realizar operações de CRUD de clientes.

### Autenticação
A autenticação da API é feita com base no modelo oAuth2, através do uso de um token gerado para usuários logados.
Então para conseguir o token, é necessário se registrar. É possível se registrar utilizando a própria API, enviando uma requisição do tipo POST. Mais detalhes na seção 'Endpoints'.

Existem duas formas de se utilizar o token: 

1. A primeira alternativa é incluir o token no Header da requisição, como uma autenticação do tipo Bearer Token. Por exemplo:
	Authorization Bearer <chave-da-api>

2. A segunda alternativa é enviar o token em uma solicitação no corpo da requisição com o atributo `api_token`.

### API Endpoints

| Ação                | Método de requisição | Endpoint          | Request Body                                 | Retorno                                            |
|---------------------|----------------------|-------------------|----------------------------------------------|----------------------------------------------------|
| Registro de usuário | POST                 | api/register      | name, email, password, password_confirmation | api_token, id, name, email, created_at, updated_at |
| Login de usuário    | POST                 | api/login         | email, password                              | api_token, id, name, email, created_at, updated_at |
| Logout de usuário   | POST                 | api/logout        |                                              | 'User logged out.'                                 |
| Todos os clientes   | GET                  | api/clientes      |                                              | Objetos JSON com todos os clientes                 |
| Cliente específico  | GET                  | api/clientes/{id} |                                              | id, name, email, tagged: {}                        |
| Novo cliente        | POST                 | api/clientes      | name, email, tags                            | id, name, email, tagged: {}                        |
| Atualizar cliente   | PUT                  | api/clientes/{id} | name, email or tags                          | id, name, email, tagged: {}                        |
| Delete cliente      | DELETE               | api/clientes/{id} |                                              | null                                               |
| Todas as Tags       | GET                  | api/tags          |                                              | Objetos JSON com todas as tags                     |

### Exemplo de uso

#### Registro de usuário

	curl -X POST http://localhost/api/register \
	 -H "Accept: application/json" \
	 -H "Content-Type: application/json" \
	 -d '{"name": "Paulo", "email": "paulo.dev@example.com", "password": "dev1234", "password_confirmation": "dev1234"}'

#### Retorno:

	{
	    "data": {
	        "api_token":"0syHnl0Y9jOIfszq11EC2CBQwCfObmvscrZYo5o2ilZPnohvndH797nDNyAT",
	        "created_at": "2020-02-20 21:17:15",
	        "email": "paulo.dev@example.com",
	        "id": 51,
	        "name": "Paulo",
	        "updated_at": "2020-02-20 21:17:15"
	    }
	}

#### Login de usuário

	curl -X POST http://localhost/api/login \
	  -H "Accept: application/json" \
	  -H "Content-type: application/json" \
	  -d "{\"email\": \"paulo.dev@example.com\", \"password\": \"dev1234\" }"


#### Retorno:

	{
	    "data": {
	        "id":1,
	        "name":"Paulo",
	        "email":"paulo.dev@example.com",
	        "created_at":"2020-02-20 21:17:15",
	        "updated_at":"2020-02-20 21:17:15",
	        "api_token":"Jll7q0BSijLOrzaOSm5Dr5hW9cJRZAJKOzvDlxjKCXepwAeZ7JR6YP5zQqnw"
	    }
	}

#### Todas as tags

	curl -X GET http://localhost/api/tags \
	  -H "Accept: application/json" \
	  -H "Content-type: application/json"


#### Retorno:

	[
	    {
	        "slug": "participantes-da-maratona",
	        "name": "Participantes da Maratona",
	        "count": 2
	    },
	    {
	        "slug": "alexanne-buckridge",
	        "name": "Alexanne Buckridge",
	        "count": 2
	    },
	    {
	        "slug": "amalia-fahey",
	        "name": "Amalia Fahey",
	        "count": 2
	    }
	]    

#### Paramêtros

Se o parâmetro `key` for informado, será retornado apenas o valor da chave desejada. Exemplo:
	
	curl -X GET http://localhost/api/tags?key=name \
	  -H "Accept: application/json" \
	  -H "Content-type: application/json"

Valores possíveis para o parâmetro `key`: slug, name ou count.


## Aplicação

A aplicação foi conteinerizada a partir de containers do Docker. Serão criados 3 containers:

* *Nginx* ou gerenciador-clientes-nginx (Servidor web)
* *PHP-FPM* ou gerenciador-clientes-app (aplicação com PHP-FPM 7.4 - *FastCGI Process Manager* )
	O composer já vem instalado no container acima. Para conseguirmos utilizar o Laravel e PHPunit para realizar a suíte de testes.
* *MySql* ou gerenciador-clientes-mysql (servidor de banco de dados MySql) - As tabelas do banco de dados são criadas automaticamente com as migrations, mas caso necessário existe um dump.sql no diretório `mysql/dump.sql`.


## Estrutura de diretórios
		clientes-teste
			|
			|-- mysql (arquivos e configs do banco de dados)
				|
				|-- dbdata (arquivos binários do mysql)
				|-- dump.sql (Dump das tabelas com os dados)
			|-- docker-compose.yml (arquivo de configuração docker)
			|-- nginx (diretório do servidor web nginx)
			|-- README.md (documentação do teste)			
			|-- src (arquivos do laravel e diretório da aplicação)
				|
				|-- public (diretório da aplicação)
					|
					|-- classes (as classes que são utilizadas)
						|
						|-- Database (classes responsáveis pela conexão e CRUDS)
						|-- Game.php (Classe com dados das partidas)
						|-- Parser.php (Classe principal responsável por percorrer o log)
						|-- Player.php (Classe com o modelo do Player. Usado para um jogador ou uma arma
					|-- static (diretório dos arquivos estáticos. Ex: css, scss, js, svg, gif)
					|-- controller.php (controlador de ajax requests)
					|-- loader_classes.php (carrega as classes)
					|-- index.php (Arquivo principal index que chama todos os outros arquivos)
				|-- test (Suíte de testes - PHPunit)
				|-- phpunit.xml.dist (arquivo de configuração PHPunit)
				|-- composer.json (Dependências do projeto)
				|-- composer.lock (Dependências do projeto com as versões exatas)
					


## Pré-requisitos para o setup:
* [Docker](https://www.docker.com/products/docker-desktop)
* [Docker-compose](https://docs.docker.com/compose/install/)


### Instalação
1. Descompacte os arquivos para o seu ambiente de desenvolvimento.

2. Caso queira, altere as variáveis de ambiente no arquivo `src/.env`. É nesse arquivo que as credenciais do banco de dados e configurações do Laravel estão definidas. A aplicação utiliza as variáveis de ambiente para realizar todas as operações com o banco de dados.
**Obs.:** O host para se conectar no banco é o nome do container do MySQL.

3. Agora execute o bash script abaixo para rodar os comandos de configuração de um projeto Laravel e criação de todos os containers.

	`sh run.sh`

**Obs.:** Caso algo de errado, você pode abrir o arquivo `run.sh` e executar os comandos individualmente no terminal.

4. Acesse o url da aplicação: 

	* http://127.0.0.1

# Docker
Relação de comandos mais usados no docker

### Lista containers em execução
    docker ps

### Acessar o container
    docker exec -it NOMEDOCONTAINER bash

### Caso algo de errado, você pode forçar a recriação dos containers com o comando:
    docker-compose up --force-recreate

### Parar todos os containers
    docker stop $(docker ps -a -q)

### Liberar espaço em disco 
    docker volume prune

### Remover images/containers
    docker-compose rm #remove containers criados pelo docker-compose
    docker rm $(docker ps -a -q) #remove todos os containers
    docker rmi $(docker images -q -a) #remove todas as imagens

### Listar imagens
    docker images

### Listar redes
    docker network ls