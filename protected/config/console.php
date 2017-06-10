<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.

$config = array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'My Console Application',

    // preloading 'log' component
    'preload'=>array('log'),

    // application components
    'components'=>array(
        /*'db'=>array(
            'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
        ),*/
        // uncomment the following to use a MySQL database
        //
        //
        //     $HostDB = '172.17.0.3';

        'db'=>array(
            'emulatePrepare' => true,
            'charset' => 'utf8',
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                                        'levels'=>'error, warning',
                ),
            ),
        ),
           ),
);

// die(var_dump($_SERVER));

// Dados de Acesso ao Banco de Dados
if ($_SERVER['LOGNAME'] == 'dev') {
    $config['components']['db']['connectionString'] = 'mysql:host=dscloud.cobre28repba.sa-east-1.rds.amazonaws.com;dbname=demo';
    $config['components']['db']['username'] = 'root';
    $config['components']['db']['password'] = 'd0c$y$t3m';
} else {
    $config['components']['db']['connectionString'] = 'mysql:host=172.17.0.3;dbname=dscloud';
    $config['components']['db']['username'] = 'root';
    $config['components']['db']['password'] = 'docsystem@123';
}

return $config;
