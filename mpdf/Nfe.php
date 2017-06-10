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
                    'cUF' => '35',//ds_ufs.codigo_ibge                        //Código da UF do emitente do Documento Fiscal
                    'cNF' => '00000009',//ds_erp_nfe.id_nfe                   //Código Numérico que compõe a Chave de Acesso
                    'natOp' => 'venda',//ds_erp_nfe.natureza_operacao         //Descrição da Natureza da Operação
                    'indPag' => '1',//ds_comercial_faturas.parcelas_servico   //0=Pagamento à vista;1=Pagamento a prazo;2=Outros.
                    'mod' => '55',//Padrao todo: posteriormente oferecer opção da 65
                    'serie' => '100',//ds_erp_nfe.serie                       //Série do Documento Fiscal
                    'nNF' => '9',//ds_erp_nfe.id_nfe                          //Número do Documento Fiscal
                    'dhEmi' => $dhEmi,//                                      //Data e hora de emissão do Documento Fiscal
                    'dhSaiEnt' => ($dhSaiEnt ? $dhSaiEnt : ''),               //OPICIONAL - Data e hora de Saída ou da Entrada da Mercadoria/Produto
                    'tpNF' => '1',//Formulário                                //Tipo de Operação
                    'idDest' => '2',//ds_crm_cliente_endereco.estado          //Identificador de local de destino da operação

                    //Parei aqui: 10/11
                    'cMunFG' => '3550308',//Criar Tabela                      //Código do Município de Ocorrência do Fato Gerador
                    'tpImp' => '1',//Formulário                               //Formato de Impressão do DANFE
                    'tpEmis' => '1',//Formulário                              //Tipo de Emissão da NF-e
                    'cDV' => '4',//Criar Tabela                               //Dígito Verificador da Chave de Acesso da NF-e
                    'tpAmb' => '2',//Cria Tabela                              //Identificação do Ambiente (1=Produção; 2=Homologação)
                    'finNFe' => '1',//Cria Tabela                             //Finalidade de emissão da NF-e
                    'indFinal' => '0',//Criar Tabela                          //Indica operação com Consumidor final
                    'indPres' => '9',//Formulário                             //Indicador de estabelecimento comercial no momento da operação
                    'procEmi' => '0',//Criar Tabela                           //Processo de emissão da NF-e
                    'verProc' => '3.22.8',//Cria Tabela                       //Versão do Processo de emissão da NF-e
                    'dhCont' => '',//                                         //Data e Hora da entrada em contingência
                    'xJust' => '',//                                          //Justificativa da entrada em contingência
                    'ano' => $tempData[0] - 2030,//ds_unidade        //campo não existente no manual, necessário apenas para NFePHP
                    'mes' => $tempData[1],//ds_unidade.mes                    //campo não existente no manual, necessário apenas para NFePHP
                    'versao' => '3.10'),//                                    //Versão do leiaute

                /* Dados do Emitente: unidade */
                'Emitente' => array(
                    'CNPJ' => '58716523000119',// ds_unidades.cnpj.                    //CNPJ do emitente
                    'CPF' => '',// ds_unidades.cpf.                                    //CPF do remetente
                    'xNome' => 'FIMATEC TEXTIL LTDA', //ds_unidades.nome.             //Razão Social ou Nome do emitente
                    'xFant' => 'FIMATEC', //Formulário
                    'IE' => '112006603110',//ds_unidades.ie.                          //Inscrição Estadual do Emitente
                    'IEST' => '', //Formuário                                         //IE do Substituto Tributário
                    'IM' => '95095870',//Formulário                                   //Inscrição Municipal do Prestador de Serviço
                    'CNAE' => '0131380',//Formulário*                                 //CNAE fiscal
                    'CRT' => '3',//Criar Tabela                                       //Código de Regime Tributário
                    'xLgr' => 'RUA DOS PATRIOTAS',//crm_cliente.xlgr.                 //Logradouro
                    'nro' => '897',//ds_unidades.nro                                  //Número
                    'xCpl' => 'ARMAZEM 42',// Formulário.                             //Complemento
                    'xBairro' => 'IPIRANGA',//ds_unidades.bairro.                     //Bairro
                    'cMun' => '3550308',//Formulário                                  //Código do município
                    'xMun' => 'Sao Paulo',//Criar Tabela                              //Nome do município
                    'UF' => 'SP',//ds.unidades.uf.                                    //Sigla da UF
                    'CEP' => '04207040',//ds_unidades.cep.                            //Código do CEP
                    'cPais' => '1058',//ds_unidades.pais.                             //Código do País
                    'xPais' => 'BRASIL',// Criar Tabela                               //Nome do País
                    'fone' => '1120677300'),//ds_unidade.fone.                        //Telefone

                /* Dados do destinatario: cliente */
                'Destinatario' => array(
                    'CNPJ' => '10702368000155',  //ds_unidade.cnpj.                //CNPJ do destinatário
                    'CPF' => '',//ds_unidade.cpf                                   //CPF do destinatário
                    'idEstrangeiro' => '',//Formulário                             //Identificação do destinatário no caso de comprador estrangeiro
                    'xNome' => 'M R  TECIDOS ME',//ds_unidade.xnome                //Razão Social ou nome do destinatário
                    'indIEDest' => '1', //Criar Tabela                             //Indicador da IE do Destinatário
                    'IE' => '244827055110',//crm_cliente.ie.                       //Inscrição Estadual do Destinatário
                    'ISUF' => '', //Formulário                                     //Inscrição na SUFRAMA
                    'IM' => '',   //Formulário                                     //Inscrição Municipal do Tomador do Serviço
                    'email' => 'docsystem@',//ds_unidade.email.                    //Email
                    'xLgr' => 'AV GASPAR RICARDO', //crm_cliente.xlgr              //Logradouro
                    'nro' => '471',//crm_cliente.nro                               //Número
                    'xCpl' => '', //Formulário                                     //Complemento
                    'xBairro' => 'CENTRO', //crm_cliente.bairro                    //Bairro
                    'cMun' => '4115200',  //Formulário                             //Código do município
                    'xMun' => 'Maringa', //Formulário                              //Nome do município
                    'UF' => 'PR', //crm_cliente.uf                                 //Sigla da UF
                    'CEP' => '87040365',//crm_cliente.cep                          //Código do CEP
                    'cPais' => '1058', //Criar Tabela                              //Código do País
                    'xPais' => 'BRASIL',//Criar Tabela                             //Nome do País
                    'fone' => '4430330100'),//crm_cliente.fone                     //Telefone

                /* Retirada: caso os dados de retirada sejam diferentes dos dados da unidade: opcional todo: escolha no formulário  */
                'Retirada' => array(
                    'CNPJ' => '12345678901234',//ds_unidade.cnpj.                                   //CNPJ
                    'CPF' => '',//ds_unidade.cpf                                                    //CPF
                    'xLgr' => 'Rua Vanish',//ds_unidade.xlgr                                        //Logradouro
                    'nro' => '000',//ds_unidade.nro                                                 //Número
                    'xCpl' => 'Ghost',//ds_unidade.zcpl                                             //Complemento
                    'xBairro' => 'Assombrado',//ds_unidade.cpf                                      //Bairro
                    'cMun' => '3509502',//Criar Tabela                                              //Código do município
                    'xMun' => 'Campinas',//Formulário                                               //Nome do município
                    'UF' => 'SP'),//ds_unidade.uf                                                   //Sigla da UF

                /* Entrega: caso os dados de entrega sejam diferentes dos dados do cliente: opcional todo: escolha no formulário  */
                'Entrega' => array(                                                 //Informar somente se diferente do endereço destinatário.
                    'CNPJ' => '12345678901234',//crm_cliente.cnpj                   //CNPJ
                    'CPF' => '',//crm_cliente.cpf                                   //CPF
                    'xLgr' => 'Viela Mixuruca',//crm_cliente.xlgr                  //Logradouro
                    'nro' => '2',//crm_cliente.nro                                 //Número
                    'xCpl' => 'Quabrada do malandro',//Formulário                  //Complemento
                    'xBairro' => 'Favela Mau Olhado',//crm_cliente.bairro          //Bairro
                    'cMun' => '3509502',//Criar Tabela                             //Código do município
                    'xMun' => 'Campinas',//Criar Tabela                            //Nome do município
                    'UF' => 'SP'),//crm_cliente.uf                                 //Sigla da UF

                /* Autorização para o CNPJ/CPF que pode acessar o XML: opcional todo: escolha no formulário */
                'AutAcessoXML' => array(
                    array(
                        'CNPJ' => '', //ds_unidade.cnpj                             //CNPJ Autorizado
                        'CPF' => '')),//ds_unidade.cpf                              //CPF Autorizado

                /* Notas fiscais referenciadas: opcional todo: escolha no formulário */
                'NfeRef' => array(                                                  //Grupo com informações de Documentos Fiscais referenciados. Informação ut. (
                    'refNFe' => '',                                                 //Referencia uma NF-e (modelo 55) emitida anteriormente, vinculada a NF-e atual, ou uma NFC-e (modelo 65),
                    'tipo'  => 0,                                                  //0 - mod 1A; 1 - Prod Rural; 2 - CT-e; 3 - Cupom fiscal
                    'NfeRef1A' => array(                                                //Informação da NF modelo 1/1A referenciada
                        'cUF' => '35',//ds_unidade.uf                                   //Código da UF do emitente
                        'AAMM' => '1312', //Formulário                                  //Ano e Mês de emissão da NF-e
                        'CNPJ' => '12345678901234',//ds_unidade.cnpj                    //CNPJ do emitente
                        'mod' => '1A',                                                  //Modelo do Documento Fiscal
                        'serie' => '1.0',                                                 //Série do Documento Fiscal
                        'nNF' => '1234'),                                               //Número do Documento Fiscal
                    'NfeRefProdRural' => array(                                         //Informações da NF de produtor rural referenciada
                        'cUF' => '35',                                                  //Código da UF do emitente
                        'AAMM' => '1312',                                               //Ano e Mês de emissão da NF-e
                        'CNPJ ' => '12345678901234',//crm_cliente.cnpj                  //CNPJ do emitente
                        'CPF' => '123456789',//crm_cliente.cpf                          //CPF do emitente
                        'IE' => '123456',////crm_cliente.ie                             //IE do emitente
                        'mod' => '1',//Criar Tabela                                     //Modelo do Documento Fiscal
                        'serie' => '0000000000000000.00',  //Criar Tabela                                 //Série do Documento Fiscal
                        'nNF' => '1234'),//Criar Tabela                                 //Número do Documento Fiscal
                    'CteRef' => array(
                        'refCTe' => ''),                                                //Chave de acesso do CT-e referenciada
                    'EcfRef' => array(                                                  //Informações do Cupom Fiscal referenciado
                        'mod' => '90',  //Criar Tabela                                  //Modelo do Documento Fiscal
                        'nECF' => '12243',//Criar Tabela                                //Número de ordem sequencial do ECF
                        'nCOO' => '111')),//Criar Tabela                                 //Número do Contador de Ordem de Operação - COO

                /* Informações dos produtos */
                'det' => array(
                    array(
                        'nItem' => 1,//Auto incrmento partindo de 1

                        /* Informações de cada produto */
                        'Produto' => array(
                            //cProd: ds_erp_venda_itens.fk_id_item > ds_producao_produto.id_produto
                            'cProd' => '2517BCB01',                                     //Código do produto ou serviço
                            //cEAN: (se não tiver, não entra a tag) ds_erp_venda_itens.fk_id_item > ds_producao_produto.id_produto > ds_producao_produto.codigo_barras
                            'cEAN' => '97899072659522',                                 //GTIN (Global Trade Item Number) do produto, antigo código EAN ou código de barras
                            //xProd: ds_erp_venda_itens.fk_id_item > ds_producao_produto.id_produto > ds_producao_produto.nome
                            'xProd' => 'DELFOS 80 MESCLA TINTO C/ AMAC. S.T 1,78M',     //Descrição do produto ou serviço
                            //NCM: ds_erp_venda_itens.fk_id_item > ds_producao_produto.id_produto > fk_id_classificacao_fiscal_mercadoria
                            'NCM' => '60063300',                                        //Código NCM com 8 dígitos
                            //NVE: Campo múltiplo opcional no Formulário todo:reformulação base de dados produtos?
                            'NVE' => array(
                                array(
                                    'texto' => '')),
                            //EXTIPI: Campo opcional no formulário todo:reformulação base de dados produtos?
                            'EXTIPI' => '',                                             //EX_TIPI
                            //CFOP: Campo de formulário (Select) a partir de tabela existente todo:reformulação base de dados produtos?
                            'CFOP' => '6101',                                           //Código Fiscal de Operações e Prestações
                            //uCom: ds_erp_venda_itens.fk_id_item > ds_producao_produto.id_produto > fk_id_unidade_medida > ds_referencia_unidade_medida.simbolo
                            'uCom' => 'KG',                                             //Unidade Comercial do produto
                            //qCom: ds_erp_venda_itens.quantidade
                            'qCom' => '389.9500',                                       //Quantidade Comercial
                            //vUnCom: ds_erp_venda_itens.valor_unitario
                            'vUnCom' => '49.9200000000',                                //Valor Unitário de Comercialização
                            //vProdvUnCom * qCom
                            'vProd' => '19466.30',                                      //Valor Total Bruto dos Produtos ou Serviços
                            //cEANTrib: (se não tiver, não entra a tag) ds_erp_venda_itens.fk_id_item > ds_producao_produto.id_produto > ds_producao_produto.codigo_barras
                            'cEANTrib' => '',                                           //GTIN (Global Trade Item Number) da unidade tributável, antigo código EAN ou código de barras
                            //uTrib: ds_erp_venda_itens.fk_id_item > ds_producao_produto.id_produto > fk_id_unidade_medida > ds_referencia_unidade_medida.simbolo
                            'uTrib' => 'KG',                                            //Unidade Tributável
                            //qCom: ds_erp_venda_itens.quantidade
                            'qTrib' => '389.9500',                                      //Quantidade Tributável
                            //vUnCom: ds_erp_venda_itens.valor_unitario
                            'vUnTrib' => '49.9200000000',                               //Valor Unitário de tributação
                            //vFrete: Formulário
                            'vFrete' => '',                                             //Valor Total do Frete
                            //vSeg: Formulário
                            'vSeg' => '',                                               //Valor Total do Seguro
                            //vOutro: Formulário
                            'vOutro' => '',                                             //Outras despesas acessórias
                            //vDesc: formulário
                            'vDesc' => '',                                              //Valor do Desconto
                            //indTot: Select no fomulário
                            'indTot' => '1',                                            //Indica se valor do Item (vProd) entra no valor total da NF-e (vProd)
                            //ds_erp_venda.num_venda
                            'xPed' => '14972',                                          //Número do Pedido de Compra
                            //nItem
                            'nItemPed' => '1',                                          //Item do Pedido de Compra
                            //nFCI: Formulário
                            'nFCI' => ''),                                              //Número de controle da FCI - Ficha de Conteúdo de Importação

                        /* Infos de importação dos produtos: opcional todo:reformulação base de dados produtos?*/
                        'InfoImport' => array(
                            //Formulário: escolha se o item é de importação. Abrir demais itens em caso afirmativo
                            //nDI: Formulário
                            'nDI' => '',                                       //Número do Documento de Importação (DI, DSI, DIRE, ...)
                            //dDI: Formulário
                            'dDI' => '2013-12-23',                                      //Data de Registro do documento
                            //xLocDesemb: Formulário
                            'xLocDesemb' => 'SANTOS',                                   //Local de desembaraço
                            //UFDesemb: Select alimentado por ds_ufs.sigla
                            'UFDesemb' => 'SP',                                         //Sigla da UF onde ocorreu o Desembaraço Aduaneiro
                            //dDesemb: Formulário
                            'dDesemb' => '2013-12-23',                                  //Data do Desembaraço Aduaneiro
                            //tpViaTransp: Formulário: Select com 1=Marítima;2=Fluvial;3=Lacustre;4=Aérea;5=Postal;6=Ferroviária;7=Rodoviária;8=Conduto / Rede Transmissão;9=Meios Próprios;10=Entrada / Saída ficta.11=Courier;12=Handcarry.
                            'tpViaTransp' => '1',                                       //Via de transporte internacional informada na Declaração de Importação (DI)
                            //vAFRMM: Formulário: somente no caso da escolha do Select anterior ser 1=Marítima
                            'vAFRMM' => '1.00',                                         //Valor da AFRMM - Adicional ao Frete para Renovação da Marinha Mercante
                            //tpIntermedio: Formulário: Select com 1=Importação por conta própria;2=Importação por conta e ordem;3=Importação por encomenda;
                            'tpIntermedio' => '0',                                      //Forma de importação quanto a intermediação
                            //CNPJ: Formulário: input no caso do select anterior ser 2 ou 3
                            'CNPJ' => '',                                               //CNPJ do adquirente ou do encomendante
                            //UFTerceiro: Select alimentado por ds_ufs.sigla no caso do select anterior ser 2 ou 3
                            'UFTerceiro' => '',                                         //Sigla da UF do adquirente ou do encomendante
                            //cExportador: formulário
                            'cExportador' => '111',                                     //Código do Exportador
                            //Formulário para múltiplas entradas
                            'Adicoes' => array(
                                //nAdicao: Auto incremento a partir de 1
                                'nAdicao' => '1',                                       //Numero da Adição
                                //nSeqAdicC: nAdicao
                                'nSeqAdicC' => '1111',                                  //Numero sequencial do item dentro da Adição
                                //cFabricante: Formulário
                                'cFabricante' => 'seila',                               //Código do fabricante estrangeiro
                                //vDescDI: Formulário
                                'vDescDI' => '0.00',                                    //Valor do desconto do item da DI – Adição
                                //nDraw: Formulário com validação: O número do Ato Concessório de Suspensão deve ser preenchido com 11 dígitos (AAAANNNNNND) e o número do Ato Concessório de Drawback Isenção deve ser preenchido com 9 dígitos (AANNNNNND). (Observação incluída na NT 2013/005 v. 1.10)
                                'nDraw' => '9393939')),                                 //Número do ato concessório de Drawback

                        /* Infos de exportação: opcional todo:reformulação base de dados produtos?*/
                        'DetExport' => array(
                            //nDraw: Formulário com validação: O número do Ato Concessório de Suspensão deve ser preenchido com 11 dígitos (AAAANNNNNND) e o número do Ato Concessório de Drawback Isenção deve ser preenchido com 9 dígitos (AANNNNNND). (Observação incluída na NT 2013/005 v. 1.10)
                            'nDraw' => '',                                              //Número do ato concessório de Drawback
                            //nRE: formulário
                            'nRE' => '',                                                //Número do Registro de Exportação
                            //chNFe: formulário
                            'chNFe' => '',                                              //Chave de Acesso da NF-e recebida para exportação
                            //qExport: formulário
                            'qExport' => ''),                                           //Quantidade do item realmente exportado

                        /* Produtos com infos específicas: opcional todo:reformulação base de dados produtos? */
                        'ProdEspec' => array(                                           //Apenas 1 dos itens ou nenhum - prever regras na entrada de dados e no tratamento de erros
                            //tipo: select com sem produto especifico;Veículos novos;medicamentos;arma;combustiveis
                            'tipo' => '',                                               //vazio - sem produto especifico; 0 - Veículos novos; 1 - medicamentos; 2 - arma; 3 - combustiveis
                            //Formulário: caso tipo seja 0
                            'veicProd' => array(                                        //Detalhamento de Veículos novos
                                'tpOp' => '',                                           //Tipo da operação
                                'chassi' => '',                                         //Chassi do veículo
                                'cCor' => '',                                           //Cor
                                'xCor' => '',                                           //Descrição da Cor
                                'pot' => '',                                            //Potência Motor (CV)
                                'cilin' => '',                                          //Cilindradas
                                'pesoL' => '',                                          //Peso Líquido
                                'pesoB' => '',                                          //Peso Bruto
                                'nSerie' => '',                                         //Serial (série)
                                'tpComb' => '',                                         //Tipo de combustível
                                'nMotor' => '',                                         //Número de Motor
                                'CMT' => '',                                            //Capacidade Máxima de Tração
                                'dist' => '',                                           //Distância entre eixos
                                'anoMod' => '',                                         //Ano Modelo de Fabricação
                                'anoFab' => '',                                         //Ano de Fabricação
                                'tpPint' => '',                                         //Tipo de Pintura
                                'tpVeic' => '',                                         //Tipo de Veículo
                                'espVeic' => '',                                        //Espécie de Veículo
                                'VIN' => '',                                            //Condição do VIN
                                'condVeic' => '',                                       //Condição do Veículo
                                'cMod' => '',                                           //Código Marca Modelo
                                'cCorDENATRAN' => '',                                   //Código da Cor
                                'lota' => '',                                           //Capacidade máxima de lotação
                                'tpRest' => ''),                                        //Restrição
                            //Formulário: caso tipo seja 1
                            'med' => array(                                             //Detalhamento de Medicamentos e de matérias-primas farmacêuticas
                                'nLote' => '',                                          //Número do Lote de medicamentos ou de matérias-primas farmacêuticas
                                'qLote' => '',                                          //Quantidade de produto no Lote de medicamentos ou de matérias-primas farmacêuticas
                                'dFab' => '',                                           //Data de fabricação
                                'dVal' => '',                                           //Data de validade
                                'vPMC' => ''),                                          //Preço máximo consumidor
                            //Formulário: caso tipo seja 2
                            'arma' => array(                                            //Detalhamento de Armamento
                                'tpArma' => '',                                         //Indicador do tipo de arma de fogo
                                'nSerie' => '',                                         //Número de série da arma
                                'nCano' => '',                                          //Número de série do cano
                                'descr' => ''),                                         //Descrição completa da arma, compreendendo: calibre, marca, capacidade, tipo de funcionamento, comprimento e demais elementos que permitam a sua perfeita identificação.
                            //Formulário: caso tipo seja 3
                            'comb' => array(                                            //Informações específicas para combustíveis líquidos e lubrificantes
                                'cProdANP' => '',                                       //Código de produto da ANP
                                'pMixGN' => '',                                         //Percentual de Gás Natural para o produto GLP (cProdANP=210203001)
                                'CODIF' => '',                                          //Código de autorização / registro do CODIF
                                'qTemp' => '',                                          //Quantidade de combustível faturada à temperatura ambiente.
                                'UFCons' => '',                                         //Sigla da UF de consumo
                                'qBCProd' => '',                                        //BC da CIDE
                                'vAliqProd' => '',                                      //Valor da alíquota da CIDE
                                'vCIDE' => ''),                                         //Valor da CIDE
                                'nRECOPI' => ''),                                       //Número do RECOPI

                        /* Infos de imposto para cada produto todo:reformulação base de dados produtos? */
                        'Imposto' => array(                                             //Grupo ISSQN mutuamente exclusivo com os grupos ICMS e II, isto é, se o grupo ISSQN for informado os grupos ICMS e II não serão informados e vice-versa.
                            //totalização dos tributos por produto
                            'vTotTrib' => '',                                           //Valor aproximado total de tributos federais, estaduais e municipais.
                            //todo:reformulação base de dados produtos x impostos?
                            'ICMS' => array(                                            //A diferenca entre os ICMS serao identificadas pelo campo cst
                                'tipo' => '0',                                           //0 - normal; 1 - Partilha; 2 - Subst Tributaria; 3 - Simples
                                'orig' => '1',                                          //Origem da mercadoria
                                'CST' => '00',                                          //Tributação do ICMS: o que vai estabelecer a diferenca entre os ICMS
                                'modBC' => '3',                                         //Modalidade de determinação da BC do ICMS
                                'pRedBC' => '',                                         //Percentual da Redução de BC
                                'vBC' => '19466.30',                                    //Valor da BC do ICMS
                                'pICMS' => '12.00',                                     //Alíquota do imposto
                                'vICMS' => '2335.96',                                   //Valor do ICMS
                                'vICMSDeson' => '',                                     //Valor do ICMS desonerado
                                'motDesICMS' => '',                                     //Motivo da desoneração do ICMS
                                'modBCST' => '',                                        //Modalidade de determinação da BC do ICMS ST
                                'pMVAST' => '',                                         //Percentual da margem de valor Adicionado do ICMS ST
                                'pRedBCST' => '',                                       //Percentual da Redução de BC do ICMS ST
                                'vBCST' => '',                                          //Valor da BC do ICMS ST
                                'pICMSST' => '',                                        //Alíquota do imposto do ICMS ST
                                'vICMSST' => '',                                        //Valor do ICMS ST
                                'pDif' => '',                                           //Percentual do diferimento
                                'vICMSDif' => '',                                       //Valor do ICMS diferido
                                'vICMSOp' => '',                                        //Valor do ICMS da Operação
                                'vBCSTRet' => '',                                       //Valor da BC do ICMS ST retido
                                'vICMSSTRet' => '',                                     //Valor do ICMS ST retido
                                'pBCOp' => '',                                          //Percentual da BC operação própria
                                'UFST' => '',                                           //UF para qual é devido o ICMS ST
                                'CSOSN' => '',                                          //Código de Situação da Operação – Simples Nacional
                                'pCredSN' => '',                                        //Alíquota aplicável de cálculo do crédito (Simples Nacional).
                                'vCredICMSSN' => ''),                                   //Valor crédito do ICMS que pode ser aproveitado nos termos do art. 23 da LC 123 (Simples Nacional)
                            //todo:reformulação base de dados produtos x impostos?
                            'IPI' => array(                                             //Informar apenas quando o item for sujeito ao IPI
                                'CST' => '',                                          //Código da situação tributária do IPI
                                'clEnq' => '',                                          //Classe de enquadramento do IPI para Cigarros e Bebidas
                                'cnpjProd' => '',                                       //CNPJ do produtor da mercadoria, quando diferente do emitente. Somente para os casos de exportação direta ou indireta.
                                'cSelo' => '',                                          //Código do selo de controle IPI
                                'qSelo' => '',                                          //Quantidade de selo de controle
                                'cEnq' => '999',                                        //Código de Enquadramento Legal do IPI
                                'vBC' => '',                                            //Valor da BC do IPI
                                'pIPI' => '',                                           //Alíquota do IPI
                                'qUnid' => '',                                          //Quantidade total na unidade padrão para tributação (somente para os produtos tributados por unidade)
                                'vUnid' => '',                                          //Valor por Unidade Tributável
                                'vIPI' => ''),                                          //Valor do IPI
                            //todo:reformulação base de dados produtos x impostos?
                            'PIS' => array(
                                'tipo' => '0',                                          //0 - PIS normal; 1 - PISST
                                'CST' => '01',                                          //Código de Situação Tributária do PIS
                                'vBC' => '19466.30',                                    //Valor da Base de Cálculo do PIS
                                'pPIS' => '1.65',                                       //Alíquota do PIS (em percentual)
                                'vPIS' => '321.19',                                     //Valor do PIS
                                'qBCProd' => '',                                        //Quantidade Vendida
                                'vAliqProd' => ''),                                     //Alíquota do PIS (em reais)
                            //todo:reformulação base de dados produtos x impostos?
                            'COFINS' => array(
                                'tipo' => '0',                                          //0 - CONFINS; 1 - COFINSST
                                'CST' => '01',                                          //Código de Situação Tributária da COFINS
                                'vBC' => '19466.30',                                    //Valor da Base de Cálculo da COFINS
                                'pCOFINS' => '7.60',                                    //Alíquota da COFINS (em percentual)
                                'vCOFINS' => '1479.44',                                 //Valor da COFINS
                                'qBCProd' => '',                                        //Quantidade Vendida
                                'vAliqProd' => ''),                                     //Alíquota da COFINS (em reais)
                            //todo:reformulação base de dados produtos x impostos?
                            'II' => array(
                                'vBC' => '',                                            //Valor BC do Imposto de Importação
                                'vDespAdu' => '',                                       //Valor despesas aduaneiras
                                'vII' => '',                                            //Valor Imposto de Importação
                                'vIOF' => ''),                                          //Valor Imposto sobre Operações Financeiras
                            //todo:reformulação base de dados produtos x impostos?
                            'ISSQN' => array(                                           //todo:excludente com relação ao ICMS, PIS, CONFINS, etc. Tratar entrada de dados e erros
                                'vBC' => '',                                            //Valor da Base de Cálculo do ISSQN
                                'vAliq' => '',                                          //Alíquota do ISSQN
                                'vISSQN' => '',                                         //Valor do ISSQN
                                'cMunFG' => '',                                         //Código do município de ocorrência do fato gerador do ISSQN
                                'cListServ' => '',                                      //Item da Lista de Serviços
                                'vDeducao' => '',                                       //Valor dedução para redução da Base de cálculo
                                'vOutro' => '',                                         //Valor outras retenções
                                'vDescIncond' => '',                                    //Valor desconto incondicionado
                                'vDescCond' => '',                                      //Valor desconto condicionado
                                'vISSRet' => '',                                        //Valor retenção ISS
                                'indISS' => '',                                         //Indicador da exigibilidade do ISS
                                'cServico' => '',                                       //Código do serviço prestado dentro do município
                                'cMun' => '',                                           //Código do Município de incidência do imposto
                                'cPais' => '',                                          //Código do País onde o serviço foi prestado
                                'nProcesso' => '',                                      //Número do processo judicial ou administrativo de suspensão da exigibilidade
                                'indIncentivo' => '')),                                    //Indicador de incentivo Fiscal
                        //Grupo de campos no caso de haver tributos devolvidos
                        'TributosDevolvidos' => array(
                            'pDevol' => '',                                             //Percentual da mercadoria devolvida
                            'vIPIDevol' => ''),                                         //Valor do IPI devolvido
                        //infAdProd: input no NFe para cada produto
                        'infAdProd' => 'TAMANHO XG')),                                             //Norma referenciada, informações complementares, etc.

                /* Totalizações */
                'Totais' => array(
                    //Totalizações das informações disponíveis
                    'ICMSTot' => array(
                        'vBC' => '19466.30',                                        //Base de Cálculo do ICMS
                        'vICMS' => '2335.96',                                       //Valor Total do ICMS
                        'vICMSDeson' => '0.00',                                     //Valor Total do ICMS desonerado
                        'vBCST' => '0.00',                                          //Base de Cálculo do ICMS ST
                        'vST' => '0.00',                                            //Valor Total do ICMS ST
                        'vProd' => '19466.30',                                      //Valor Total dos produtos e serviços
                        'vFrete' => '0.00',                                         //Valor Total do Frete
                        'vSeg' => '0.00',                                           //Valor Total do Seguro
                        'vDesc' => '0.00',                                          //Valor Total do Desconto
                        'vII' => '0.00',                                            //Valor Total do II
                        'vIPI' => '0.00',                                           //Valor Total do IPI
                        'vPIS' => '321.19',                                         //Valor do PIS
                        'vCOFINS' => '1479.44',                                     //Valor da COFINS
                        'vOutro' => '0.00',                                         //Outras Despesas acessórias
                        'vNF' => '19466.30',                                        //Valor Total da NF-e
                        'vTotTrib' => ''),                                          //Valor aproximado total de tributos federais, estaduais e municipais.
                    //Totalizações das informações disponíveis
                    'ISSQNtot' => array(
                        'vServ' => '',                                              //Valor total dos Serviços sob não-incidência ou não tributados pelo ICMS
                        'vBC' => '',                                                //Valor total Base de Cálculo do ISS
                        'vISS' => '',                                               //Valor total do ISS
                        'vPIS' => '',                                               //Valor total do PIS sobre serviços
                        'vCOFINS' => '',                                            //Valor total da COFINS sobre serviços
                        'dCompet' => '',                                            //Data da prestação do serviço
                        'vDeducao' => '',                                           //Valor total dedução para redução da Base de Cálculo
                        'vOutro' => '',                                             //Valor total outras retenções
                        'vDescIncond' => '',                                        //Valor total desconto incondicionado
                        'vDescCond' => '',                                          //Valor total desconto condicionado
                        'vISSRet' => '',                                            //Valor total retenção ISS
                        'cRegTrib' => ''),                                          //Código do Regime Especial de Tributação
                    //Grupo caso haja valores retidos
                    'retTrib' => array(
                        //vRetPIS: input no NFe
                        'vRetPIS' => '',                                            //Valor Retido de PIS
                        //vRetCOFINS: input no NFe
                        'vRetCOFINS' => '',                                         //Valor Retido de COFINS
                        //vRetCSLL: input no NFe
                        'vRetCSLL' => '',                                           //Valor Retido de CSLL
                        //vBCIRRF: input no NFe
                        'vBCIRRF' => '',                                            //Base de Cálculo do IRRF
                        //vIRRF: input no NFe
                        'vIRRF' => '',                                              //Valor Retido do IRRF
                        //vBCRetPrev: input no NFe
                        'vBCRetPrev' => '',                                         //Base de Cálculo da Retenção da Previdência Social
                        //vRetPrev: input no NFe
                        'vRetPrev' => '')),                                         //Valor da Retenção da Previdência Social

                /* Infos de transporte todo: campos a serem preenchidos na emissão da nota */
                'Transporte' => array(
                    //modFrete: select 0=Por conta do emitente;1=Por conta do destinatário/remetente;2=Por conta de terceiros;9=Sem frete. (V2.0)
                    'modFrete' => '0',                                              //Modalidade do frete

                    /* Infos Transportadora: todo: opcional - formulário de escolha ou preenchimento */
                    'transporta' => array(
                        //CNPJ: input todo: em cadastro de transportadora?
                        'CNPJ' => '',                                               //CNPJ do Transportador
                        //CPF: input todo: em cadastro de transportadora?
                        'CPF' => '12345678901',                                     //CPF do Transportador
                        //xNome: input todo: em cadastro de transportadora?
                        'xNome' => 'Ze da Carroca',                                 //Razão Social ou nome
                        //IE:  input todo: em cadastro de transportadora?
                        'IE' => '',                                                 //Inscrição Estadual do Transportador
                        //xEnder: input todo: em cadastro de transportadora?
                        'xEnder' => 'Beco Escuro',                                  //Endereço Completo
                        //xMun: input todo: em cadastro de transportadora?
                        'xMun' => 'Campinas',                                       //Nome do município
                        //UF: select todo: em cadastro de transportadora?
                        'UF' => 'SP'),                                              //Sigla da UF

                    /* Grupo caso haja retenção relativa a transporte todo: opcional - formulário de escolha ou preenchimento */
                    'retTransp' => array(                                           //Grupo Retenção ICMS transporte
                        //vServ: input NFe
                        'vServ' => '258.69',                                        //Valor do Serviço
                        //vBCRet: input NFe
                        'vBCRet' => '258.69',                                       //BC da Retenção do ICMS
                        //pICMSRet: input NFe
                        'pICMSRet' => '10.00',                                      //Alíquota da Retenção
                        //vICMSRet: input NFe
                        'vICMSRet' => '25.87',                                      //Valor do ICMS Retido
                        //CFOP: Select Nfe
                        'CFOP' => '5352',                                           //CFOP de Serviço de Transporte (Anexo XIII.03).
                        //cMunFG: Select NFe
                        'cMunFG' => '3509502'),                                     //Código do município de ocorrência do fato gerador do ICMS do transporte

                    /*Grupo caso haja informações adicionais de veículo trator todo: opcional - formulário de escolha ou preenchimento */
                    'veicTransp' => array(                                          //Grupo Veículo Transporte
                        //placa: Input NFe
                        'placa' => 'AAA1212',                                       //Placa do Veículo
                        //UF: Select NFe
                        'UF' => 'SP',                                               //Sigla da UF
                        //RNTC: Input NFe
                        'RNTC' => '12345678'),                                      //Registro Nacional de Transportador de Carga (ANTT)

                    /*Grupo caso haja informações de reboque todo: opcional - formulário de escolha ou preenchimento */
                    'reboque' => array(
                        array(
                            //placa: Input NFe
                            'placa' => '',                                          //Placa do Veículo
                            //UF: Select NFe
                            'UF' => '',                                             //Sigla da UF
                            //RNTC: Input NFe
                            'RNTC' => '',                                           //Registro Nacional de Transportador de Carga (ANTT)
                            //vagao: Input NFe
                            'vagao' => '',                                          //Identificação do vagão
                            //balsa: Input NFe
                            'balsa' => '')),                                        //Identificação da balsa

                    /* Volumes da nota todo: formulário para preenchimento de cada volume */
                    'vol' => array(
                        array(
                            //qVol: Input NFe
                            'qVol' => '24',                                         //Quantidade de volumes transportados
                            //esp: Input NFe
                            'esp' => 'VOLUMES',                                     //Espécie dos volumes transportados
                            //marca: INput NFe
                            'marca' => '',                                          //Marca dos volumes transportados
                            //nVol: Input NFe
                            'nVol' => '',                                           //Numeração dos volumes transportados
                            //pesoL: Input NFe
                            'pesoL' => '389.950',                                   //Peso Líquido (em kg)
                            //pesoB: Input NFe
                            'pesoB' => '399.550',                                   //Peso Bruto (em kg)
                            'aLacres' => array(                                     //Grupo Lacres: se não tiver lacre a array deve ser vazia
                                //nLacre: input NFe
                                'nLacre' => '2')))),                                 //Número dos Lacres

                /* Infos de cobrança */
                'Cobranca' => array(
                    //todo: tabela de fatura?
                    'fat' => array(                                                 //Grupo Fatura
                        'nFat' => '000034189',                                      //Número da Fatura
                        'vOrig' => '19466.30',                                      //Valor Original da Fatura
                        'vDesc' => '',                                              //Valor do desconto
                        'vLiq' => '19466.30'),                                      //Valor Líquido da Fatura
                    //todo: tabela de duplicata?
                    'dup' => array(                                                 //Grupo Duplicata
                        array(
                            'nDup' => '',                                           //Número da Duplicata
                            'dVenc' => '',                                          //Data de vencimento
                            'vDup' => ''))),                                        //Valor da duplicata

                /* Forma de pagamento */
                'FormaPagamento' => array(                                          //Grupo obrigatório para a NFC-e, a critério da UF. Não informar para a NF-e.
                    //tPag: select com 01=Dinheiro;02=Cheque;03=Cartão de Crédito;04=Cartão de Débito;05=Crédito Loja;10=Vale Alimentação;11=Vale Refeição;12=Vale Presente;13=Vale Combustível;99=Outros
                    'tPag' => '',                                                   //Forma de pagamento
                    //vPag:input Nfe
                    'vPag' => '',                                                   //Valor do Pagamento
                    //Se tPag = 3
                    'card' => array(                                                //Grupo de Cartões
                        //CNPJ: input NFe
                        'CNPJ' => '31551765000143',                                 //CNPJ da Credenciadora de cartão de crédito e/ou débito
                        //tBand: input NFe
                        'tBand' => '01',                                            //Bandeira da operadora de cartão de crédito e/ou débito
                        //cAut: input NFe
                        'cAut' => 'AB254FC79001')),                                 //Número de autorização da operação cartão de crédito e/ou débito

                /* Informações adicionais todo: formulário */
                'InfoAdic' => array(
                    //infAdFisco: input NFe
                    'infAdFisco' => 'SAIDA C/ SUSP IPI CONF ART 29 DA LEI 10.637',  //Informações Adicionais de Interesse do Fisco
                    //infCpl: input NFe
                    'infCpl' => '',                                                 //Informações Complementares de interesse do Contribuinte
                    'ObsCont' => array(                                         //Grupo Campo de uso livre do contribuinte
                        array(
                            'xCampo' => '',                                         //Identificação do campo
                            'xTexto' => '')),                                       //Conteúdo do campo
                    'ObsFisco' => array(                                            //Grupo Campo de uso livre do Fisco
                        array(
                            //xCampo: input NFe
                            'xCampo' => '',                                         //Identificação do campo
                            //xTexto: input NFe
                            'xTexto' => '')),                                       //Conteúdo do campo
                    'Processos' => array(                                           //Grupo Processo referenciado
                        array(
                            //nProc: input NFe
                            'nProc' => '',                                          //Identificador do processo ou ato concessório
                            //indProc: input NFe
                            'indProc' => ''))),                                     //Indicador da origem do processo

                /* Grupo de campos caso a nota for para exportação todo: formulário condicional se a nota for de exportação */
                'Exporta' => array(
                    //UFSaidaPais: Select NFe
                    'UFSaidaPais' => 'SP',                                          //Sigla da UF de Embarque ou de transposição de fronteira
                    //xLocExporta: input NFe
                    'xLocExporta' => 'Maritimo',                                    //Descrição do Local de Embarque ou de transposição de fronteira
                    //xLocDespacho: input NFe
                    'xLocDespacho' => 'Porto Santos'),                              //Descrição do Local de Embarque ou de transposição de fronteira

                /* Grupo de campos caso haja informações adicionais de compra todo: formulário se a nota for de compra */
                'Compra' => array(
                    //xNEmp: input NFe
                    'xNEmp' => '',                                                  //Nota de Empenho
                    //xpED: input NFe
                    'xPed' => '12345',                                              //Pedido
                    //xCont: input NFe
                    'xCont' => 'A342212'),                                          //Contrato

                /* Grupo de campos caso as transações envolvam cana todo: formulário condicional */
                'Cana' => array(
                    //input NFe
                    'safra' => '',                                              //Identificação da safra
                    //input NFe
                    'ref' => '',                                             //Mês e ano de referência
                    //multiplas entradas input NFe
                    'forDia' => array(                                              //Grupo Fornecimento diário de cana
                        array(
                            //input NFe
                            'dia' => '',                                            //Dia
                            //input NFe
                            'qtde' => '',                                           //Quantidade
                            //input NFe
                            'qTotMes' => '',                                        //Quantidade Total do Mês
                            //input NFe
                            'qTotAnt' => '',                                        //Quantidade Total Anterior
                            //input NFe
                            'qTotGer' => '')),                                      //Quantidade Total Geral
                    //Grupo caso haja deduções
                    'deduc' => array(                                               //Grupo Deduções – Taxas e Contribuições
                        //input NFe
                        'xDed' => '',                                               //Descrição da Dedução
                        //input NFe
                        'vDed' => '',                                               //Valor da Dedução
                        //input NFe
                        'vFor' => '',                                               //Valor dos Fornecimentos
                        //input NFe
                        'vTotDed' => '',                                            //Valor Total da Dedução
                        //input NFe
                        'vLiqFor' => '')                                            //Valor Líquido dos Fornecimentos
                )
            );

            return $nfeDados;

        }

}
