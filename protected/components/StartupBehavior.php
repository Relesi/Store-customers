<?php

/*
 * Developer Rafael Rabaço
 * rafaelrabaco@gmail.com
 */

class StartupBehavior extends CBehavior {

    /**
     * Executa Funções
     * @param type $owner
     */
    public function attach($owner) {        
        $owner->attachEventHandler('onBeginRequest', array($this, 'LanguageDetect'));
        $owner->attachEventHandler('onBeginRequest', array($this, 'ModelDetect'));
        $owner->attachEventHandler('onBeginRequest', array($this, 'ViewDetect'));
        $owner->attachEventHandler('onBeginRequest', array($this, 'ParentDetect'));
        $owner->attachEventHandler('onBeginRequest', array($this, 'ParentMinDetect'));
        $owner->attachEventHandler('onBeginRequest', array($this, 'Security'));
        $owner->attachEventHandler('onBeginRequest', array($this, 'TitleDetect'));
//        $owner->attachEventHandler('onBeginRequest', array($this, 'Dominio'));
        $owner->attachEventHandler('onBeginRequest', array($this, 'LogoDetect'));
//        $owner->attachEventHandler('onBeginRequest', array($this, 'VerifySetup'));
        $owner->attachEventHandler('onBeginRequest', array($this, 'CacheJavaScript'));
        $owner->attachEventHandler('onBeginRequest', array($this, 'CacheCSS'));
        $owner->attachEventHandler('onBeginRequest', array($this, 'getProtocol'));
    }

    /**
     * Detecta o Modulo Atual
     * @param CEvent $event
     */
    public function ModelDetect(CEvent $event) {
        $getModulo = explode('/', $_SERVER['REQUEST_URI']);
        $C = count($getModulo) - 1;
        if ($C >= 1) {
            Yii::app()->params['Modulo'] = strtoupper($getModulo[1]);
        } else {
            Yii::app()->params['Modulo'] = null;
        }
    }

    /**
     * Detecta a View Atual
     * @param CEvent $event
     */
    public function ViewDetect(CEvent $event) {
        $getView = explode('/', $_SERVER['REQUEST_URI']);
        $C = count($getView) - 1;
        if ($C >= 2) {
            Yii::app()->params['View'] = $getView[2];
        } else {
            Yii::app()->params['View'] = null;
        }
    }

    /**
     * Detecta a Parent Atual
     * @param CEvent $event
     */
    public function ParentDetect(CEvent $event) {
        $getView = explode('/', $_SERVER['REQUEST_URI']);
        $C = count($getView) - 1;
        if ($C >= 3) {
            Yii::app()->params['Parent'] = $getView[3];
        } else {
            Yii::app()->params['Parent'] = null;
        }
    }

    /**
     * Detecta a Parent Atual Minima
     * @param CEvent $event
     */
    public function ParentMinDetect(CEvent $event) {
        $getView = explode('/', $_SERVER['REQUEST_URI']);
        $C = count($getView) - 1;
        if ($C >= 3) {
            $PMin = explode('?', $getView[3]);
            Yii::app()->params['ParentMin'] = $PMin[0];
        } else {
            Yii::app()->params['ParentMin'] = null;
        }
    }

    /**
     * Função para atribuir segurança aos modulos
     * @param CEvent $event
     */
    public function Security(CEvent $event) {
        //Ajustado o registro [id] para o id(identificador) do banco de dados, anteriormente estava o login(texto)
        Yii::app()->user->id = Yii::app()->session['ID'];

        if (Yii::app()->params['Modulo'] === '') { //Caso não exista modulo, redireciona para o modulo padrão do usuário
            if (Yii::app()->session['Trial'] == 1)
                Yii::app()->user->setFlash('Trial', "");
//            Yii::app()->request->redirect(Yii::app()->session['DefaultPage'] === null ? 'home' : Yii::app()->session['DefaultPage']);
            Yii::app()->request->redirect(Yii::app()->session['DefaultPage'] === null ? 'crm' : Yii::app()->session['DefaultPage']); // PROVISORIO
        }
        
        $params = Yii::app()->params;
        if (!in_array($params['Modulo'], ['', 'LOGIN', 'PUBLIC']) && Yii::app()->user->getId() === null) { //Protege os demais modulos
            Yii::app()->request->redirect(Yii::app()->user->loginRequired());
        }

        if (Yii::app()->user->getId() != null) {//Permissões para usuários que estão logados
            if (Yii::app()->params['Modulo'] === 'CONFIGS' && Yii::app()->params['View'] === 'usuarios' && Yii::app()->params['Parent'] === 'view?id=' . Yii::app()->session['ID'] || Yii::app()->params['Modulo'] === 'CONFIGS' && Yii::app()->params['View'] === 'usuarios' && Yii::app()->params['Parent'] === 'update?id=' . Yii::app()->session['ID']) {
                //Concede o acesso
            } else if (Yii::app()->params['Modulo'] === 'CONFIGS' && Yii::app()->session['Admin'] == 0) { //Protege tela de configurações para usuários que não são admin
                throw new CHttpException(403, 'Acesso Negado');
            }


            /**
             * Protege a página de WorkSpaces
             */
            if (Yii::app()->params['View'] === 'workspaces' && Yii::app()->session['WorkSpace'] != 'dscloud') {
                throw new CHttpException(403, 'Acesso Negado');
            }

            /**
             * Verifica se a versão trial do Usuário que esta logado já expirou
             */
            if (Yii::app()->session['Trial'] == 1) {
                $this->VerifyTrial();
            }
            /**
             * Protege os modulos que não foram contratados pela WorkSpace
             */
            if (Yii::app()->params['Modulo'] === 'CRM' && Yii::app()->session['CRM'] == 0) {
                throw new CHttpException(403, 'Acesso Negado');
            }
            if (Yii::app()->params['Modulo'] === 'ERP' && Yii::app()->session['ERP'] == 0) {
                throw new CHttpException(403, 'Acesso Negado');
            }
            if (Yii::app()->params['Modulo'] === 'ECM' && Yii::app()->session['ECM'] == 0) {
                throw new CHttpException(403, 'Acesso Negado');
            }
            if (Yii::app()->params['Modulo'] === 'BPM' && Yii::app()->session['BPM'] == 0) {
                throw new CHttpException(403, 'Acesso Negado');
            }
            if (Yii::app()->params['Modulo'] === 'BI' && Yii::app()->session['BI'] == 0) {
                throw new CHttpException(403, 'Acesso Negado');
            }
            if (Yii::app()->params['Modulo'] === 'WCM' && Yii::app()->session['WCM'] == 0) {
                throw new CHttpException(403, 'Acesso Negado');
            }
            if (Yii::app()->params['Modulo'] === 'CMS' && Yii::app()->session['CMS'] == 0) {
                throw new CHttpException(403, 'Acesso Negado');
            }
            if (Yii::app()->params['Modulo'] === 'SNK' && Yii::app()->session['SNK'] == 0) {
                throw new CHttpException(403, 'Acesso Negado');
            }
            if (Yii::app()->params['Modulo'] === 'IMOB' && Yii::app()->session['IMOB'] == 0) {
                throw new CHttpException(403, 'Acesso Negado');
            }
            if (Yii::app()->params['Modulo'] === 'DS' && Yii::app()->session['DS'] == 0) {
                throw new CHttpException(403, 'Acesso Negado');
            }
        }
    }

    /**
     * Auto detecta o titulo baseado no Modulo e na View atual
     * @param CEvent $event
     */
    public function TitleDetect(CEvent $event) {
        if (Yii::app()->params['Modulo'] == null)
            Yii::app()->params['Titulo'] = Yii::app()->name . ' - Home';

        if (Yii::app()->params['Modulo'] == "HOME")
            Yii::app()->params['Titulo'] = Yii::app()->name . ' - Home';

        if (Yii::app()->params['Modulo'] == "CRM")
            Yii::app()->params['Titulo'] = Yii::app()->name . ' - CRM';

        if (Yii::app()->params['Modulo'] == "ERP")
            Yii::app()->params['Titulo'] = Yii::app()->name . ' - ERP';

        if (Yii::app()->params['Modulo'] == "ECM")
            Yii::app()->params['Titulo'] = Yii::app()->name . ' - ECM';

        if (Yii::app()->params['Modulo'] == "BPM")
            Yii::app()->params['Titulo'] = Yii::app()->name . ' - BPM';

        if (Yii::app()->params['Modulo'] == "BI")
            Yii::app()->params['Titulo'] = Yii::app()->name . ' - BI';

        if (Yii::app()->params['Modulo'] == "WCM")
            Yii::app()->params['Titulo'] = Yii::app()->name . ' - WCM';

        if (Yii::app()->params['Modulo'] == "CMS")
            Yii::app()->params['Titulo'] = Yii::app()->name . ' - CMS';

        if (Yii::app()->params['Modulo'] == "SNK")
            Yii::app()->params['Titulo'] = Yii::app()->name . ' - SOCIAL NETWORK';

        if (Yii::app()->params['Modulo'] == "DS")
            Yii::app()->params['Titulo'] = Yii::app()->name . ' - COMERCIAL DS';

        if (Yii::app()->params['Modulo'] == "CONFIGS")
            Yii::app()->params['Titulo'] = Yii::app()->name . ' - Configurações';

        if (Yii::app()->params['Modulo'] == "EMAIL")
            Yii::app()->params['Titulo'] = Yii::app()->name . ' - Gerenciador de Email';

        if (Yii::app()->params['Modulo'] == "SETUP")
            Yii::app()->params['Titulo'] = Yii::app()->name . ' - Setup';

        if (Yii::app()->params['Modulo'] == null)
            Yii::app()->params['Titulo'] = Yii::app()->name . '';
    }

    /**
     * Detecta a Linguagem Atual
     * @param CEvent $event
     */
    public function LanguageDetect(CEvent $event) {
        //Não Inplementado
    }

    /**
     * Detecta o Dominio
     * @param CEvent $event
     */
    public function Dominio(CEvent $event) {
        $dominio = explode(".", $_SERVER['HTTP_HOST']);
        Yii::app()->params['WorkSpace'] = $dominio[0];
    }

    /**
     * Detecta o Logo da WorkSpace
     * @param CEvent $event
     */
    public function LogoDetect(CEvent $event) {
        if (Yii::app()->session['WorkSpaceLogo'] == null) {
            try {
                $connection = Yii::app()->db;
                $sql = "SELECT logo, facebook, twitter, youtube, linkedin FROM ds_empresa LIMIT 1";
                $WS = $connection->createCommand($sql)->query();
                $WS->bindColumn(1, $Logo);
                $WS->bindColumn(2, $FB);
                $WS->bindColumn(3, $TT);
                $WS->bindColumn(4, $YT);
                $WS->bindColumn(5, $LKIN);
                while ($WS->read() !== false) {
                    // Faz a leitura das colunas
                }
                Yii::app()->session['WorkSpaceFB'] = $FB;
                Yii::app()->session['WorkSpaceTT'] = $TT;
                Yii::app()->session['WorkSpaceYT'] = $YT;
                Yii::app()->session['WorkSpaceLKIN'] = $LKIN;

                $imageLocation = Yii::app()->basePath . '/../uploads/logo/';
                if (file_exists($imageLocation . $Logo . '.png')) {
                    Yii::app()->session['WorkSpaceLogo'] = $Logo;
                } else {
                    Yii::app()->session['WorkSpaceLogo'] = 'default';
                }
            } catch (Exception $e) { // caso a consulta falhe
                $imageLocation = Yii::app()->basePath . '/../uploads/logo/';
                Yii::app()->session['WorkSpaceLogo'] = 'default'; //Logo Padrão
            }
        }
    }

    /**
     * Verifica se o usuário concluiu o setup inicial
     * @param CEvent $event
     */
    public function VerifySetup(CEvent $event) {
        if (Yii::app()->session['PrimeiroLogin'] == 1 && Yii::app()->params['Modulo'] != 'SETUP') {
            Yii::app()->request->redirect('setup');
        }
    }

    /**
     * Verifica se o usuário concluiu o setup inicial
     */
    protected function VerifyTrial() {
        $DataAtual = explode(' ', Yii::app()->localtime->toLocalDateTime(Yii::app()->localtime->UTCNow, 'medium', 'medium'));
        if ($DataAtual[0] >= Yii::app()->session['TrialDate']) {
            HistoricoLogin::model()->SalvaLogout(Yii::app()->session['ID']); // Salva Logout do Usuário no Histórico
            Yii::app()->user->logout();
            Yii::app()->request->redirect('login');
        }
    }

    /**
     * Compara a data de modificação do cache com o arquivo
     * @param type $ArquivoPadrao
     * @param type $ArquivoModulo
     * @param type $ArquivoCompilado
     * @return boolean
     */
    protected function VerificarCache($ArquivoPadrao, $ArquivoModulo, $ArquivoCompilado) {
        if (file_exists($ArquivoModulo)) {
            if (filemtime($ArquivoPadrao) < filemtime($ArquivoCompilado) || filemtime($ArquivoModulo) < filemtime($ArquivoCompilado)) {
                return false; //Mantem Arquivo Compilado
            } else {
                return true; //Recompila
            }
        } else {
            if (filemtime($ArquivoPadrao) > filemtime($ArquivoCompilado)) {
                return false; //Mantem Arquivo Compilado
            } else {
                return true; //Recompila
            }
        }
    }

    /**
     * Cria um Compilado & Cachea o JavaScript
     */
    protected function CacheJavaScript(CEvent $event) {
        $File = Yii::app()->request->baseUrl . "themes/classic/assets/js/DocSystem/DocSystem_" . Yii::app()->params['Modulo'] . ".js";
        $DefaultFile = Yii::app()->request->baseUrl . "themes/classic/assets/js/DocSystem/DocSystem_MAIN.js";
        $CacheDiretoryWS = Yii::app()->request->baseUrl . "assets/" . md5(Yii::app()->session['WorkSpace']) . "/";
        $CacheDiretoryJS = Yii::app()->request->baseUrl . "assets/" . md5(Yii::app()->session['WorkSpace']) . "/js/";
        $CacheFile = $CacheDiretoryWS . "js/" . md5(Yii::app()->params['Modulo']) . ".js";
        if (!file_exists($CacheDiretoryWS)) {
            mkdir($CacheDiretoryWS, 0777);
        }
        if (!file_exists($CacheDiretoryJS)) {
            mkdir($CacheDiretoryJS, 0777);
        }
        $ArquivoUnico = '';
        switch (Yii::app()->params['Modulo']) {
            case "ECM":
                if (!file_exists($CacheFile)) {
                    $fh = fopen($CacheFile, 'w');
                    fclose($fh);
                    $ArquivoUnico .= file_get_contents($DefaultFile);
                    $ArquivoUnico .= file_get_contents($File);
                    $output = Yii::app()->JSMin->Compilar($ArquivoUnico);
                    file_put_contents($CacheFile, $output);
                } else {
                    if (!$this->VerificarCache($DefaultFile, $File, $CacheFile)) {
                        $ArquivoUnico .= file_get_contents($DefaultFile);
                        $ArquivoUnico .= file_get_contents($File);
                        $output = Yii::app()->JSMin->Compilar($ArquivoUnico);
                        file_put_contents($CacheFile, $output);
                    }
                }
                break;

            case "ERP":
                if (!file_exists($CacheFile)) {
                    $fh = fopen($CacheFile, 'w');
                    fclose($fh);
                    $ArquivoUnico .= file_get_contents($DefaultFile);
                    $ArquivoUnico .= file_get_contents($File);
                    $output = Yii::app()->JSMin->Compilar($ArquivoUnico);
                    file_put_contents($CacheFile, $output);
                } else {
                    if (!$this->VerificarCache($DefaultFile, $File, $CacheFile)) {
                        $ArquivoUnico .= file_get_contents($DefaultFile);
                        $ArquivoUnico .= file_get_contents($File);
                        $output = Yii::app()->JSMin->Compilar($ArquivoUnico);
                        file_put_contents($CacheFile, $output);
                    }
                }
                break;

            case "BPM":
                if (!file_exists($CacheFile)) {
                    $fh = fopen($CacheFile, 'w');
                    fclose($fh);
                    $ArquivoUnico .= file_get_contents($DefaultFile);
                    $ArquivoUnico .= file_get_contents($File);
                    $output = Yii::app()->JSMin->Compilar($ArquivoUnico);
                    file_put_contents($CacheFile, $output);
                } else {
                    if (!$this->VerificarCache($DefaultFile, $File, $CacheFile)) {
                        $ArquivoUnico .= file_get_contents($DefaultFile);
                        $ArquivoUnico .= file_get_contents($File);
                        $output = Yii::app()->JSMin->Compilar($ArquivoUnico);
                        file_put_contents($CacheFile, $output);
                    }
                }
                break;

            case "DS":
                if (!file_exists($CacheFile)) {
                    $fh = fopen($CacheFile, 'w');
                    fclose($fh);
                    $ArquivoUnico .= file_get_contents($DefaultFile);
                    $ArquivoUnico .= file_get_contents($File);
                    $output = Yii::app()->JSMin->Compilar($ArquivoUnico);
                    file_put_contents($CacheFile, $output);
                } else {
                    if (!$this->VerificarCache($DefaultFile, $File, $CacheFile)) {
                        $ArquivoUnico .= file_get_contents($DefaultFile);
                        $ArquivoUnico .= file_get_contents($File);
                        $output = Yii::app()->JSMin->Compilar($ArquivoUnico);
                        file_put_contents($CacheFile, $output);
                    }
                }
                break;

            case "THEMES":
                break;
            case "LAYOUTS":
                break;
            case "FA-VALUE_PB.":
                break;
            case "LOGIN":
                break;
            case "FAVICON.ICO":
                break;
            case "SITE":
                break;
            case "SAIR":
                break;
            case "ASSETS":
                break;
            default:
                if (!file_exists($CacheFile)) {
                    $fh = fopen($CacheFile, 'w');
                    fclose($fh);
                    $ArquivoUnico = file_get_contents($DefaultFile);
                    $output = Yii::app()->JSMin->Compilar($ArquivoUnico);
                    file_put_contents($CacheFile, $output);
                } else {
                    if (!$this->VerificarCache($DefaultFile, $File, $CacheFile)) {
                        $ArquivoUnico = file_get_contents($DefaultFile);
                        $output = Yii::app()->JSMin->Compilar($ArquivoUnico);
                        file_put_contents($CacheFile, $output);
                    }
                }
                break;
        }
    }

    /**
     * Cria um Compilado & Cachea o CSS
     */
    protected function CacheCSS(CEvent $event) {
        $DefaultFile = Yii::app()->request->baseUrl . "themes/classic/assets/css/DocSystem/DocSystem_MAIN.css";
        $File = Yii::app()->request->baseUrl . "themes/classic/assets/css/DocSystem/DocSystem_" . Yii::app()->params['Modulo'] . ".css";
        $CacheDiretoryWS = Yii::app()->request->baseUrl . "assets/" . md5(Yii::app()->session['WorkSpace']) . "/";
        $CacheDiretoryCSS = Yii::app()->request->baseUrl . "assets/" . md5(Yii::app()->session['WorkSpace']) . "/css/";
        $CacheFile = $CacheDiretoryWS . "css/" . md5(Yii::app()->params['Modulo']) . ".css";
        if (!file_exists($CacheDiretoryWS)) {
            mkdir($CacheDiretoryWS, 0777);
        }
        if (!file_exists($CacheDiretoryCSS)) {
            mkdir($CacheDiretoryCSS, 0777);
        }
        $ArquivoUnico = '';
        switch (Yii::app()->params['Modulo']) {
            case "CRM":
            case "ECM":
            case "BPM":
            case "DS":
                if (!file_exists($CacheFile)) {
                    $fh = fopen($CacheFile, 'w');
                    fclose($fh);
                    $ArquivoUnico .= file_get_contents($DefaultFile);
                    $ArquivoUnico .= file_get_contents($File);
                    $output = Yii::app()->JSMin->CompilarCSS($ArquivoUnico);
                    file_put_contents($CacheFile, $output);
                } else {
                    if (!$this->VerificarCache($DefaultFile, $File, $CacheFile)) {
                        $ArquivoUnico .= file_get_contents($DefaultFile);
                        $ArquivoUnico .= file_get_contents($File);
                        $output = Yii::app()->JSMin->CompilarCSS($ArquivoUnico);
                        file_put_contents($CacheFile, $output);
                    }
                }
                break;
            case "THEMES":
                break;
            case "LAYOUTS":
                break;
            case "FA-VALUE_PB.":
                break;
            case "LOGIN":
                break;
            case "FAVICON.ICO":
                break;
            case "SITE":
                break;
            case "SAIR":
                break;
            case "ASSETS":
                break;
            default:
                if (!file_exists($CacheFile)) {
                    $fh = fopen($CacheFile, 'w');
                    fclose($fh);
                    $ArquivoUnico = file_get_contents($DefaultFile);
                    $output = Yii::app()->JSMin->CompilarCSS($ArquivoUnico);
                    file_put_contents($CacheFile, $output);
                } else {
                    if (!$this->VerificarCache($DefaultFile, $File, $CacheFile)) {
                        $ArquivoUnico = file_get_contents($DefaultFile);
                        $output = Yii::app()->JSMin->CompilarCSS($ArquivoUnico);
                        file_put_contents($CacheFile, $output);
                    }
                }
                break;
        }
    }

    /**
     * Pega Protocolo HTTP/HTTPS
     * @param CEvent $event
     */
    public function getProtocol(CEvent $event) {
        Yii::app()->params['Protocolo'] = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    }

}
