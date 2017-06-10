<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'defaultController' => 'home',
    'name' => 'DSCloud',
    'language' => 'pt',
    // preloading 'log' component
    'preload' => array('log', 'bootstrap'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.modules.srbac.controllers.SBaseController',
        'ext.select2.Select2',
        'application.modules.srbac.models.AuthItem',
        'application.modules.Configs.models.Usuarios',
        'application.modules.Configs.models.UsuariosUnidades',
        'application.modules.Configs.models.HistoricoLogin',
        'application.modules.Configs.models.SessoesAtivas',
        'application.modules.Configs.models.Unidades',
        'application.modules.Configs.models.GruposUsuarios',
        'application.modules.Configs.models.Grupos',
        'application.modules.Configs.models.Nfe',
        
        'application.modules.CRM.models.Cliente.Cliente',
        'application.modules.CRM.models.Cliente.ClienteDocumento',
        'application.modules.CRM.models.Cliente.ClienteEndereco',
        'application.modules.CRM.models.Cliente.Contato',
        'application.modules.CRM.models.Cliente.ContatoContatos',
        'application.modules.CRM.models.Cliente.ContatoEndereco',
        'application.modules.CRM.models.Colaborador.Colaborador',
        
        'application.modules.ERP.models.Financeiro.Financeiro',
        'application.modules.ECM.models.OpcoesConfig',
        'application.modules.ECM.models.PerfilOpcoes',
        'application.modules.ECM.models.ScannerPerfil',
        'application.modules.ECM.models.ScannerPerfilUsuario',
        'application.modules.CMS.models.Site',
        'application.modules.Email.models.Contas',
        'application.modules.ERP.models.Fabricante',
        'application.modules.ERP.models.Fornecedor',
        'application.modules.ERP.models.Estoque.Estoque',
        'application.modules.ERP.models.Estoque.EstoqueTransacao',
        'application.modules.ERP.models.Servico.ServicoCategoria',

        'application.modules.Producao.models.Produto',
        'application.modules.Producao.models.ProdutoCategoria',
    ),
    'aliases' => array(
        'booster' => realpath(__DIR__ . '/../extensions/bootstrap')
    ),
    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'ds@123',
            'generatorPaths' => array(
                'booster.gii',
            ),
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => ['127.0.0.1', '::1', '172.17.0.136', '172.17.0.115']
        ),
        'CRM',
        'ERP',
        'ECM',
        'BPM',
        'BI',
        'WCM',
        'CMS',
        'SNK',
        'IMOB',
        'DS',
        'Producao',
        'Configs',
        'Email',
        'srbac' => array(
            'userclass' => 'Usuarios',
            'userid' => 'id_usuario',
            'username' => 'login',
            'delimeter' => '@', //default:-
            'debug' => false, //default :false
            'pageSize' => 10, // default : 15
            'superUser' => 'SRBAC_Admin', //default: Authorizer
            'css' => 'srbac.css', //default: srbac.css
            'layout' => 'application.views.layouts.main', //default: application.views.layouts.main,  //must be an existing alias
            'notAuthorizedView' => 'srbac.views.authitem.unauthorized', // default:
            //srbac.views.authitem.unauthorized, must be an existing alias
            'alwaysAllowed' => array(//default: array()
                'SiteLogin', 'SiteLogout', 'SiteIndex', 'SiteAdmin',
                'SiteError', 'SiteContact'
            ),
            'userActions' => array('Show', 'View', 'List'), //default: array()
            'listBoxNumberOfLines' => 15, //default : 10
            'imagesPath' => 'srbac.images', // default: srbac.images
            'imagesPack' => 'noia', //default: noia
            'iconText' => true, // default : false
            'header' => 'srbac.views.authitem.header', //default : srbac.views.authitem.header,
            //must be an existing alias
            'footer' => 'srbac.views.authitem.footer', //default: srbac.views.authitem.footer,
            //must be an existing alias
            'showHeader' => true, // default: false
            'showFooter' => true, // default: false
            'alwaysAllowedPath' => 'srbac.components' // default: srbac.components
        )
    ),
    'behaviors' => array(
        'onBeginRequest' => array(
            'class' => 'application.components.RequireLogin',
            'class' => 'application.components.StartupBehavior'
        )
    ),
    'params' => array(
        'recSenha' => 'site/recuperarsenha', //Registration page url
        'sDemo' => 'site/solicitardemo', //Recovery or change password page url
        'language' => 'pt'
    ),
    // application components
    'components' => array(
        'ESaveRelatedBehavior' => array(
            'class' => 'application.extensions.ESaveRelatedBehavior',
            'useOnUpdate' => false
        ),
        'clientScript' => [
            'scriptMap' => [
                'jquery.js' => false
            ]
        ],
        /*'mail' => array(
 			'class' => 'application.extensions.yii-mail.YiiMail',
 			'transportType' => 'php',
 			'viewPath' => 'application.views.mail',
 			'logging' => true,
 			'dryRun' => false
 		),*/
        /* 'cache'=>array(
          'class'=>'system.caching.CMemCache',
          'servers'=>array(
          array('host'=>'127.0.0.1', 'port'=>11211, 'weight'=>60),
          ),
          ), */
        'storage' => [
            'class' => 'Storage'
        ],
        'tesseract' => [
            'class' => 'TesseractOCR'
        ],
        'localtime' => array(
            'class' => 'LocalTime',
        ),
        'timeinfo' => array(
            'class' => 'TimeInfo'
        ),
        'securityAdvanced' => array(
            'class' => 'SecurityAdvanced',
        ),
        'Utils' => array(
            'class' => 'Util',
        ),
        'JSMin' => array(
            'class' => 'JSMin',
        ),
        'securityCrypto' => array(
            'class' => 'SecurityCrypto',
        ),
        'viewRenderer' => array(
            'class' => 'application.vendor.smarty.ESmartyViewRenderer',
            'fileExtension' => '.tpl',
        //'pluginsDir' => 'application.smartyPlugins',
        //'configDir' => 'application.smartyConfig',
        //'prefilters' => array(array('MyClass','filterMethod')),
        //'postfilters' => array(),
        //'config'=>array(
        //    'force_compile' => YII_DEBUG,
        //   ... any Smarty object parameter
        //)
        ),
        'session' => require(dirname(__FILE__) . '/Session.php'),
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'loginUrl' => array('site/login'), //login page url
        ),
        'authManager' => array(
            'class' => 'CDbAuthManager',
            'class' => 'application.modules.srbac.components.SDbAuthManager',
            'connectionID' => 'db',
            'itemTable' => 'srbac_items',
            'assignmentTable' => 'srbac_assign',
            'itemChildTable' => 'srbac_ichildren',
        ),
        'bootstrap' => array(
            'class' => 'ext.yiibooster.components.Booster',
            'responsiveCss' => true,
            'minify' => true
        ),
        'urlManager' => array(
            'showScriptName' => false, // Removes index.php from URL
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                'home' => 'site/index',
                'sair' => 'site/logout',
                'login' => 'site/login',
                'setup' => 'site/setup',
                'SocketIO' => 'site/SocketIO',
                'minhasUnidades' => 'site/minhasunidades',
                'configs' => 'Configs',
                'configs/<_c:\w+>/<_a:\w+>/<id:\d+>' => 'Configs/<_c>/<_a>/<id>',
                'configs/<_c:\w+>/<_a:\w+>' => 'Configs/<_c>/<_a>',
                'configs/<_c:\w+>' => 'Configs/<_c>',
                'crm' => 'CRM',
                'crm/<_c:\w+>/<_a:\w+>/<id:\d+>' => 'CRM/<_c>/<_a>/<id>',
                'crm/<_c:\w+>/<_a:\w+>' => 'CRM/<_c>/<_a>',
                'crm/<_c:\w+>' => 'CRM/<_c>',
                'erp' => 'ERP',
                'erp/<_c:\w+>/<_a:\w+>/<id:\d+>' => 'ERP/<_c>/<_a>/<id>',
                'erp/<_c:\w+>/<_a:\w+>' => 'ERP/<_c>/<_a>',
                'erp/<_c:\w+>' => 'ERP/<_c>',
                'bpm' => 'BPM',
                'bpm/<_c:\w+>/<_a:\w+>/<id:\d+>' => 'BPM/<_c>/<_a>/<id>',
                'bpm/<_c:\w+>/<_a:\w+>' => 'BPM/<_c>/<_a>',
                'bpm/<_c:\w+>' => 'BPM/<_c>',
                'ecm' => 'ECM',
                'ecm/<_c:\w+>/<_a:\w+>/<id:\d+>' => 'ECM/<_c>/<_a>/<id>',
                'ecm/<_c:\w+>/<_a:\w+>' => 'ECM/<_c>/<_a>',
                'ecm/<_c:\w+>' => 'ECM/<_c>',
                'producao' => 'Producao',
                'producao/<_c:\w+>/<_a:\w+>/<id:\d+>' => 'Producao/<_c>/<_a>/<id>',
                'producao/<_c:\w+>/<_a:\w+>' => 'Producao/<_c>/<_a>',
                'producao/<_c:\w+>' => 'Producao/<_c>',
                'bi' => 'BI',
                'bi/<_c:\w+>/<_a:\w+>/<id:\d+>' => 'BI/<_c>/<_a>/<id>',
                'bi/<_c:\w+>/<_a:\w+>' => 'BI/<_c>/<_a>',
                'bi/<_c:\w+>' => 'BI/<_c>',
                'wcm' => 'WCM',
                'wcm/<_c:\w+>/<_a:\w+>/<id:\d+>' => 'WCM/<_c>/<_a>/<id>',
                'wcm/<_c:\w+>/<_a:\w+>' => 'WCM/<_c>/<_a>',
                'wcm/<_c:\w+>' => 'WCM/<_c>',
                'cms' => 'CMS',
                'cms/<_c:\w+>/<_a:\w+>/<id:\d+>' => 'CMS/<_c>/<_a>/<id>',
                'cms/<_c:\w+>/<_a:\w+>' => 'CMS/<_c>/<_a>',
                'cms/<_c:\w+>' => 'CMS/<_c>',
                'snk' => 'SNK',
                'snk/<_c:\w+>/<_a:\w+>/<id:\d+>' => 'SNK/<_c>/<_a>/<id>',
                'snk/<_c:\w+>/<_a:\w+>' => 'SNK/<_c>/<_a>',
                'snk/<_c:\w+>' => 'SNK/<_c>',
                'imob' => 'IMOB',
                'imob/<_c:\w+>/<_a:\w+>/<id:\d+>' => 'IMOB/<_c>/<_a>/<id>',
                'imob/<_c:\w+>/<_a:\w+>' => 'IMOB/<_c>/<_a>',
                'imob/<_c:\w+>' => 'IMOB/<_c>',
                'ds' => 'DS',
                'ds/<_c:\w+>/<_a:\w+>/<id:\d+>' => 'DS/<_c>/<_a>/<id>',
                'ds/<_c:\w+>/<_a:\w+>' => 'DS/<_c>/<_a>',
                'ds/<_c:\w+>' => 'DS/<_c>',
                'email' => 'Email',
                'email/<_c:\w+>/<_a:\w+>/<id:\d+>' => 'Email/<_c>/<_a>/<id>',
                'email/<_c:\w+>/<_a:\w+>' => 'Email/<_c>/<_a>',
                'email/<_c:\w+>' => 'Email/<_c>',
            ),
        ),
        'db' => require(dirname(__FILE__) . '/ClienteDB.php'),
        'dev' => require(dirname(__FILE__) . '/DevDB.php'),
        //'ocrdb' => require(dirname(__FILE__) . '/OCRDB.php'),
        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
        //Configurações Globais dos "Widgets"
        'widgetFactory' => [
            'widgets' => [
                'CGridView' => [
                    'summaryText' => 'Mostrando {start} - {end} de {count}',
                    'emptyText' => 'Nenhum resultado encontrado',
                    'pager' => [
                        'header' => '',
                        'cssFile' => false,
                        'maxButtonCount' => 5,
                        'nextPageLabel' => '<i class="ace-icon fa fa-chevron-right"></i>',
                        'prevPageLabel' => '<i class="ace-icon fa fa-chevron-left"></i>',
                        'firstPageLabel' => '<i class="ace-icon fa fa-chevron-left"></i><i class="ace-icon fa fa-chevron-left"></i>',
                        'lastPageLabel' => '<i class="ace-icon fa fa-chevron-right"></i><i class="ace-icon fa fa-chevron-right"></i>',
                        'selectedPageCssClass' => 'active',
                        'hiddenPageCssClass' => 'disabled',
                    ]
                ],
                'TbGridView' => [
                    'summaryText' => 'Mostrando {start} - {end} de {count}',
                    'emptyText' => 'Nenhum resultado encontrado',
                ]
            ]
        ],
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning, info, trace',
                    'categories' => 'system.db.CDbCommand',
                    'logFile' => 'db.log',
                ),
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                    'logFile' => 'app.log',
                )
            )
        ),
        'ePdf' => array(
            'class' => 'ext.yii-pdf.EYiiPdf',
            'params' => array(
                'mpdf' => array(
                    'librarySourcePath' => 'application.vendor.mpdf.*',
                    'constants' => array(
                        '_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
                    ),
                    'class' => 'mpdf', // the literal class filename to be loaded from the vendors folder.
                    'defaultParams' => array(// More info: http://mpdf1.com/manual/index.php?tid=184
                        'mode' => '', //  This parameter specifies the mode of the new document.
                        'format' => 'A4', // format A4, A5, ...
                        'default_font_size' => 0, // Sets the default document font size in points (pt)
                        'default_font' => '', // Sets the default font-family for the new document.
                        'mgl' => 15, // margin_left. Sets the page margins for the new document.
                        'mgr' => 15, // margin_right
                        'mgt' => 16, // margin_top
                        'mgb' => 16, // margin_bottom
                        'mgh' => 9, // margin_header
                        'mgf' => 9, // margin_footer
                        'orientation' => 'P' // landscape or portrait orientation
                    )
                ),
                'HTML2PDF' => array(
                    'librarySourcePath' => 'application.vendor.html2pdf.*',
                    'classFile' => 'html2pdf.class.php', // For adding to Yii::$classMap
                    'defaultParams' => array(// More info: http://wiki.spipu.net/doku.php?id=html2pdf:en:v4:accueil
                        'orientation' => 'P', // landscape or portrait orientation
                        'format' => 'A4', // format A4, A5, ...
                        'language' => 'en', // language: fr, en, it ...
                        'unicode' => true, // TRUE means clustering the input text IS unicode (default = true)
                        'encoding' => 'UTF-8', // charset encoding; Default is UTF-8
                        'marges' => array(5, 5, 5, 8), // margins by default, in order (left, top, right, bottom)
                    )
                )
            ),
        )
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => require(dirname(__FILE__) . '/Params.php')
);
