# parlatube php api

## installation:

### 1. composer
- composer install
- rename sample.config.php to config.php
fix db connection in config.php

R::freeze(true) - no on the fly tables/fields will be created 

R::freeze(false) - tables/fields will be created on the fly 

### 2. run:

- composer build or  
- mysql restore (mysqldump.sql) or
- run create.php
- remove create.php
- make folder cache

### 3. usage 
- run composer start or composer start7 (fix path to php7 executable)
- for setSnippet check jquery post in initializr/js/main.js
- if necessary uncomment setHeaders in config.php
- fix .htaccess for CORS, see https://stackoverflow.com/questions/14467673/enable-cors-in-htaccess

