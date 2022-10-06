# Mandrill PHP API

This project is a fork of the original Mandrill PHP API project rewrited to use PSR-4 loading.

### Installation

```
composer install lesjoursfr/mandrill-api-php
```

### Documentation

The documentation is available [here](https://lesjoursfr.github.io/mandrill-api-php/)

### Development only

To install the Symphony PHP CS you have to run the following commands (assuming you have downloaded [composer.phar](https://getcomposer.org/)) :

```
php composer.phar install
vendor/bin/phpcs --config-set installed_paths vendor/escapestudios/symfony2-coding-standard
```

Then you can check the code style with the following command

```
vendor/squizlabs/php_codesniffer/bin/phpcs --standard=./phpcs.xml --no-cache --parallel=1 ./src
```

To generate the documentation you have to run (assuming you have downloaded [phpDocumentor.phar](https://www.phpdoc.org/)) :

```
php phpDocumentor.phar run -d src/ -t docs
```
