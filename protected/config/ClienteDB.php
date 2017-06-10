<?php

$dominio = explode(".", $_SERVER['HTTP_HOST']);

// Dados de Acesso ao Banco de Dados
if ($dominio[1] == 'dsnovo' || $dominio[1] == 'dsyii' || $dominio[1] == 'ds-deploy' || $dominio[1] == 'dsdev' ||
    $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == 'localhost' || $_SERVER['REMOTE_ADDR'] == '::1') {

    $HostDB = '';
    $LoginDB = '';
    $SenhaDB = '';
} else {

    $HostDB = '';
    $LoginDB = '';
    $SenhaDB = '';
}
//Detecta Dominio Atual e Salva a ConexÃ£o

if ($dominio[0] == 'dscloud' || $dominio[0] == 'docsystem')
    $DBCliente = 'dscloud';
else
    $DBCliente = $dominio[0];


return array(
    'class' => 'system.db.CDbConnection',
    'connectionString' => 'mysql:host=' . $HostDB . ';dbname=' . $DBCliente,
    'schemaCachingDuration' => 3600,
    'emulatePrepare' => true,
    'username' => $LoginDB,
    'password' => $SenhaDB,
    'charset' => 'utf8',
    'initSQLs'=>array("set time_zone='+00:00';"),
    'enableParamLogging' => true,
);
