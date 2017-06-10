<?php

$dominio = explode(".", $_SERVER['HTTP_HOST']);

// Dados de Acesso ao Banco de Dados
if ($dominio[1] == 'dsnovo' || $dominio[1] == 'dsyii' || $dominio[1] == 'ds-deploy' || $dominio[1] == 'dsdev' ||
    $_SERVER['REMOTE_ADDR'] == '10.6.2.1' || $_SERVER['REMOTE_ADDR'] == 'localhost' || $_SERVER['REMOTE_ADDR'] == '::1') {

    $HostDB = '172.17.0.3';
    $LoginDB = 'root';
    $SenhaDB = 'docsystem@123';
} else {

    $HostDB = '10.6.2.1';
    $LoginDB = 'dscloud';
    $SenhaDB = 'w7LthhgfLBKTP7';
}


$DB = 'dev'; // Developer Databasesessoes_limite
return array(
    'class' => 'system.db.CDbConnection',
    'connectionString' => 'mysql:host=' . $HostDB . ';dbname=' . $DB,
    'schemaCachingDuration' => 3600,
    'emulatePrepare' => true,
    'username' => $LoginDB,
    'password' => $SenhaDB,
    'charset' => 'utf8',
    'initSQLs'=>array("set time_zone='+00:00';"),
);
