# Runtime: PHP 8.1.9 with Xdebug 3.1.5 with Composer 2.3.10

# INSTALL Dependencies

```bash
composer install
```

# RUN application

```bash
php src/app.php data/input.txt
```

# RUN tests with coverage

## You must have xDebug PHP extension installed and enabled

```bash
XDEBUG_MODE=coverage ./vendor/bin/phpunit -c config/phpunit.xml
```

[SEE Code Coverage Results](/documentation/codecoverage/php-code-coverage/index.html)
