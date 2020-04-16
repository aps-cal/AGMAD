<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'), /* oracle */

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [
/*        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
        ],
*/
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'db1'),
            'username' => env('DB_USERNAME', 'admin'),
            'password' => env('DB_PASSWORD', 'G10balPAD'),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
/*
        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
        ],
   
        'oracle' => [
            'driver' => 'oracle',
            'host' => '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST = sitspr.warwick.ac.uk)(PORT=1521)))(CONNECT_DATA=(SERVICE_NAME=sitspr.warwick.ac.uk)))',
            'port' => '1521',
            'database' => 'sitspr.warwick.ac.uk',
            'username' => 'CAL_APP',
            'password' => 'frangotyohaz4',
            'charset' => 'utf8',
            'prefix' => '',
        ],'oracle' => [
            'driver' => 'oracle',
            'host' => '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST = sitstr.warwick.ac.uk)(PORT=1521)))(CONNECT_DATA=(SERVICE_NAME=sitstr.warwick.ac.uk)))',
            'port' => '1521',
            'database' => 'sitstr.warwick.ac.uk',
            'username' => 'elsiai',
            'password' => 'A_madTe5t',
            'charset' => 'utf8',
            'prefix' => '',
        ],'oracle' => [
            'driver' => 'oracle',
            'host' => 'sitstr.warwick.ac.uk',
            'port' => '1521',
            'database' => 'sitstr.warwick.ac.uk',
            'username' => 'elsiai',
            'password' => 'A_madTe5t',
            'charset' => 'utf8',
            'prefix' => '', 
        ],*/
        'Oracle' => [
            'driver' => 'oracle',
            'host' => 'sitspr.warwick.ac.uk', //env('SITS_HOST', 'sitspr.warwick.ac.uk'),
            'port' => env('SITS_PORT', '1521'),
            'database' => 'sitspr.warwick.ac.uk', //env('SITS_DATABASE', 'sitspr.warwick.ac.uk'),
            'service_name' => 'sitspr.warwick.ac.uk', //env('SITS_SID', 'sitspr.warwick.ac.uk'),
            'username' => 'CAL_APP', //env('CAL_APP_TR_USERNAME', 'CAL_APP'),
            'password' => 'frangotyohaz4', //env('CAL_APP_TR_PASSWORD', 'frangotyohaz4'),
            'charset' => 'utf8',
            'prefix' => '',
        ],
        'CAL_APP_TR' => [
            'driver' => 'oracle',
            'host' => env('SITS_HOST', 'sitstr.warwick.ac.uk'),
            'port' => env('SITS_PORT', '1521'),
            'database' => env('SITS_DATABASE', 'sitstr.warwick.ac.uk'),
            'service_name' => env('SITS_SID', 'sitstr.warwick.ac.uk'),
            'username' => env('CAL_APP_TR_USERNAME', 'CAL_APP'),
            'password' => env('CAL_APP_TR_PASSWORD', 'frangotyohaz4'),
            'charset' => 'utf8',
            'prefix' => '',
        ],
        'EL_APIUSER_TR' => [
            'driver' => 'oracle',
            'host' => env('TRAPI_HOST', 'sitstr.warwick.ac.uk'),
            'port' => env('TRAPI_PORT', '1521'),
            'database' => env('TRAPI_DATABASE', 'sitstr.warwick.ac.uk'),
            'service_name' => env('TRAPI_SID', 'sitstr.warwick.ac.uk'),
            'username' => env('TRAPI_USERNAME', 'EL_APIUSER'),
            'password' => env('TRAPI_PASSWORD', 'BrownDogHair_23'),
            'charset' => 'utf8',
            'prefix' => '',
        ],
        'EL_APIUSER_PR' => [
            'driver' => 'oracle',
            'host' => 'sitspr.warwick.ac.uk',
            'port' => '1521',
            'database' => 'sitspr.warwick.ac.uk',
            'service_name' => 'sitspr.warwick.ac.uk',
            'username' => 'EL_APIUSER',
            'password' => 'BrownDogHair_23',
            'charset' => 'utf8',
            'prefix' => '',
        ],
        'CAL_APP_PR' => [
            'driver' => 'oracle',
            'host' => 'sitspr.warwick.ac.uk', //env('SITS_HOST', 'sitspr.warwick.ac.uk'),
            'port' => env('SITS_PORT', '1521'),
            'database' => 'sitspr.warwick.ac.uk', //env('SITS_DATABASE', 'sitspr.warwick.ac.uk'),
            'service_name' => 'sitspr.warwick.ac.uk', //env('SITS_SID', 'sitspr.warwick.ac.uk'),
            'username' => 'CAL_APP', //env('CAL_APP_TR_USERNAME', 'CAL_APP'),
            'password' => 'frangotyohaz4', //env('CAL_APP_TR_PASSWORD', 'frangotyohaz4'),
            'charset' => 'utf8',
            'prefix' => '',
        ],
      
    ], 
    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => 'predis',

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],

    ],

];
