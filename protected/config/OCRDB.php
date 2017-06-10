<?php
/**
 * Created by PhpStorm.
 * User: daraujo
 * Date: 12/08/2015
 * Time: 11:20
 */

$dominio = explode(".", $_SERVER['HTTP_HOST']);

// Dados de Acesso ao Banco de Dados
if ($dominio[1] == 'dsnovo' || $dominio[1] == 'dsyii' || $dominio[1] == 'ds-deploy' || $dominio[1] == 'dsdev' ||
    $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == 'localhost' || $_SERVER['REMOTE_ADDR'] == '::1') {

    $DB = 'ocr_dscloud'; // OCR Dev
    $HostDB = '172.17.0.3';
    $LoginDB = 'root';
    $SenhaDB = 'docsystem@123';
} else {

    $DB = 'ocr_'.$dominio[1];
    $HostDB = '10.6.2.1';
    $LoginDB = 'dscloud';
    $SenhaDB = 'w7LthhgfLBKTP7';
}



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
