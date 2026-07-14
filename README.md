# UserAPI

API REST desenvolvida em **PHP** com autenticação **JWT** e operações completas de **CRUD** para gerenciamento de usuários, utilizando **SQLite** como banco de dados.

##Funcionalidades

- Autenticação com JWT
- Cadastro de usuários
- Listagem de usuários
- Busca por ID
- Atualização de usuários
- Exclusão de usuários

## 🛠️ Tecnologias

- PHP
- SQLite
- Composer
- JWT (`firebase/php-jwt`)

##Instalação

Clone o repositório:

```bash
git clone https://github.com/seu-usuario/userapi.git
cd userapi
```

Instale as dependências:

```bash
composer install
```

Crie o arquivo `.env` a partir do exemplo:

```bash
cp .env.example .env
```

Inicie o servidor:

```bash
php -S localhost:8000 -t public
```

##Autenticação

As rotas protegidas exigem um token JWT no cabeçalho da requisição:

```text
Authorization: Bearer SEU_TOKEN
```

##Endpoints

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| POST | `/login` | Login |
| POST | `/usuarios` | Criar usuário |
| GET | `/usuarios` | Listar usuários |
| GET | `/usuarios/{id}` | Buscar usuário |
| PUT | `/usuarios/{id}` | Atualizar usuário |
| DELETE | `/usuarios/{id}` | Remover usuário |

##Autor

Desenvolvido por **Marcos**.
