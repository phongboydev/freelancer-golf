# inapps-modules

[![Build Status](https://travis-ci.org/joemccann/dillinger.svg?branch=master)](https://travis-ci.org/joemccann/dillinger) 

# InApps module structure generator for Laravel Framework.

# Targets
  - To split features into Modules.
  - To define tasks inside a feature.
  - To make any module portable as a standalone package.

### Installation
`composer` is required
```sh
$ composer require inapps/inapps-modules
```
Register Service in `config\app.php`
```sh
\InApps\IAModules\InAppsServiceProvider::class
```

### Usage
```sh
php artisan ia-modules:make <moduleName> <groupName>
```
Eg: 
 - `php artisan ia-modules:make test` will create `modules/admin/Test`
 - `php artisan ia-modules:make testModule group` will create `modules/group/TestModule`

### Todos
 - Add Test

License
----
Apache License, Version 2.0
