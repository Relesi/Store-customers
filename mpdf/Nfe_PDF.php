/*<?php

$arquivo = "arquivos/homologacao/enviadas/aprovadas/" .
    $_REQUEST['emissao'] . "/" . $_REQUEST['chave'] . "-nfe.xml";

if(is_file($arquivo)){

    require_once('Danfe.php');

    $docxml = file_get_contents($arquivo);
    $danfe = new Danfe($docxml, 'P', 'A4', 'images/logo.jpg',
        'I', '');
    $id = $danfe->Danfe.php();
    $Gera = $danfe->Danfe($id . '.pdf', 'I');
} else {
    header("location: http://dscloud.dsyii.com.br/erp/nfe/processaNfe");

}

