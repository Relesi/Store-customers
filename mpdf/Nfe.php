<?php

/**
 * This is the model class for table "ds_erp_nfe".
 *
 * The followings are the available columns in table 'ds_unidades':
 * @property string $id_erp_nfe
 * @property string $natureza_operacao
 * @property string $serie
 *
 * The followings are the available model relations:
 * @property DsCrmCliente[] $dsCrmClientes
 * @property DsCrmColaborador[] $dsCrmColaboradors
 * @property DsCrmProjeto[] $dsCrmProjetos
 * @property DsErpCompra[] $dsErpCompras
 * @property DsErpContaBancaria[] $dsErpContaBancarias
 * @property DsErpEstoque[] $dsErpEstoques
 * @property DsErpScannerMovimentacao[] $dsErpScannerMovimentacaos
 * @property DsReferenciaUnidadeCategoria $fkIdUnidadeCategoria
 * @property DsCrmFranqueadoFranquia $fkIdFranquia
 * @property DsUsuariosGruposUnidades[] $dsUsuariosGruposUnidades
 * @property DsUsuariosUnidades[] $dsUsuariosUnidades
 */

class Nfe extends CActiveRecord
{


//$classname = 'mPDF';
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'ds_unidades';
    }


	/**
	 * @return array validation rules for model attributes.
	 */

	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(

		);
	}

	/**
	 * @return array relational rules.
	 */

	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'dsVendaFatura' => array(self::HAS_MANY, 'VendaFaturas', 'id_venda_fatura'),
            'dsVendaProduto' => array(self::HAS_MANY, 'VendaProduto', 'fk_id_venda_produto'),
            'dsContasReceber' => array(self::HAS_MANY, 'ContasReceber','fk_id_contas_receber' ),
            'dsUnidades' => array(self::HAS_ONE, 'Unidades', 'fk_id_unidade'),
            'dsUfs' => array(self::HAS_MANY, 'DsUfs', 'sigla'),
            'dsCidades' => array(self::HAS_MANY, 'DsCidades', 'cidade'),
			'dsCrmClientes' => array(self::HAS_MANY, 'DsCrmCliente', 'fk_id_cliente'),
            'dsCrmClienteEndereco' => array(self::HAS_ONE, 'DsCrmCliente', 'fk_id_cliente'),



		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */

	public function attributeLabels()
	{
		return array(
			'id_erp_nfe' => 'Id Nfe',

		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */

	 public function search()
        {
            // @todo Please modify the following code to remove attributes that should not be searched.

            $criteria=new CDbCriteria;

            $criteria->compare('id_unidade',$this->id_unidade,true);
            $criteria->compare('fk_id_unidade_categoria',$this->fk_id_unidade_categoria);
            $criteria->compare('fk_id_franquia',$this->fk_id_franquia);
            $criteria->compare('nome',$this->nome,true);
            $criteria->compare('cnpj',$this->cnpj,true);
            $criteria->compare('ie',$this->ie,true);
            $criteria->compare('im',$this->im,true);
            $criteria->compare('junta_comercial',$this->junta_comercial,true);
            $criteria->compare('junta_comercial_data',$this->junta_comercial_data,true);
            $criteria->compare('cep',$this->cep,true);
            $criteria->compare('endereco',$this->endereco,true);
            $criteria->compare('endereco_numero',$this->endereco_numero,true);
            $criteria->compare('bairro',$this->bairro,true);
            $criteria->compare('complemento',$this->complemento,true);
            $criteria->compare('cidade',$this->cidade,true);
            $criteria->compare('estado',$this->estado,true);
            $criteria->compare('email1',$this->email1,true);
            $criteria->compare('email2',$this->email2,true);
            $criteria->compare('fax',$this->fax,true);
            $criteria->compare('telefone1',$this->telefone1,true);
            $criteria->compare('telefone2',$this->telefone2,true);
            $criteria->compare('ativo',$this->ativo);

            return new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
            ));
        }

        /**
         * Returns the static model of the specified AR class.
         * Please note that you should have this exact method in all your CActiveRecord descendants!
         * @param string $className active record class name.
         * @return Nfe the static model class
         */

		public static function model($className=__CLASS__)
        {
            return parent::modelclassName;
        }

        public function tst_data()
        {
            $dhEmi = str_replace(" ", "T", date("Y-m-d H:i:sP"));
            $dhSaiEnt = str_replace(" ", "T", date("Y-m-d H:i:sP"));
            $tempData = explode("-", $dhEmi);

            $nfeDados = array(

                /* Dados da nota */
                'NFe' => array(
                    'cUF' => '35',//ds_ufs.codigo_ibge                        //C�digo da UF do emitente do Documento Fiscal
                    'cNF' => '00000009',//ds_erp_nfe.id_nfe                   //C�digo Num�rico que comp�e a Chave de Acesso
                    'natOp' => 'venda',//ds_erp_nfe.natureza_operacao         //Descri��o da Natureza da Opera��o
                    'indPag' => '1',//ds_comercial_faturas.parcelas_servico   //0=Pagamento � vista;1=Pagamento a prazo;2=Outros.
                    'mod' => '55',//Padrao todo: posteriormente oferecer op��o da 65
                    'serie' => '100',//ds_erp_nfe.serie                       //S�rie do Documento Fiscal
                    'nNF' => '9',//ds_erp_nfe.id_nfe                          //N�mero do Documento Fiscal
                    'dhEmi' => $dhEmi,//                                      //Data e hora de emiss�o do Documento Fiscal
                    'dhSaiEnt' => ($dhSaiEnt ? $dhSaiEnt : ''),               //OPICIONAL - Data e hora de Sa�da ou da Entrada da Mercadoria/Produto
                    'tpNF' => '1',//Formul�rio                                //Tipo de Opera��o
                    'idDest' => '2',//ds_crm_cliente_endereco.estado          //Identificador de local de destino da opera��o

                    //Parei aqui: 10/11
                    'cMunFG' => '3550308',//Criar Tabela                      //C�digo do Munic�pio de Ocorr�ncia do Fato Gerador
                    'tpImp' => '1',//Formul�rio                               //Formato de Impress�o do DANFE
                    'tpEmis' => '1',//Formul�rio                              //Tipo de Emiss�o da NF-e
                    'cDV' => '4',//Criar Tabela                               //D�gito Verificador da Chave de Acesso da NF-e
                    'tpAmb' => '2',//Cria Tabela                              //Identifica��o do Ambiente (1=Produ��o; 2=Homologa��o)
                    'finNFe' => '1',//Cria Tabela                             //Finalidade de emiss�o da NF-e
                    'indFinal' => '0',//Criar Tabela                          //Indica opera��o com Consumidor final
                    'indPres' => '9',//Formul�rio                             //Indicador de estabelecimento comercial no momento da opera��o
                    'procEmi' => '0',//Criar Tabela                           //Processo de emiss�o da NF-e
                    'verProc' => '3.22.8',//Cria Tabela                       //Vers�o do Processo de emiss�o da NF-e
                    'dhCont' => '',//                                         //Data e Hora da entrada em conting�ncia
                    'xJust' => '',//                                          //Justificativa da entrada em conting�ncia
                    'ano' => $tempData[0] - 2030,//ds_unidade        //campo n�o existente no manual, necess�rio apenas para NFePHP
                    'mes' => $tempData[1],//ds_unidade.mes                    //campo n�o existente no manual, necess�rio apenas para NFePHP
                    'versao' => '3.10'),//                                    //Vers�o do leiaute

                /* Dados do Emitente: unidade */
                'Emitente' => array(
                    'CNPJ' => '58716523000119',// ds_unidades.cnpj.                    //CNPJ do emitente
                    'CPF' => '',// ds_unidades.cpf.                                    //CPF do remetente
                    'xNome' => 'FIMATEC TEXTIL LTDA', //ds_unidades.nome.             //Raz�o Social ou Nome do emitente
                    'xFant' => 'FIMATEC', //Formul�rio
                    'IE' => '112006603110',//ds_unidades.ie.                          //Inscri��o Estadual do Emitente
                    'IEST' => '', //Formu�rio                                         //IE do Substituto Tribut�rio
                    'IM' => '95095870',//Formul�rio                                   //Inscri��o Municipal do Prestador de Servi�o
                    'CNAE' => '0131380',//Formul�rio*                                 //CNAE fiscal
                    'CRT' => '3',//Criar Tabela                                       //C�digo de Regime Tribut�rio
                    'xLgr' => 'RUA DOS PATRIOTAS',//crm_cliente.xlgr.                 //Logradouro
                    'nro' => '897',//ds_unidades.nro                                  //N�mero
                    'xCpl' => 'ARMAZEM 42',// Formul�rio.                             //Complemento
                    'xBairro' => 'IPIRANGA',//ds_unidades.bairro.                     //Bairro
                    'cMun' => '3550308',//Formul�rio                                  //C�digo do munic�pio
                    'xMun' => 'Sao Paulo',//Criar Tabela                              //Nome do munic�pio
                    'UF' => 'SP',//ds.unidades.uf.                                    //Sigla da UF
                    'CEP' => '04207040',//ds_unidades.cep.                            //C�digo do CEP
                    'cPais' => '1058',//ds_unidades.pais.                             //C�digo do Pa�s
                    'xPais' => 'BRASIL',// Criar Tabela                               //Nome do Pa�s
                    'fone' => '1120677300'),//ds_unidade.fone.                        //Telefone

                /* Dados do destinatario: cliente */
                'Destinatario' => array(
                    'CNPJ' => '10702368000155',  //ds_unidade.cnpj.                //CNPJ do destinat�rio
                    'CPF' => '',//ds_unidade.cpf                                   //CPF do destinat�rio
                    'idEstrangeiro' => '',//Formul�rio                             //Identifica��o do destinat�rio no caso de comprador estrangeiro
                    'xNome' => 'M R  TECIDOS ME',//ds_unidade.xnome                //Raz�o Social ou nome do destinat�rio
                    'indIEDest' => '1', //Criar Tabela                             //Indicador da IE do Destinat�rio
                    'IE' => '244827055110',//crm_cliente.ie.                       //Inscri��o Estadual do Destinat�rio
                    'ISUF' => '', //Formul�rio                                     //Inscri��o na SUFRAMA
                    'IM' => '',   //Formul�rio                                     //Inscri��o Municipal do Tomador do Servi�o
                    'email' => 'docsystem@',//ds_unidade.email.                    //Email
                    'xLgr' => 'AV GASPAR RICARDO', //crm_cliente.xlgr              //Logradouro
                    'nro' => '471',//crm_cliente.nro                               //N�mero
                    'xCpl' => '', //Formul�rio                                     //Complemento
                    'xBairro' => 'CENTRO', //crm_cliente.bairro                    //Bairro
                    'cMun' => '4115200',  //Formul�rio                             //C�digo do munic�pio
                    'xMun' => 'Maringa', //Formul�rio                              //Nome do munic�pio
                    'UF' => 'PR', //crm_cliente.uf                                 //Sigla da UF
                    'CEP' => '87040365',//crm_cliente.cep                          //C�digo do CEP
                    'cPais' => '1058', //Criar Tabela                              //C�digo do Pa�s
                    'xPais' => 'BRASIL',//Criar Tabela                             //Nome do Pa�s
                    'fone' => '4430330100'),//crm_cliente.fone                     //Telefone

                /* Retirada: caso os dados de retirada sejam diferentes dos dados da unidade: opcional todo: escolha no formul�rio  */
                'Retirada' => array(
                    'CNPJ' => '12345678901234',//ds_unidade.cnpj.                                   //CNPJ
                    'CPF' => '',//ds_unidade.cpf                                                    //CPF
                    'xLgr' => 'Rua Vanish',//ds_unidade.xlgr                                        //Logradouro
                    'nro' => '000',//ds_unidade.nro                                                 //N�mero
                    'xCpl' => 'Ghost',//ds_unidade.zcpl                                             //Complemento
                    'xBairro' => 'Assombrado',//ds_unidade.cpf                                      //Bairro
                    'cMun' => '3509502',//Criar Tabela                                              //C�digo do munic�pio
                    'xMun' => 'Campinas',//Formul�rio                                               //Nome do munic�pio
                    'UF' => 'SP'),//ds_unidade.uf                                                   //Sigla da UF

                /* Entrega: caso os dados de entrega sejam diferentes dos dados do cliente: opcional todo: escolha no formul�rio  */
                'Entrega' => array(                                                 //Informar somente se diferente do endere�o destinat�rio.
                    'CNPJ' => '12345678901234',//crm_cliente.cnpj                   //CNPJ
                    'CPF' => '',//crm_cliente.cpf                                   //CPF
                    'xLgr' => 'Viela Mixuruca',//crm_cliente.xlgr                  //Logradouro
                    'nro' => '2',//crm_cliente.nro                                 //N�mero
                    'xCpl' => 'Quabrada do malandro',//Formul�rio                  //Complemento
                    'xBairro' => 'Favela Mau Olhado',//crm_cliente.bairro          //Bairro
                    'cMun' => '3509502',//Criar Tabela                             //C�digo do munic�pio
                    'xMun' => 'Campinas',//Criar Tabela                            //Nome do munic�pio
                    'UF' => 'SP'),//crm_cliente.uf                                 //Sigla da UF

                /* Autoriza��o para o CNPJ/CPF que pode acessar o XML: opcional todo: escolha no formul�rio */
                'AutAcessoXML' => array(
                    array(
                        'CNPJ' => '', //ds_unidade.cnpj                             //CNPJ Autorizado
                        'CPF' => '')),//ds_unidade.cpf                              //CPF Autorizado

                /* Notas fiscais referenciadas: opcional todo: escolha no formul�rio */
                'NfeRef' => array(                                                  //Grupo com informa��es de Documentos Fiscais referenciados. Informa��o ut. (
                    'refNFe' => '',                                                 //Referencia uma NF-e (modelo 55) emitida anteriormente, vinculada a NF-e atual, ou uma NFC-e (modelo 65),
                    'tipo'  => 0,                                                  //0 - mod 1A; 1 - Prod Rural; 2 - CT-e; 3 - Cupom fiscal
                    'NfeRef1A' => array(                                                //Informa��o da NF modelo 1/1A referenciada
                        'cUF' => '35',//ds_unidade.uf                                   //C�digo da UF do emitente
                        'AAMM' => '1312', //Formul�rio                                  //Ano e M�s de emiss�o da NF-e
                        'CNPJ' => '12345678901234',//ds_unidade.cnpj                    //CNPJ do emitente
                        'mod' => '1A',                                                  //Modelo do Documento Fiscal
                        'serie' => '1.0',                                                 //S�rie do Documento Fiscal
                        'nNF' => '1234'),                                               //N�mero do Documento Fiscal
                    'NfeRefProdRural' => array(                                         //Informa��es da NF de produtor rural referenciada
                        'cUF' => '35',                                                  //C�digo da UF do emitente
                        'AAMM' => '1312',                                               //Ano e M�s de emiss�o da NF-e
                        'CNPJ ' => '12345678901234',//crm_cliente.cnpj                  //CNPJ do emitente
                        'CPF' => '123456789',//crm_cliente.cpf                          //CPF do emitente
                        'IE' => '123456',////crm_cliente.ie                             //IE do emitente
                        'mod' => '1',//Criar Tabela                                     //Modelo do Documento Fiscal
                        'serie' => '0000000000000000.00',  //Criar Tabela                                 //S�rie do Documento Fiscal
                        'nNF' => '1234'),//Criar Tabela                                 //N�mero do Documento Fiscal
                    'CteRef' => array(
                        'refCTe' => ''),                                                //Chave de acesso do CT-e referenciada
                    'EcfRef' => array(                                                  //Informa��es do Cupom Fiscal referenciado
                        'mod' => '90',  //Criar Tabela                                  //Modelo do Documento Fiscal
                        'nECF' => '12243',//Criar Tabela                                //N�mero de ordem sequencial do ECF
                        'nCOO' => '111')),//Criar Tabela                                 //N�mero do Contador de Ordem de Opera��o - COO

                /* Informa��es dos produtos */
                'det' => array(
                    array(
                        'nItem' => 1,//Auto incrmento partindo de 1

                        /* Informa��es de cada produto */
                        'Produto' => array(
                            //cProd: ds_erp_venda_itens.fk_id_item > ds_producao_produto.id_produto
                            'cProd' => '2517BCB01',                                     //C�digo do produto ou servi�o
                            //cEAN: (se n�o tiver, n�o entra a tag) ds_erp_venda_itens.fk_id_item > ds_producao_produto.id_produto > ds_producao_produto.codigo_barras
                            'cEAN' => '97899072659522',                                 //GTIN (Global Trade Item Number) do produto, antigo c�digo EAN ou c�digo de barras
                            //xProd: ds_erp_venda_itens.fk_id_item > ds_producao_produto.id_produto > ds_producao_produto.nome
                            'xProd' => 'DELFOS 80 MESCLA TINTO C/ AMAC. S.T 1,78M',     //Descri��o do produto ou servi�o
                            //NCM: ds_erp_venda_itens.fk_id_item > ds_producao_produto.id_produto > fk_id_classificacao_fiscal_mercadoria
                            'NCM' => '60063300',                                        //C�digo NCM com 8 d�gitos
                            //NVE: Campo m�ltiplo opcional no Formul�rio todo:reformula��o base de dados produtos?
                            'NVE' => array(
                                array(
                                    'texto' => '')),
                            //EXTIPI: Campo opcional no formul�rio todo:reformula��o base de dados produtos?
                            'EXTIPI' => '',                                             //EX_TIPI
                            //CFOP: Campo de formul�rio (Select) a partir de tabela existente todo:reformula��o base de dados produtos?
                            'CFOP' => '6101',                                           //C�digo Fiscal de Opera��es e Presta��es
                            //uCom: ds_erp_venda_itens.fk_id_item > ds_producao_produto.id_produto > fk_id_unidade_medida > ds_referencia_unidade_medida.simbolo
                            'uCom' => 'KG',                                             //Unidade Comercial do produto
                            //qCom: ds_erp_venda_itens.quantidade
                            'qCom' => '389.9500',                                       //Quantidade Comercial
                            //vUnCom: ds_erp_venda_itens.valor_unitario
                            'vUnCom' => '49.9200000000',                                //Valor Unit�rio de Comercializa��o
                            //vProdvUnCom * qCom
                            'vProd' => '19466.30',                                      //Valor Total Bruto dos Produtos ou Servi�os
                            //cEANTrib: (se n�o tiver, n�o entra a tag) ds_erp_venda_itens.fk_id_item > ds_producao_produto.id_produto > ds_producao_produto.codigo_barras
                            'cEANTrib' => '',                                           //GTIN (Global Trade Item Number) da unidade tribut�vel, antigo c�digo EAN ou c�digo de barras
                            //uTrib: ds_erp_venda_itens.fk_id_item > ds_producao_produto.id_produto > fk_id_unidade_medida > ds_referencia_unidade_medida.simbolo
                            'uTrib' => 'KG',                                            //Unidade Tribut�vel
                            //qCom: ds_erp_venda_itens.quantidade
                            'qTrib' => '389.9500',                                      //Quantidade Tribut�vel
                            //vUnCom: ds_erp_venda_itens.valor_unitario
                            'vUnTrib' => '49.9200000000',                               //Valor Unit�rio de tributa��o
                            //vFrete: Formul�rio
                            'vFrete' => '',                                             //Valor Total do Frete
                            //vSeg: Formul�rio
                            'vSeg' => '',                                               //Valor Total do Seguro
                            //vOutro: Formul�rio
                            'vOutro' => '',                                             //Outras despesas acess�rias
                            //vDesc: formul�rio
                            'vDesc' => '',                                              //Valor do Desconto
                            //indTot: Select no fomul�rio
                            'indTot' => '1',                                            //Indica se valor do Item (vProd) entra no valor total da NF-e (vProd)
                            //ds_erp_venda.num_venda
                            'xPed' => '14972',                                          //N�mero do Pedido de Compra
                            //nItem
                            'nItemPed' => '1',                                          //Item do Pedido de Compra
                            //nFCI: Formul�rio
                            'nFCI' => ''),                                              //N�mero de controle da FCI - Ficha de Conte�do de Importa��o

                        /* Infos de importa��o dos produtos: opcional todo:reformula��o base de dados produtos?*/
                        'InfoImport' => array(
                            //Formul�rio: escolha se o item � de importa��o. Abrir demais itens em caso afirmativo
                            //nDI: Formul�rio
                            'nDI' => '',                                       //N�mero do Documento de Importa��o (DI, DSI, DIRE, ...)
                            //dDI: Formul�rio
                            'dDI' => '2013-12-23',                                      //Data de Registro do documento
                            //xLocDesemb: Formul�rio
                            'xLocDesemb' => 'SANTOS',                                   //Local de desembara�o
                            //UFDesemb: Select alimentado por ds_ufs.sigla
                            'UFDesemb' => 'SP',                                         //Sigla da UF onde ocorreu o Desembara�o Aduaneiro
                            //dDesemb: Formul�rio
                            'dDesemb' => '2013-12-23',                                  //Data do Desembara�o Aduaneiro
                            //tpViaTransp: Formul�rio: Select com 1=Mar�tima;2=Fluvial;3=Lacustre;4=A�rea;5=Postal;6=Ferrovi�ria;7=Rodovi�ria;8=Conduto / Rede Transmiss�o;9=Meios Pr�prios;10=Entrada / Sa�da ficta.11=Courier;12=Handcarry.
                            'tpViaTransp' => '1',                                       //Via de transporte internacional informada na Declara��o de Importa��o (DI)
                            //vAFRMM: Formul�rio: somente no caso da escolha do Select anterior ser 1=Mar�tima
                            'vAFRMM' => '1.00',                                         //Valor da AFRMM - Adicional ao Frete para Renova��o da Marinha Mercante
                            //tpIntermedio: Formul�rio: Select com 1=Importa��o por conta pr�pria;2=Importa��o por conta e ordem;3=Importa��o por encomenda;
                            'tpIntermedio' => '0',                                      //Forma de importa��o quanto a intermedia��o
                            //CNPJ: Formul�rio: input no caso do select anterior ser 2 ou 3
                            'CNPJ' => '',                                               //CNPJ do adquirente ou do encomendante
                            //UFTerceiro: Select alimentado por ds_ufs.sigla no caso do select anterior ser 2 ou 3
                            'UFTerceiro' => '',                                         //Sigla da UF do adquirente ou do encomendante
                            //cExportador: formul�rio
                            'cExportador' => '111',                                     //C�digo do Exportador
                            //Formul�rio para m�ltiplas entradas
                            'Adicoes' => array(
                                //nAdicao: Auto incremento a partir de 1
                                'nAdicao' => '1',                                       //Numero da Adi��o
                                //nSeqAdicC: nAdicao
                                'nSeqAdicC' => '1111',                                  //Numero sequencial do item dentro da Adi��o
                                //cFabricante: Formul�rio
                                'cFabricante' => 'seila',                               //C�digo do fabricante estrangeiro
                                //vDescDI: Formul�rio
                                'vDescDI' => '0.00',                                    //Valor do desconto do item da DI � Adi��o
                                //nDraw: Formul�rio com valida��o: O n�mero do Ato Concess�rio de Suspens�o deve ser preenchido com 11 d�gitos (AAAANNNNNND) e o n�mero do Ato Concess�rio de Drawback Isen��o deve ser preenchido com 9 d�gitos (AANNNNNND). (Observa��o inclu�da na NT 2013/005 v. 1.10)
                                'nDraw' => '9393939')),                                 //N�mero do ato concess�rio de Drawback

                        /* Infos de exporta��o: opcional todo:reformula��o base de dados produtos?*/
                        'DetExport' => array(
                            //nDraw: Formul�rio com valida��o: O n�mero do Ato Concess�rio de Suspens�o deve ser preenchido com 11 d�gitos (AAAANNNNNND) e o n�mero do Ato Concess�rio de Drawback Isen��o deve ser preenchido com 9 d�gitos (AANNNNNND). (Observa��o inclu�da na NT 2013/005 v. 1.10)
                            'nDraw' => '',                                              //N�mero do ato concess�rio de Drawback
                            //nRE: formul�rio
                            'nRE' => '',                                                //N�mero do Registro de Exporta��o
                            //chNFe: formul�rio
                            'chNFe' => '',                                              //Chave de Acesso da NF-e recebida para exporta��o
                            //qExport: formul�rio
                            'qExport' => ''),                                           //Quantidade do item realmente exportado

                        /* Produtos com infos espec�ficas: opcional todo:reformula��o base de dados produtos? */
                        'ProdEspec' => array(                                           //Apenas 1 dos itens ou nenhum - prever regras na entrada de dados e no tratamento de erros
                            //tipo: select com sem produto especifico;Ve�culos novos;medicamentos;arma;combustiveis
                            'tipo' => '',                                               //vazio - sem produto especifico; 0 - Ve�culos novos; 1 - medicamentos; 2 - arma; 3 - combustiveis
                            //Formul�rio: caso tipo seja 0
                            'veicProd' => array(                                        //Detalhamento de Ve�culos novos
                                'tpOp' => '',                                           //Tipo da opera��o
                                'chassi' => '',                                         //Chassi do ve�culo
                                'cCor' => '',                                           //Cor
                                'xCor' => '',                                           //Descri��o da Cor
                                'pot' => '',                                            //Pot�ncia Motor (CV)
                                'cilin' => '',                                          //Cilindradas
                                'pesoL' => '',                                          //Peso L�quido
                                'pesoB' => '',                                          //Peso Bruto
                                'nSerie' => '',                                         //Serial (s�rie)
                                'tpComb' => '',                                         //Tipo de combust�vel
                                'nMotor' => '',                                         //N�mero de Motor
                                'CMT' => '',                                            //Capacidade M�xima de Tra��o
                                'dist' => '',                                           //Dist�ncia entre eixos
                                'anoMod' => '',                                         //Ano Modelo de Fabrica��o
                                'anoFab' => '',                                         //Ano de Fabrica��o
                                'tpPint' => '',                                         //Tipo de Pintura
                                'tpVeic' => '',                                         //Tipo de Ve�culo
                                'espVeic' => '',                                        //Esp�cie de Ve�culo
                                'VIN' => '',                                            //Condi��o do VIN
                                'condVeic' => '',                                       //Condi��o do Ve�culo
                                'cMod' => '',                                           //C�digo Marca Modelo
                                'cCorDENATRAN' => '',                                   //C�digo da Cor
                                'lota' => '',                                           //Capacidade m�xima de lota��o
                                'tpRest' => ''),                                        //Restri��o
                            //Formul�rio: caso tipo seja 1
                            'med' => array(                                             //Detalhamento de Medicamentos e de mat�rias-primas farmac�uticas
                                'nLote' => '',                                          //N�mero do Lote de medicamentos ou de mat�rias-primas farmac�uticas
                                'qLote' => '',                                          //Quantidade de produto no Lote de medicamentos ou de mat�rias-primas farmac�uticas
                                'dFab' => '',                                           //Data de fabrica��o
                                'dVal' => '',                                           //Data de validade
                                'vPMC' => ''),                                          //Pre�o m�ximo consumidor
                            //Formul�rio: caso tipo seja 2
                            'arma' => array(                                            //Detalhamento de Armamento
                                'tpArma' => '',                                         //Indicador do tipo de arma de fogo
                                'nSerie' => '',                                         //N�mero de s�rie da arma
                                'nCano' => '',                                          //N�mero de s�rie do cano
                                'descr' => ''),                                         //Descri��o completa da arma, compreendendo: calibre, marca, capacidade, tipo de funcionamento, comprimento e demais elementos que permitam a sua perfeita identifica��o.
                            //Formul�rio: caso tipo seja 3
                            'comb' => array(                                            //Informa��es espec�ficas para combust�veis l�quidos e lubrificantes
                                'cProdANP' => '',                                       //C�digo de produto da ANP
                                'pMixGN' => '',                                         //Percentual de G�s Natural para o produto GLP (cProdANP=210203001)
                                'CODIF' => '',                                          //C�digo de autoriza��o / registro do CODIF
                                'qTemp' => '',                                          //Quantidade de combust�vel faturada � temperatura ambiente.
                                'UFCons' => '',                                         //Sigla da UF de consumo
                                'qBCProd' => '',                                        //BC da CIDE
                                'vAliqProd' => '',                                      //Valor da al�quota da CIDE
                                'vCIDE' => ''),                                         //Valor da CIDE
                                'nRECOPI' => ''),                                       //N�mero do RECOPI

                        /* Infos de imposto para cada produto todo:reformula��o base de dados produtos? */
                        'Imposto' => array(                                             //Grupo ISSQN mutuamente exclusivo com os grupos ICMS e II, isto �, se o grupo ISSQN for informado os grupos ICMS e II n�o ser�o informados e vice-versa.
                            //totaliza��o dos tributos por produto
                            'vTotTrib' => '',                                           //Valor aproximado total de tributos federais, estaduais e municipais.
                            //todo:reformula��o base de dados produtos x impostos?
                            'ICMS' => array(                                            //A diferenca entre os ICMS serao identificadas pelo campo cst
                                'tipo' => '0',                                           //0 - normal; 1 - Partilha; 2 - Subst Tributaria; 3 - Simples
                                'orig' => '1',                                          //Origem da mercadoria
                                'CST' => '00',                                          //Tributa��o do ICMS: o que vai estabelecer a diferenca entre os ICMS
                                'modBC' => '3',                                         //Modalidade de determina��o da BC do ICMS
                                'pRedBC' => '',                                         //Percentual da Redu��o de BC
                                'vBC' => '19466.30',                                    //Valor da BC do ICMS
                                'pICMS' => '12.00',                                     //Al�quota do imposto
                                'vICMS' => '2335.96',                                   //Valor do ICMS
                                'vICMSDeson' => '',                                     //Valor do ICMS desonerado
                                'motDesICMS' => '',                                     //Motivo da desonera��o do ICMS
                                'modBCST' => '',                                        //Modalidade de determina��o da BC do ICMS ST
                                'pMVAST' => '',                                         //Percentual da margem de valor Adicionado do ICMS ST
                                'pRedBCST' => '',                                       //Percentual da Redu��o de BC do ICMS ST
                                'vBCST' => '',                                          //Valor da BC do ICMS ST
                                'pICMSST' => '',                                        //Al�quota do imposto do ICMS ST
                                'vICMSST' => '',                                        //Valor do ICMS ST
                                'pDif' => '',                                           //Percentual do diferimento
                                'vICMSDif' => '',                                       //Valor do ICMS diferido
                                'vICMSOp' => '',                                        //Valor do ICMS da Opera��o
                                'vBCSTRet' => '',                                       //Valor da BC do ICMS ST retido
                                'vICMSSTRet' => '',                                     //Valor do ICMS ST retido
                                'pBCOp' => '',                                          //Percentual da BC opera��o pr�pria
                                'UFST' => '',                                           //UF para qual � devido o ICMS ST
                                'CSOSN' => '',                                          //C�digo de Situa��o da Opera��o � Simples Nacional
                                'pCredSN' => '',                                        //Al�quota aplic�vel de c�lculo do cr�dito (Simples Nacional).
                                'vCredICMSSN' => ''),                                   //Valor cr�dito do ICMS que pode ser aproveitado nos termos do art. 23 da LC 123 (Simples Nacional)
                            //todo:reformula��o base de dados produtos x impostos?
                            'IPI' => array(                                             //Informar apenas quando o item for sujeito ao IPI
                                'CST' => '',                                          //C�digo da situa��o tribut�ria do IPI
                                'clEnq' => '',                                          //Classe de enquadramento do IPI para Cigarros e Bebidas
                                'cnpjProd' => '',                                       //CNPJ do produtor da mercadoria, quando diferente do emitente. Somente para os casos de exporta��o direta ou indireta.
                                'cSelo' => '',                                          //C�digo do selo de controle IPI
                                'qSelo' => '',                                          //Quantidade de selo de controle
                                'cEnq' => '999',                                        //C�digo de Enquadramento Legal do IPI
                                'vBC' => '',                                            //Valor da BC do IPI
                                'pIPI' => '',                                           //Al�quota do IPI
                                'qUnid' => '',                                          //Quantidade total na unidade padr�o para tributa��o (somente para os produtos tributados por unidade)
                                'vUnid' => '',                                          //Valor por Unidade Tribut�vel
                                'vIPI' => ''),                                          //Valor do IPI
                            //todo:reformula��o base de dados produtos x impostos?
                            'PIS' => array(
                                'tipo' => '0',                                          //0 - PIS normal; 1 - PISST
                                'CST' => '01',                                          //C�digo de Situa��o Tribut�ria do PIS
                                'vBC' => '19466.30',                                    //Valor da Base de C�lculo do PIS
                                'pPIS' => '1.65',                                       //Al�quota do PIS (em percentual)
                                'vPIS' => '321.19',                                     //Valor do PIS
                                'qBCProd' => '',                                        //Quantidade Vendida
                                'vAliqProd' => ''),                                     //Al�quota do PIS (em reais)
                            //todo:reformula��o base de dados produtos x impostos?
                            'COFINS' => array(
                                'tipo' => '0',                                          //0 - CONFINS; 1 - COFINSST
                                'CST' => '01',                                          //C�digo de Situa��o Tribut�ria da COFINS
                                'vBC' => '19466.30',                                    //Valor da Base de C�lculo da COFINS
                                'pCOFINS' => '7.60',                                    //Al�quota da COFINS (em percentual)
                                'vCOFINS' => '1479.44',                                 //Valor da COFINS
                                'qBCProd' => '',                                        //Quantidade Vendida
                                'vAliqProd' => ''),                                     //Al�quota da COFINS (em reais)
                            //todo:reformula��o base de dados produtos x impostos?
                            'II' => array(
                                'vBC' => '',                                            //Valor BC do Imposto de Importa��o
                                'vDespAdu' => '',                                       //Valor despesas aduaneiras
                                'vII' => '',                                            //Valor Imposto de Importa��o
                                'vIOF' => ''),                                          //Valor Imposto sobre Opera��es Financeiras
                            //todo:reformula��o base de dados produtos x impostos?
                            'ISSQN' => array(                                           //todo:excludente com rela��o ao ICMS, PIS, CONFINS, etc. Tratar entrada de dados e erros
                                'vBC' => '',                                            //Valor da Base de C�lculo do ISSQN
                                'vAliq' => '',                                          //Al�quota do ISSQN
                                'vISSQN' => '',                                         //Valor do ISSQN
                                'cMunFG' => '',                                         //C�digo do munic�pio de ocorr�ncia do fato gerador do ISSQN
                                'cListServ' => '',                                      //Item da Lista de Servi�os
                                'vDeducao' => '',                                       //Valor dedu��o para redu��o da Base de c�lculo
                                'vOutro' => '',                                         //Valor outras reten��es
                                'vDescIncond' => '',                                    //Valor desconto incondicionado
                                'vDescCond' => '',                                      //Valor desconto condicionado
                                'vISSRet' => '',                                        //Valor reten��o ISS
                                'indISS' => '',                                         //Indicador da exigibilidade do ISS
                                'cServico' => '',                                       //C�digo do servi�o prestado dentro do munic�pio
                                'cMun' => '',                                           //C�digo do Munic�pio de incid�ncia do imposto
                                'cPais' => '',                                          //C�digo do Pa�s onde o servi�o foi prestado
                                'nProcesso' => '',                                      //N�mero do processo judicial ou administrativo de suspens�o da exigibilidade
                                'indIncentivo' => '')),                                    //Indicador de incentivo Fiscal
                        //Grupo de campos no caso de haver tributos devolvidos
                        'TributosDevolvidos' => array(
                            'pDevol' => '',                                             //Percentual da mercadoria devolvida
                            'vIPIDevol' => ''),                                         //Valor do IPI devolvido
                        //infAdProd: input no NFe para cada produto
                        'infAdProd' => 'TAMANHO XG')),                                             //Norma referenciada, informa��es complementares, etc.

                /* Totaliza��es */
                'Totais' => array(
                    //Totaliza��es das informa��es dispon�veis
                    'ICMSTot' => array(
                        'vBC' => '19466.30',                                        //Base de C�lculo do ICMS
                        'vICMS' => '2335.96',                                       //Valor Total do ICMS
                        'vICMSDeson' => '0.00',                                     //Valor Total do ICMS desonerado
                        'vBCST' => '0.00',                                          //Base de C�lculo do ICMS ST
                        'vST' => '0.00',                                            //Valor Total do ICMS ST
                        'vProd' => '19466.30',                                      //Valor Total dos produtos e servi�os
                        'vFrete' => '0.00',                                         //Valor Total do Frete
                        'vSeg' => '0.00',                                           //Valor Total do Seguro
                        'vDesc' => '0.00',                                          //Valor Total do Desconto
                        'vII' => '0.00',                                            //Valor Total do II
                        'vIPI' => '0.00',                                           //Valor Total do IPI
                        'vPIS' => '321.19',                                         //Valor do PIS
                        'vCOFINS' => '1479.44',                                     //Valor da COFINS
                        'vOutro' => '0.00',                                         //Outras Despesas acess�rias
                        'vNF' => '19466.30',                                        //Valor Total da NF-e
                        'vTotTrib' => ''),                                          //Valor aproximado total de tributos federais, estaduais e municipais.
                    //Totaliza��es das informa��es dispon�veis
                    'ISSQNtot' => array(
                        'vServ' => '',                                              //Valor total dos Servi�os sob n�o-incid�ncia ou n�o tributados pelo ICMS
                        'vBC' => '',                                                //Valor total Base de C�lculo do ISS
                        'vISS' => '',                                               //Valor total do ISS
                        'vPIS' => '',                                               //Valor total do PIS sobre servi�os
                        'vCOFINS' => '',                                            //Valor total da COFINS sobre servi�os
                        'dCompet' => '',                                            //Data da presta��o do servi�o
                        'vDeducao' => '',                                           //Valor total dedu��o para redu��o da Base de C�lculo
                        'vOutro' => '',                                             //Valor total outras reten��es
                        'vDescIncond' => '',                                        //Valor total desconto incondicionado
                        'vDescCond' => '',                                          //Valor total desconto condicionado
                        'vISSRet' => '',                                            //Valor total reten��o ISS
                        'cRegTrib' => ''),                                          //C�digo do Regime Especial de Tributa��o
                    //Grupo caso haja valores retidos
                    'retTrib' => array(
                        //vRetPIS: input no NFe
                        'vRetPIS' => '',                                            //Valor Retido de PIS
                        //vRetCOFINS: input no NFe
                        'vRetCOFINS' => '',                                         //Valor Retido de COFINS
                        //vRetCSLL: input no NFe
                        'vRetCSLL' => '',                                           //Valor Retido de CSLL
                        //vBCIRRF: input no NFe
                        'vBCIRRF' => '',                                            //Base de C�lculo do IRRF
                        //vIRRF: input no NFe
                        'vIRRF' => '',                                              //Valor Retido do IRRF
                        //vBCRetPrev: input no NFe
                        'vBCRetPrev' => '',                                         //Base de C�lculo da Reten��o da Previd�ncia Social
                        //vRetPrev: input no NFe
                        'vRetPrev' => '')),                                         //Valor da Reten��o da Previd�ncia Social

                /* Infos de transporte todo: campos a serem preenchidos na emiss�o da nota */
                'Transporte' => array(
                    //modFrete: select 0=Por conta do emitente;1=Por conta do destinat�rio/remetente;2=Por conta de terceiros;9=Sem frete. (V2.0)
                    'modFrete' => '0',                                              //Modalidade do frete

                    /* Infos Transportadora: todo: opcional - formul�rio de escolha ou preenchimento */
                    'transporta' => array(
                        //CNPJ: input todo: em cadastro de transportadora?
                        'CNPJ' => '',                                               //CNPJ do Transportador
                        //CPF: input todo: em cadastro de transportadora?
                        'CPF' => '12345678901',                                     //CPF do Transportador
                        //xNome: input todo: em cadastro de transportadora?
                        'xNome' => 'Ze da Carroca',                                 //Raz�o Social ou nome
                        //IE:  input todo: em cadastro de transportadora?
                        'IE' => '',                                                 //Inscri��o Estadual do Transportador
                        //xEnder: input todo: em cadastro de transportadora?
                        'xEnder' => 'Beco Escuro',                                  //Endere�o Completo
                        //xMun: input todo: em cadastro de transportadora?
                        'xMun' => 'Campinas',                                       //Nome do munic�pio
                        //UF: select todo: em cadastro de transportadora?
                        'UF' => 'SP'),                                              //Sigla da UF

                    /* Grupo caso haja reten��o relativa a transporte todo: opcional - formul�rio de escolha ou preenchimento */
                    'retTransp' => array(                                           //Grupo Reten��o ICMS transporte
                        //vServ: input NFe
                        'vServ' => '258.69',                                        //Valor do Servi�o
                        //vBCRet: input NFe
                        'vBCRet' => '258.69',                                       //BC da Reten��o do ICMS
                        //pICMSRet: input NFe
                        'pICMSRet' => '10.00',                                      //Al�quota da Reten��o
                        //vICMSRet: input NFe
                        'vICMSRet' => '25.87',                                      //Valor do ICMS Retido
                        //CFOP: Select Nfe
                        'CFOP' => '5352',                                           //CFOP de Servi�o de Transporte (Anexo XIII.03).
                        //cMunFG: Select NFe
                        'cMunFG' => '3509502'),                                     //C�digo do munic�pio de ocorr�ncia do fato gerador do ICMS do transporte

                    /*Grupo caso haja informa��es adicionais de ve�culo trator todo: opcional - formul�rio de escolha ou preenchimento */
                    'veicTransp' => array(                                          //Grupo Ve�culo Transporte
                        //placa: Input NFe
                        'placa' => 'AAA1212',                                       //Placa do Ve�culo
                        //UF: Select NFe
                        'UF' => 'SP',                                               //Sigla da UF
                        //RNTC: Input NFe
                        'RNTC' => '12345678'),                                      //Registro Nacional de Transportador de Carga (ANTT)

                    /*Grupo caso haja informa��es de reboque todo: opcional - formul�rio de escolha ou preenchimento */
                    'reboque' => array(
                        array(
                            //placa: Input NFe
                            'placa' => '',                                          //Placa do Ve�culo
                            //UF: Select NFe
                            'UF' => '',                                             //Sigla da UF
                            //RNTC: Input NFe
                            'RNTC' => '',                                           //Registro Nacional de Transportador de Carga (ANTT)
                            //vagao: Input NFe
                            'vagao' => '',                                          //Identifica��o do vag�o
                            //balsa: Input NFe
                            'balsa' => '')),                                        //Identifica��o da balsa

                    /* Volumes da nota todo: formul�rio para preenchimento de cada volume */
                    'vol' => array(
                        array(
                            //qVol: Input NFe
                            'qVol' => '24',                                         //Quantidade de volumes transportados
                            //esp: Input NFe
                            'esp' => 'VOLUMES',                                     //Esp�cie dos volumes transportados
                            //marca: INput NFe
                            'marca' => '',                                          //Marca dos volumes transportados
                            //nVol: Input NFe
                            'nVol' => '',                                           //Numera��o dos volumes transportados
                            //pesoL: Input NFe
                            'pesoL' => '389.950',                                   //Peso L�quido (em kg)
                            //pesoB: Input NFe
                            'pesoB' => '399.550',                                   //Peso Bruto (em kg)
                            'aLacres' => array(                                     //Grupo Lacres: se n�o tiver lacre a array deve ser vazia
                                //nLacre: input NFe
                                'nLacre' => '2')))),                                 //N�mero dos Lacres

                /* Infos de cobran�a */
                'Cobranca' => array(
                    //todo: tabela de fatura?
                    'fat' => array(                                                 //Grupo Fatura
                        'nFat' => '000034189',                                      //N�mero da Fatura
                        'vOrig' => '19466.30',                                      //Valor Original da Fatura
                        'vDesc' => '',                                              //Valor do desconto
                        'vLiq' => '19466.30'),                                      //Valor L�quido da Fatura
                    //todo: tabela de duplicata?
                    'dup' => array(                                                 //Grupo Duplicata
                        array(
                            'nDup' => '',                                           //N�mero da Duplicata
                            'dVenc' => '',                                          //Data de vencimento
                            'vDup' => ''))),                                        //Valor da duplicata

                /* Forma de pagamento */
                'FormaPagamento' => array(                                          //Grupo obrigat�rio para a NFC-e, a crit�rio da UF. N�o informar para a NF-e.
                    //tPag: select com 01=Dinheiro;02=Cheque;03=Cart�o de Cr�dito;04=Cart�o de D�bito;05=Cr�dito Loja;10=Vale Alimenta��o;11=Vale Refei��o;12=Vale Presente;13=Vale Combust�vel;99=Outros
                    'tPag' => '',                                                   //Forma de pagamento
                    //vPag:input Nfe
                    'vPag' => '',                                                   //Valor do Pagamento
                    //Se tPag = 3
                    'card' => array(                                                //Grupo de Cart�es
                        //CNPJ: input NFe
                        'CNPJ' => '31551765000143',                                 //CNPJ da Credenciadora de cart�o de cr�dito e/ou d�bito
                        //tBand: input NFe
                        'tBand' => '01',                                            //Bandeira da operadora de cart�o de cr�dito e/ou d�bito
                        //cAut: input NFe
                        'cAut' => 'AB254FC79001')),                                 //N�mero de autoriza��o da opera��o cart�o de cr�dito e/ou d�bito

                /* Informa��es adicionais todo: formul�rio */
                'InfoAdic' => array(
                    //infAdFisco: input NFe
                    'infAdFisco' => 'SAIDA C/ SUSP IPI CONF ART 29 DA LEI 10.637',  //Informa��es Adicionais de Interesse do Fisco
                    //infCpl: input NFe
                    'infCpl' => '',                                                 //Informa��es Complementares de interesse do Contribuinte
                    'ObsCont' => array(                                         //Grupo Campo de uso livre do contribuinte
                        array(
                            'xCampo' => '',                                         //Identifica��o do campo
                            'xTexto' => '')),                                       //Conte�do do campo
                    'ObsFisco' => array(                                            //Grupo Campo de uso livre do Fisco
                        array(
                            //xCampo: input NFe
                            'xCampo' => '',                                         //Identifica��o do campo
                            //xTexto: input NFe
                            'xTexto' => '')),                                       //Conte�do do campo
                    'Processos' => array(                                           //Grupo Processo referenciado
                        array(
                            //nProc: input NFe
                            'nProc' => '',                                          //Identificador do processo ou ato concess�rio
                            //indProc: input NFe
                            'indProc' => ''))),                                     //Indicador da origem do processo

                /* Grupo de campos caso a nota for para exporta��o todo: formul�rio condicional se a nota for de exporta��o */
                'Exporta' => array(
                    //UFSaidaPais: Select NFe
                    'UFSaidaPais' => 'SP',                                          //Sigla da UF de Embarque ou de transposi��o de fronteira
                    //xLocExporta: input NFe
                    'xLocExporta' => 'Maritimo',                                    //Descri��o do Local de Embarque ou de transposi��o de fronteira
                    //xLocDespacho: input NFe
                    'xLocDespacho' => 'Porto Santos'),                              //Descri��o do Local de Embarque ou de transposi��o de fronteira

                /* Grupo de campos caso haja informa��es adicionais de compra todo: formul�rio se a nota for de compra */
                'Compra' => array(
                    //xNEmp: input NFe
                    'xNEmp' => '',                                                  //Nota de Empenho
                    //xpED: input NFe
                    'xPed' => '12345',                                              //Pedido
                    //xCont: input NFe
                    'xCont' => 'A342212'),                                          //Contrato

                /* Grupo de campos caso as transa��es envolvam cana todo: formul�rio condicional */
                'Cana' => array(
                    //input NFe
                    'safra' => '',                                              //Identifica��o da safra
                    //input NFe
                    'ref' => '',                                             //M�s e ano de refer�ncia
                    //multiplas entradas input NFe
                    'forDia' => array(                                              //Grupo Fornecimento di�rio de cana
                        array(
                            //input NFe
                            'dia' => '',                                            //Dia
                            //input NFe
                            'qtde' => '',                                           //Quantidade
                            //input NFe
                            'qTotMes' => '',                                        //Quantidade Total do M�s
                            //input NFe
                            'qTotAnt' => '',                                        //Quantidade Total Anterior
                            //input NFe
                            'qTotGer' => '')),                                      //Quantidade Total Geral
                    //Grupo caso haja dedu��es
                    'deduc' => array(                                               //Grupo Dedu��es � Taxas e Contribui��es
                        //input NFe
                        'xDed' => '',                                               //Descri��o da Dedu��o
                        //input NFe
                        'vDed' => '',                                               //Valor da Dedu��o
                        //input NFe
                        'vFor' => '',                                               //Valor dos Fornecimentos
                        //input NFe
                        'vTotDed' => '',                                            //Valor Total da Dedu��o
                        //input NFe
                        'vLiqFor' => '')                                            //Valor L�quido dos Fornecimentos
                )
            );

            return $nfeDados;

        }

}
