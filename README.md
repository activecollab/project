## Creating a new project

Use Composer to create a new project based on this template:

```bash
composer create-project activecollab/project Warhorse 0.10.* --repository=https://github.com/activecollab/project --dev
```

This command will create a project with name Warhorse from a template. Post clone, it does several things:

1. Configures `composer.json` for your project
1. Adjusts application name and namespace (from `ActiveCollab\App` to `ActiveCollab\Warhorse`)
1. Adjusts environment variable names (prefix is changed from `APP_` to `WARHORSE_`)

## Staring a web server

To start a PHP built-in web server, run:

```bash
php -S 0.0.0.0:8888 -t public public/index.php
```
xw

## Running tests

To run tests, `cd` to project's root and run:

```bash
phpunit
```