### Setup

1. You need PHP 8 or higher, [Composer](https://getcomposer.org/download/) to install PHP packages
   and [Symfony CLI](https://symfony.com/download)
2. You also need database(Mysql, Postgres) and update .env to match your database setup

### Installation

First clone the project

```shell 
git clone https://github.com/MarinoTolj/MyBlog.git
```

Position yourself in project directory and then proceed to run provided commands

```shell
cd MyBlog
```

Before you continue you should create .env file with database url.\
For example (for Mysql): DATABASE_URL="mysql://root:root@127.0.0.1:3306/blog_post"

Install the project's dependencies into vendor

```shell
composer install
``` 

Install javascript dependencies into node_modules

```shell
npm install
```

Create database

```shell
php .\bin\console doctrine:database:create
```

Run migrations

```shell
php .\bin\console doctrine:schema:create
```

Seed database

```shell
php .\bin\console doctrine:fixtures:load
```

Install CKEditor in web directory

```shell
php bin/console assets:install public
```

Build javascript files

```shell
npm run dev
```

or to continuously watch changes

```shell
npm run watch
```

And finally to start server

```shell
symfony server:start
```

Open in browser by running this command. The default URL is http://127.0.0.1:8000

```shell
symfony open:local
```

To login as admin user use:\
email: admin@admin\
pass: Adminadmin1

## Contributing

If you would like to contribute to the project, please feel free to submit a pull request. Before submitting a pull
request, please ensure that your changes follow the existing code style and that all tests pass.
License

## License

This project is licensed under the MIT License.
