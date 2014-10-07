<?php
/**
 * Include settings file. Throw error, if it does not exist.
 */
$settings = dirname(__FILE__).'/../../includes/settings.inc';
if(!file_exists($settings)) die("[main.php] Invalid settings file's path (".$settings.") -- file is missing or not readable.");

require_once $settings;

/**
 * Return main application configuration array.
 */ 
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'My Web Application',

    // preloading 'log' component
    'preload'=>array('log'),

    // autoloading model and component classes
    'import'=>array(
        'application.models.*',
        'application.components.*',
    ),

    'modules'=>array(
        // uncomment the following to enable the Gii tool
        /*
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'Enter Your Password Here',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters'=>array('127.0.0.1','::1'),
        ),
        */
    ),

    // application components
    'components'=>array(
        'user'=>array(
            // enable cookie-based authentication
            'allowAutoLogin'=>true,
        ),
        // uncomment the following to enable URLs in path-format
        /*
        'urlManager'=>array(
            'urlFormat'=>'path',
            'rules'=>array(
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ),
        ),
        */
        
        'db'=>array
        (
            'connectionString'=>(isset($yiihost) && isset($yiiname)) ? 'mysql:host='.$yiihost.';dbname='.$yiiname : 'mysql:host=localhost;dbname=testdrive',
            'emulatePrepare'=>true,
            'username'=>(isset($yiiuser)) ? $yiiuser : 'root',
            'password'=>(isset($yiipass)) ? $yiipass : '',
            'charset'=>'utf8',
        ),
        
        'errorHandler'=>array(
            // use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ),
                // uncomment the following to show log messages on web pages
                /*
                array(
                    'class'=>'CWebLogRoute',
                ),
                */
            ),
        ),
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'=>array
    (
        // this is used in in /protected/components/UserIdentity.php
        'ldapHost'=>(isset($ldapHost)) ? $ldapHost : '',
        'ldapDn'=>(isset($ldapDn)) ? $ldapDn : '',
        
        // this is used in contact page
        'adminEmail'=>(isset($adminEmail)) ? $adminEmail : 'webmaster@example.com',
    ),
);