PodR Web Edition
========================

1) Prepare the server
-------------------------------------
- Install nodejs and coffeescript and set the path in config.php


2) Checking your System Configuration
-------------------------------------

Before starting coding, make sure that your local system is properly
configured for Symfony.

Execute the `check.php` script from the command line:

    php app/check.php

The script returns a status code of `0` if all mandatory requirements are met, `1` otherwise.

Access the `config.php` script from a browser:

    http://localhost/path/to/symfony/app/web/config.php

If you get any warnings or recommendations, fix them.

3) Prepare the project
-------------------------------------
- Install the vendors by running: "php composer.phar install"
- Copy parameters.yml.dist to parameters.yml and update the settings
- Chmod 777 app/cache, app/logs and web/uploads
- "chmod +x deploy.sh"
- Setup DB with the command "php app/console doctrine:schema:update --force"

4) Upgrading
-------------------------------------
- Run "php app/console cache:clear -e=prod" to clear the cache
- Run "php app/console assetic:dump --env=prod --no-debug" to dump the css/js resources.
- Or set permissions 755 on "deploy.sh" and run "./deploy.sh"

5) Troubleshooting
-------------------------------------
- Routing does not work:
  Symfony2 DOES NOT work with the module libapache2-mod-php5filter. In order to solve this problem, you have to uninstall libapache2-mod-php5filter. AFter you do that, you have to install libapache2-mod-php5
- Can't access app_dev.php (not on localhost)
  Add your IP to line #14 in app_dev.php
- Blank page in app.dev often mean the cache is not working. Remove and add the app/cache folder and set the permissions to 777.
- Not able to execute deploy.sh file: /bin/bash^M: bad interpreter is solved by "cat deploy.sh | tr -d '\r' > deploy.sh.new"

6) Requirements
-------------------------------------
- PHP >= 5.4.0