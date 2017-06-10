<?php
$PegaURL = 'http://'.$_SERVER['HTTP_HOST']; //Pega URL Atual
$dominio = explode(".", $_SERVER['HTTP_HOST']); // Pega o Dominio Atual

return array(
    'adminEmail' => 'admin@dscloud.com.br',
    'urlFlagAPI' => 'http://api.hostip.info/flag.php?ip=', //http://www.geojoe.co.uk/api/flag/?ip=
    'versao' => 'v1.0.0', //Versão Do Sistema
    'SocketURL' => ''.$_SERVER['HTTP_HOST'].'', // URL do Socket
    'SocketPort' => '3443', //Porta do Socket
    'ThirdParty-WebMail' => ''.$_SERVER['HTTP_HOST'].'/third-party/WebMail2/', // MODIFICADO, o: docsystemcloud *******************
    'localStorage' => 1,
    'localStorageDir' => "/mnt/storage/",

    /*'SocketURL' => ''.$dominio[0].'.docsystemcloud.com.br', // URL do Socket
    'SocketPort' => '3443', //Porta do Socket
    'ThirdParty-WebMail' => ''.$dominio[0].'.dsnovo.com.br/third-party/WebMail/' // MODIFICADO, o: docsystemcloud *******************
    */
);