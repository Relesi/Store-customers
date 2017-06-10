<?php

//Detecta Dominio Atual
$dominio = explode(".", $_SERVER['HTTP_HOST']);
$SessionName = $dominio[0];


return array(
   'class'=>'CHttpSession',
    'cookieMode' => 'only',
    'sessionName' => $SessionName,
    'savePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'temp',
);
