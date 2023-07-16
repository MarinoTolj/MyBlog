### Setup

1. `git clone https://github.com/MarinoTolj/MyBlog.git`
2. You need PHP 8 or higher, [Composer](https://getcomposer.org/download/) to install PHP packages and [Symfony CLI](https://symfony.com/download)
### Installation
To install the project's dependencies into vendor
```shell
   composer install
``` 
To install javascript dependencies into node_modules
```shell
npm install
```
To creat database
```shell
php .\bin\console doctrine:database:create
```
To run migrations
```shell
php .\bin\console doctrine:schema:create
```
To seed database
```shell
php .\bin\console doctrine:fixtures:load
```
To build javascript files
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

2. Run 
3. ```shell
3. to create database
3. Run `` to run migrations
4. Run `` to seed database
5. Run ``  or  to build and watch changes in files
6. Run `symfony server:start` to start the server

## Contributing

If you would like to contribute to the project, please feel free to submit a pull request. Before submitting a pull request, please ensure that your changes follow the existing code style and that all tests pass.
License

## License

This project is licensed under the MIT License.
