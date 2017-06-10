<?php

namespace NFePHP\NFe;
/**
 * Created by PhpStorm.
 * User: humberto.galletto
 * Date: 29/10/2015
 * Time: 09:42
 */

use NFePHP\NFe\MakeNFe;
use NFePHP\NFe\ToolsNFe;


class DsNFe{

    private $dNFe = array();
    private $chave = '';
    private $xml;
    private $pdf;
    public $erros = array();

    /**
     * StartNFe constructor.
     * @param array $dadosNFe
     */
    public function __construct(array $dNFe)
    {
        $this->dNFe = $dNFe;

    }

    /**
     * Aciona cada um dos metodos que fazem parte do processo de emissao da NFe
     */
    public function processaNFe()
    {
        if($this->criaNFe()){

            $this->assinaNFe();

            return $this->xml;

            if($this->validaNFe()){

                $resp_envia = $this->enviaNFe();
                if($resp_envia){

                    $resp_consulta = $this->consultaReciboNFe();
                    if($resp_consulta){
                        $this->addProtocoloNFe();
                    }
                }
            }

        }
    }

    /**
     * Constroi o XML da NFe atraves da classe MakeNFe
     * @return true se contruiu o xml com sucesso ou false no caso de falha passada para array $this->erros
     */
    function criaNFe()
    {
        $NFe = $this->dNFe['NFe'];
        $Emitente = $this->dNFe['Emitente'];
        $Destinatario = $this->dNFe['Destinatario'];
        $Retirada = $this->dNFe['Retirada'];
        $Entrega = $this->dNFe['Entrega'];
        $NfeRef = $this->dNFe['NfeRef'];
        $NfeRef1A = $this->dNFe['NfeRef1A'];
        $NfeRefProdRural = $this->dNFe['NfeRefProdRural'];
        $CteRef = $this->dNFe['CteRef'];
        $EcfRef = $this->dNFe['EcfRef'];
        $Totais = $this->dNFe['Totais'];
        $Transporte = $this->dNFe['Transporte'];
        $Cobranca = $this->dNFe['Cobranca'];
        $FormaPagamento = $this->dNFe['FormaPagamento'];
        $InfoAdic = $this->dNFe['InfoAdic'];
        $Exporta = $this->dNFe['Exporta'];
        $Compra = $this->dNFe['Compra'];
        $Cana = $this->dNFe['Cana'];


        $nfe = new MakeNFe();

        $this->chave = $nfe->montaChave(  $NFe['cUF'], $NFe['ano'], $NFe['mes'], $Emitente['CNPJ'], $NFe['mod'],
                                    $NFe['serie'], $NFe['nNF'], $NFe['tpEmis'], $NFe['cNF'] );

        $resp = $nfe->taginfNFe($this->chave, $NFe['versao']);

        $resp = $nfe->tagide(   $NFe['cUF'], $NFe['cNF'], $NFe['natOp'], $NFe['indPag'], $NFe['mod'], $NFe['serie'], $NFe['nNF'],
                                $NFe['dhEmi'], $NFe['dhSaiEnt'], $NFe['tpNF'], $NFe['idDest'], $NFe['cMunFG'], $NFe['tpImp'],
                                $NFe['tpEmis'], $NFe['cDV'], $NFe['tpAmb'], $NFe['finNFe'], $NFe['indFinal'], $NFe['indPres'],
                                $NFe['procEmi'], $NFe['verProc'], $NFe['dhCont'], $NFe['xJust'] );

        if($NfeRef['refNFe']) {
            $resp = $nfe->tagrefNFe($NfeRef['refNFe']);

            switch($NfeRef['tipo']) {
                case 0://mod 1A
                    $resp = $nfe->tagrefNF( $NfeRef1A['cUF'], $NfeRef1A['AAMM'], $NfeRef1A['CNPJ'], $NfeRef1A['mod'],
                                            $NfeRef1A['serie'], $NfeRef1A['nNF']);
                    break;
                case 1://prod rural
                    $resp = $nfe->tagrefNFP($NfeRefProdRural['cUF'], $NfeRefProdRural['AAMM'], $NfeRefProdRural['CNPJ'],
                                            $NfeRefProdRural['CPF'], $NfeRefProdRural['IE'],$NfeRefProdRural['mod'],
                                            $NfeRefProdRural['serie'], $NfeRefProdRural['nNF']);
                    break;
                case 2://CT-e
                    $resp = $nfe->tagrefCTe($CteRef['refCTe']);
                    break;
                case 3://cupom fiscal
                    $resp = $nfe->tagrefECF($EcfRef['mod'], $EcfRef['nECF'], $EcfRef['nCOO']);
                    break;
                default:
                    $this->erros[] = "Tipo de NFe Refenciada não escolhido";
                    break;
            }

        }


        $resp = $nfe->tagemit(  $Emitente['CNPJ'], $Emitente['CPF'], $Emitente['xNome'], $Emitente['xFant'], $Emitente['IE'],
                                $Emitente['IEST'], $Emitente['IM'], $Emitente['CNAE'], $Emitente['CRT'] );

        $resp = $nfe->tagenderEmit( $Emitente['xLgr'], $Emitente['nro'], $Emitente['xCpl'], $Emitente['xBairro'], $Emitente['cMun'],
                                    $Emitente['xMun'], $Emitente['UF'], $Emitente['CEP'], $Emitente['cPais'], $Emitente['xPais'],
                                    $Emitente['fone']);

        if($NFe['mod'] == '55' && $Destinatario['CNPJ'] == '' && $Destinatario['CPF']  == ''){
            $this->erros[] = "O destinatário é obrigatório para o mod 55. Verifique o CNPF e/ou o CPF do destinátário.";
        }

        $resp = $nfe->tagdest(  $Destinatario['CNPJ'], $Destinatario['CPF'], $Destinatario['idEstrangeiro'], $Destinatario['xNome'],
                                $Destinatario['indIEDest'], $Destinatario['IE'], $Destinatario['ISUF'], $Destinatario['IM'], $Destinatario['email']);

        $resp = $nfe->tagenderDest( $Destinatario['xLgr'], $Destinatario['nro'], $Destinatario['xCpl'], $Destinatario['xBairro'],
                                    $Destinatario['cMun'], $Destinatario['xMun'], $Destinatario['UF'], $Destinatario['CEP'], $Destinatario['cPais'],
                                    $Destinatario['xPais'],$Destinatario['fone']);

        if(!($Emitente['xLgr'] == $Retirada['xLgr'] && $Emitente['nro'] == $Retirada['nro'])) {
            $resp = $nfe->tagretirada(  $Retirada['CNPJ'], $Retirada['CPF'], $Retirada['xLgr'], $Retirada['nro'], $Retirada['xCpl'],
                                        $Retirada['xBairro'], $Retirada['cMun'], $Retirada['xMun'], $Retirada['UF']);
        }

        if(!($Destinatario['xLgr'] == $Entrega['xLgr'] && $Destinatario['nro'] == $Entrega['nro'])) {
            $resp = $nfe->tagentrega(   $Entrega['CNPJ'], $Entrega['CPF'], $Entrega['xLgr'], $Entrega['nro'], $Entrega['xCpl'], $Entrega['xBairro'],
                                        $Entrega['cMun'], $Entrega['xMun'], $Entrega['UF']);
        }

        foreach($this->dNFe['AutAcessoXML'] as $aut){
            if(!empty($aut['CNPJ']) || !empty($aut['CPF'])){
                $resp = $nfe->tagautXML($aut['CNPJ'], $aut['CPF']);
            }
        }

        foreach($this->dNFe['det'] as $det) {
            $resp = $nfe->tagprod(  $det['nItem'], $det['Produto']['cProd'], $det['Produto']['cEAN'], $det['Produto']['xProd'],
                                    $det['Produto']['NCM'], $det['Produto']['EXTIPI'], $det['Produto']['CFOP'], $det['Produto']['uCom'],
                                    $det['Produto']['qCom'], $det['Produto']['vUnCom'], $det['Produto']['vProd'], $det['Produto']['cEANTrib'],
                                    $det['Produto']['uTrib'], $det['Produto']['qTrib'], $det['Produto']['vUnTrib'], $det['Produto']['vFrete'],
                                    $det['Produto']['vSeg'], $det['Produto']['vDesc'], $det['Produto']['vOutro'], $det['Produto']['indTot'],
                                    $det['Produto']['xPed'], $det['Produto']['nItemPed'], $det['Produto']['nFCI']);


            foreach ($det['Produto']['NVE'] as $nve) {
                $resp = $nfe->tagNVE($det['nItem'], $nve['texto']);
            }


            $resp = $nfe->tagDI(    $det['nItem'], $det['InfoImport']['nDI'], $det['InfoImport']['dDI'], $det['InfoImport']['xLocDesemb'],
                                    $det['InfoImport']['UFDesemb'], $det['InfoImport']['dDesemb'], $det['InfoImport']['tpViaTransp'],
                                    $det['InfoImport']['vAFRMM'], $det['InfoImport']['tpIntermedio'], $det['InfoImport']['CNPJ'],
                                    $det['InfoImport']['UFTerceiro'], $det['InfoImport']['cExportador']);

            $resp = $nfe->tagadi(   $det['nItem'], $det['InfoImport']['nDI'], $det['InfoImport']['Adicoes']['nAdicao'],
                                    $det['InfoImport']['Adicoes']['nSeqAdicC'], $det['InfoImport']['Adicoes']['cFabricante'],
                                    $det['InfoImport']['Adicoes']['vDescDI'], $det['InfoImport']['Adicoes']['nDraw']);

            $resp = $nfe->tagdetExport( $det['nItem'], $det['DetExport']['nDraw'], $det['DetExport']['nRE'],
                                        $det['DetExport']['chNFe'], $det['DetExport']['qExport']);

            switch($det['ProdEspec']['tipo']){
                case 0:
                    $resp = $nfe->tagveicProd($det['nItem'],
                        $det['ProdEspec']['veicProd']['tpOp'],
                        $det['ProdEspec']['veicProd']['chassi'],
                        $det['ProdEspec']['veicProd']['cCor'],
                        $det['ProdEspec']['veicProd']['xCor'],
                        $det['ProdEspec']['veicProd']['pot'],
                        $det['ProdEspec']['veicProd']['cilin'],
                        $det['ProdEspec']['veicProd']['pesoL'],
                        $det['ProdEspec']['veicProd']['pesoB'],
                        $det['ProdEspec']['veicProd']['nSerie'],
                        $det['ProdEspec']['veicProd']['tpComb'],
                        $det['ProdEspec']['veicProd']['nMotor'],
                        $det['ProdEspec']['veicProd']['CMT'],
                        $det['ProdEspec']['veicProd']['dist'],
                        $det['ProdEspec']['veicProd']['anoMod'],
                        $det['ProdEspec']['veicProd']['anoFab'],
                        $det['ProdEspec']['veicProd']['tpPint'],
                        $det['ProdEspec']['veicProd']['tpVeic'],
                        $det['ProdEspec']['veicProd']['espVeic'],
                        $det['ProdEspec']['veicProd']['VIN'],
                        $det['ProdEspec']['veicProd']['condVeic'],
                        $det['ProdEspec']['veicProd']['cMod'],
                        $det['ProdEspec']['veicProd']['cCorDENATRAN'],
                        $det['ProdEspec']['veicProd']['lota'],
                        $det['ProdEspec']['veicProd']['tpRest']);
                    break;
                case 1:
                    $resp = $nfe->tagmed($det['nItem'],
                        $det['ProdEspec']['med']['nLote'],
                        $det['ProdEspec']['med']['qLote'],
                        $det['ProdEspec']['med']['dFab'],
                        $det['ProdEspec']['med']['dVal'],
                        $det['ProdEspec']['med']['vPMC']);
                    break;
                case 2:
                    $resp = $nfe->tagarma($det['nItem'] = '',
                        $det['ProdEspec']['arma']['tpArma'],
                        $det['ProdEspec']['med']['nSerie'],
                        $det['ProdEspec']['med']['nCano'],
                        $det['ProdEspec']['med']['descr']);
                    break;
                case 3:
                    $resp = $nfe->tagcomb($det['nItem'] = '',
                        $det['ProdEspec']['comb']['cProdANP'],
                        $det['ProdEspec']['comb']['pMixGN'],
                        $det['ProdEspec']['comb']['CODIF'],
                        $det['ProdEspec']['comb']['qTemp'],
                        $det['ProdEspec']['comb']['UFCons'],
                        $det['ProdEspec']['comb']['qBCProd'],
                        $det['ProdEspec']['comb']['vAliqProd'],
                        $det['ProdEspec']['comb']['vCIDE']);
                    break;
                case '':
                default:
                    break;
            }


            $resp = $nfe->tagimposto($det['nItem'], $det['Imposto']['vTotTrib']);

            $icms = $det['Imposto']['ICMS'];
            switch($icms['tipo']) {

                case 1:
                    $resp = $nfe->tagICMSPart(  $det['nItem'], $icms['orig'], $icms['cst'], $icms['modBC'],
                                                $icms['vBC'], $icms['pRedBC'], $icms['pICMS'],
                                                $icms['vICMS'], $icms['modBCST'],$icms['pMVAST'], $icms['pRedBCST'],
                                                $icms['vBCST'], $icms['pICMSST'], $icms['vICMSST'], $icms['pBCOp'],$icms['ufST']);
                    break;
                case 2:
                    $resp = $nfe->tagICMSST(    $det['nItem'], $icms['orig'], $icms['cst'], $icms['vBCSTRet'],
                                                $icms['vICMSSTRet'], $icms['vBCSTDest'], $icms['vICMSSTDest']);
                    break;
                case 3:
                    $resp = $nfe->tagICMSSN(    $det['nItem'], $icms['orig'], $icms['csosn'], $icms['modBC'],
                                                $icms['vBC'], $icms['pRedBC'], $icms['pICMS'], $icms['vICMS'],
                                                $icms['pCredSN'], $icms['vCredICMSSN'], $icms['modBCST'],
                                                $icms['pMVAST'], $icms['pRedBCST'], $icms['vBCST'], $icms['pICMSST'],
                                                $icms['vICMSST'], $icms['vBCSTRet'], $icms['vICMSSTRet']);
                    break;
                case 0:
                default:
                    $resp = $nfe->tagICMS(  $det['nItem'], $icms['orig'], $icms['CST'], $icms['modBC'], $icms['pRedBC'],
                                            $icms['vBC'], $icms['pICMS'], $icms['vICMS'], $icms['vICMSDeson'],
                                            $icms['motDesICMS'], $icms['modBCST'], $icms['pMVAST'], $icms['pRedBCST'],
                                            $icms['vBCST'], $icms['pICMSST'], $icms['vICMSST'], $icms['pDif'],
                                            $icms['vICMSDif'], $icms['vICMSOp'], $icms['vBCSTRet'], $icms['vICMSSTRet'] );
                    break;
            }

            $ipi = $det['Imposto']['IPI'];
            $resp = $nfe->tagIPI(   $det['nItem'], $ipi['CST'], $ipi['clEnq'], $ipi['cnpjProd'], $ipi['cSelo'],
                                    $ipi['qSelo'], $ipi['cEnq'], $ipi['vBC'], $ipi['pIPI'],
                                    $ipi['qUnid'], $ipi['vUnid'], $ipi['vIPI']);

            $pis = $det['Imposto']['PIS'];

            switch($pis['tipo']){
                case 1:
                    $resp = $nfe->tagPIS(   $det['nItem'], $pis['CST'], $pis['vBC'], $pis['pPIS'],
                                            $pis['vPIS'], $pis['qBCProd'], $pis['vAliqProd']);
                    break;
                case 0:
                default:
                $resp = $nfe->tagPISST( $det['nItem'], $pis['vBC'], $pis['pPIS'], $pis['qBCProd'],
                                        $pis['vAliqProd'], $pis['vPIS']);
                    break;
            }

            $cofins = $det['Imposto']['COFINS'];

            switch($cofins['tipo']){
                case 1:
                    $resp = $nfe->tagCOFINSST( $det['nItem'], $cofins['vBC'], $cofins['pCOFINS'], $cofins['qBCProd'],
                                                $cofins['vAliqProd'], $cofins['vCOFINS']);
                    break;
                case 0:
                default:
                $resp = $nfe->tagCOFINS(    $det['nItem'], $cofins['CST'], $cofins['vBC'], $cofins['pCOFINS'],
                                            $cofins['vCOFINS'],$cofins['qBCProd'], $cofins['vAliqProd']);
                    break;
            }

            $ii = $det['Imposto']['II'];
            $resp = $nfe->tagII(    $det['nItem'], $ii['vBC'], $ii['vDespAdu'], $ii['vII'], $ii['vIOF']);

            if(!((float)$icms['vBC'] > 0 && (float)$ipi['vBC'] > 0 && (float)$pis['vBC'] > 0 && (float)$cofins['vBC'] > 0 && (float)$ii['vBC'] > 0)) {
                $issqn = $det['Imposto']['ISSQN'];
                $resp = $nfe->tagISSQN($det['nItem'], $issqn['vBC'], $issqn['vAliq'], $issqn['vISSQN'], $issqn['cMunFG'],
                    $issqn['cListServ'], $issqn['vDeducao'], $issqn['vOutro'], $issqn['vDescIncond'],
                    $issqn['vDescCond'], $issqn['vISSRet'], $issqn['indISS'], $issqn['cServico'], $issqn['cMun'],
                    $issqn['cPais'], $issqn['nProcesso'], $issqn['indIncentivo']);
            }

            $resp = $nfe->tagimpostoDevol($det['nItem'], $det['TributosDevolvidos']['pDevol'],$det['TributosDevolvidos']['vIPIDevol'] );

            $resp = $nfe->taginfAdProd($det['nItem'], $det['infAdProd']);

        }

        $resp = $nfe->tagICMSTot(   $Totais['ICMSTot']['vBC'], $Totais['ICMSTot']['vICMS'], $Totais['ICMSTot']['vICMSDeson'],
                                    $Totais['ICMSTot']['vBCST'], $Totais['ICMSTot']['vST'], $Totais['ICMSTot']['vProd'],
                                    $Totais['ICMSTot']['vFrete'], $Totais['ICMSTot']['vSeg'], $Totais['ICMSTot']['vDesc'],
                                    $Totais['ICMSTot']['vII'], $Totais['ICMSTot']['vIPI'],
                                    $Totais['ICMSTot']['vPIS'], $Totais['ICMSTot']['vCOFINS'], $Totais['ICMSTot']['vOutro'],
                                    $Totais['ICMSTot']['vNF'], $Totais['ICMSTot']['vTotTrib'] );

        $resp = $nfe->tagISSQNTot(  $Totais['ISSQNtot']['vServ'], $Totais['ISSQNtot']['vBC'], $Totais['ISSQNtot']['vISS'],
                                    $Totais['ISSQNtot']['vPIS'], $Totais['ISSQNtot']['vCOFINS'], $Totais['ISSQNtot']['dCompet'],
                                    $Totais['ISSQNtot']['vDeducao'], $Totais['ISSQNtot']['vOutro'], $Totais['ISSQNtot']['vDescIncond'],
                                    $Totais['ISSQNtot']['vDescCond'],$Totais['ISSQNtot']['vISSRet'], $Totais['ISSQNtot']['cRegTrib'] );

        $resp = $nfe->tagretTrib(   $Totais['retTrib']['vRetPIS'], $Totais['retTrib']['vRetCOFINS'],
                                    $Totais['retTrib']['vRetCSLL'],$Totais['retTrib']['vBCIRRF'],
                                    $Totais['retTrib']['vIRRF'], $Totais['retTrib']['vBCRetPrev'],
                                    $Totais['retTrib']['vRetPrev']  );

        $resp = $nfe->tagtransp($Transporte['modFrete']);

        $resp = $nfe->tagtransporta(    $Transporte['transporta']['CNPJ'], $Transporte['transporta']['CPF'],
                                        $Transporte['transporta']['xNome'], $Transporte['transporta']['IE'],
                                        $Transporte['transporta']['xEnder'], $Transporte['transporta']['xMun'],
                                        $Transporte['transporta']['UF']);

        $resp = $nfe->tagretTransp( $Transporte['retTransp']['vServ'], $Transporte['retTransp']['vBCRet'],
                                    $Transporte['retTransp']['pICMSRet'], $Transporte['retTransp']['vICMSRet'],
                                    $Transporte['retTransp']['CFOP'], $Transporte['retTransp']['cMunFG']);

        $resp = $nfe->tagveicTransp(    $Transporte['veicTransp']['placa'], $Transporte['veicTransp']['UF'],
                                        $Transporte['veicTransp']['RNTC']);

        foreach($Transporte['reboque'] as $reboque){
            $resp = $nfe->tagreboque($reboque['placa'], $reboque['UF'], $reboque['RNTC'], $reboque['vagao'], $reboque['balsa']);
        }

        foreach($Transporte['vol'] as $volume){
            $resp = $nfe->tagvol(   $volume['qVol'], $volume['esp'], $volume['marca'], $volume['nVol'],
                                    $volume['pesoL'], $volume['pesoB'], $volume['aLacres']);
        }

        $resp = $nfe->tagfat($Cobranca['fat']['nFat'], $Cobranca['fat']['vOrig'], $Cobranca['fat']['vDesc'], $Cobranca['fat']['vLiq']);

        foreach($Cobranca['dup'] as $duplicata){
            $resp = $nfe->tagdup($duplicata['nDup'], $duplicata['dVenc'], $duplicata['vDup']);
        }

        $resp = $nfe->tagpag($FormaPagamento['tPag'], $FormaPagamento['vPag']);

        $resp = $nfe->tagcard($FormaPagamento['card']['CNPJ'], $FormaPagamento['card']['tBand'], $FormaPagamento['card']['cAut']);

        $resp = $nfe->taginfAdic($InfoAdic['infAdFisco'], $InfoAdic['infCpl']);

        foreach($InfoAdic['ObsEmitente'] as $obs_emitente){
            $resp = $nfe->tagobsCont($obs_emitente['xCampo'], $obs_emitente['xTexto']);
        }

        foreach($InfoAdic['ObsFisco'] as $obs_fisco){
            $resp = $nfe->tagobsFisco($obs_fisco['xCampo'], $obs_fisco['xTexto']);
        }

        foreach($InfoAdic['Processos'] as $processo){
            $resp = $nfe->tagprocRef($processo['nProc'], $processo['indProc']);
        }

        $resp = $nfe->tagexporta($Exporta['UFSaidaPais'], $Exporta['xLocExporta'], $Exporta['xLocDespacho']);

        $resp = $nfe->tagcompra($Compra['xNEmp'], $Compra['xPed'], $Compra['xCont']);
        $resp = $nfe->tagcana($Cana['safra'], $Cana['ref']);

        foreach($Cana['forDia'] as $fordia){
            $resp = $nfe->tagforDia($fordia['dia'], $fordia['qtde'], $fordia['qTotMes'], $fordia['qTotAnt'], $fordia['qTotGer']);
        }

        $resp = $nfe->tagdeduc( $Cana['deduc']['xDed'],$Cana['deduc']['vDed'],$Cana['deduc']['vFor'],
                                $Cana['deduc']['vTotDed'],$Cana['deduc']['vLiqFor']);


        $resp = $nfe->montaNFe();

        if($resp){
            $this->xml = $nfe->getXML();
            return true;
        }
        return false;

    }

    /**
     * Insere a assinatura no XML atraves da classe ToolsNFe
     */
    function assinaNFe()
    {
        $nfe = new ToolsNFe('../../config/config.json');

        $this->xml = $nfe->assina($this->xml);
    }

    /**
     * Valida o XML da NFe e lista os erros na array $this->erros
     *
     */
    function validaNFe()
    {

    }

    /**
     * Envia a NFe para a receita atraves da classe ToolsNFe
     * retorna a string de retorno do SEFAZ
     *
     */
    function enviaNFe()
    {

    }

    /**
     * Consulta o recibo para veriguar se a nota foi aprovada atraves da classe ToolsNFe
     * retorna uma array com os dados de retorno
     *
     */
    function consultaReciboNFe()
    {

    }

    /**
     * Adiciona o protocolo na NFe - classe ToolsNFe
     *
     */
    function addProtocoloNFe()
    {

    }

    /**
     * Gera PDF da DANFE e retorna o PDF
     * @return $this->pdf
     */
    public function imprimeNFe()
    {
        return $this->pdf;
    }























}