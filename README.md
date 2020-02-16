# yii2-oauth-client-example
Example application to demonstrate the implementation of an OAuth-Client with Yii 2

### Getting started
 - $ `git clone https://github.com/cebe/yii2-oauth-client-example.git`
 - $ `cd yii2-oauth-client-example`
 - $ `composer install`
 - create new file `config/db.php`
 - put content in that file as:
 ```php
<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=oauth-jwt-client',
    'username' => 'PLACEHOLDER', // replace this
    'password' => 'PLACEHOLDER', // replace this
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
```
  - adjust DB name, user and password accordingly in above file & make sure you have created above database externally (for e.g. phpmyadmin)
  - hit migration by $ `./yii migrate`
  - start server by $ ` php -S localhost:7878`
  - visit http://localhost:7878/web/index.php in browser
  - now you should have this web app running in browser


### Next step


 - change client config in `config/web.php`
```php
<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
	...
    'components' => [
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'github' => [ // github added to learn and understand our own client
                    'class' => \Da\User\AuthClient\GitHub::class,
                    'clientId' => '',
                    'clientSecret' => ''
                ],
                'oauthserver' => [
                    'class' => \app\components\OauthServerDaClient::class,
                    'clientId' => 1, // replace this
                    'clientSecret' => 'bbA-ANTt5qPCMOcYUxOO0bcoN6TLIN_Ib8OA7QFAr9zyf7jLAyVjfmY2OJ4Hs3rY', // replace this
                ],
```
 - replace `clientId` and `clientSecret` to that of Oauth server (e.g. client info can be found at http://localhost:7876/web/index.php?r=oauth%2Fclient%2Fview&id=1)
 - click the link at the bottom of login page with text `Login/Signup with Oauth Server`

### Notes


 - Package `league/oauth2-server` is not required. But to decode JWT and get data from it, it is needed. And also Oauth server's public key is needed
 - this example only implements [Authorization code grant](https://oauth2.thephpleague.com/authorization-server/auth-code-grant/)
 - this app is for only server rendered app (a typical PHP MVC app) and not for SPA (Single page application)
 - No public or private keys needs to be generated in client app


### JWT token

In order to get JWT data (we stored in header; see server file `yii2-oauth-server-example/modules/oauth/oauth/entities/AccessTokenEntity.php` )

 - make property `showApiCallExample` of `app\controllers\SiteController` class to true and
 - if both oauth server and client app are in same dir then no more steps needed but if not make corresponding change for public.key (of server) file path in method `getServerPublicKeyPath()`


JWT & Calling resource server API example is also shown in `SiteController` -> `actionIndex()`
