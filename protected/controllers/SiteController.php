<?php

class SiteController extends Controller {
  
    /**
     *  
     * 
     * Declares class-based actions.
     */ 
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),  
            // page action renders "static" pages stored under 'protected/views/site/pages ' 
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }
    
    /**
     * (PHP 5)
     * 
     * Search through cities according to defined value
     * 
     * @param String $value
     */
    public function actionGetCidades($value = null) {
        $estados = (new DsCidades)
                ->with(['estado'])
                ->findAll([
                    'condition' => "nome LIKE '%{$value}%'",
                    'limit' => 10,
                    'order' => 't.nome'
                ]);
        
        $cidades = [];
        foreach ($estados as $cidade) {                     
            $cidades[] = [
                'id' => $cidade->id_cidade, 
                'text' => "{$cidade->nome} ({$cidade->estado->sigla})",
                'hasOthers' => count($cidade->franquias) > 0
            ];
        }
        
        echo json_encode($cidades);
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        $this->render('index');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     *  
     * Displays the contact page
     */       
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                        "Reply-To: {$model->email}\r\n" .
                        "MIME-Version: 1.0\r\n" .
                        "Content-type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin()  {
        $model = new LoginForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(array('/home'));
        }
        // display the login form
        $this->layout = '//layouts/login';
        $this->render('login', array('model' => $model));
//        $this->render('login_manu', array('model' => $model));
    }
    /**
     * Tela de Setup do Usuário
     */
    public function actionSetup() {
        Yii::import('application.modules.Configs.controllers.UsuariosController');
        $UserSetup = new UsuariosController(true);
        $model = $UserSetup->loadModel(Yii::app()->session['ID']);
        $model->scenario = 'update';
        $UserSetup->saveModelSetup($model);

        $this->layout = '//layouts/login';
        $this->render('setup', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        HistoricoLogin::model()->SalvaLogout(Yii::app()->session['ID']); // Salva Logout do Usuário no Histórico
        SessoesAtivas::model()->DestroySession(Yii::app()->session['ID']); // Destroi sessão do usuário na database
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->user->loginUrl);
    }

    /**
     * Listar Perfis.
     */
    public function actionEcmperfis() {
        extract($_POST);

        Yii::import('application.modules.ECM.models.PerfilOpcoes');
        $PerfilOpcoes = new PerfilOpcoes(true);

        //role_id
        $result_role = $PerfilOpcoes->findAll("t.roleid = '$id'");

        foreach ($result_role AS $role) {
            $id = $role->attributes['id'];
            $roleid = $role->attributes['roleid'];
            $nome = $role->attributes['nome'];
            $opcoes = $role->attributes['opcoes'];
            $dispositivo = $role->attributes['dispositivo'];

            $perfis[] = array("id" => $id, "roleid" => $roleid, "nome" => $nome, "opcoes" => $opcoes, "dispositivo" => $dispositivo);
        }

        echo json_encode($perfis);
    }

    /**
     * Listar Opções.
     */
    public function actionEcmopcoes() {
        extract($_POST);

        Yii::import('application.modules.ECM.models.PerfilOpcoes');
        $PerfilOpcoes = new PerfilOpcoes(true);

        //role_id
        $result_opcoes = $PerfilOpcoes->findAll(array("select" => "t.opcoes", "condition" => "t.id = '$id'"));

        $arr_opcoes = array();

        $setadas = str_replace('opcoes', '', str_replace('"', '', $result_opcoes[0]->attributes["opcoes"]));

        $_opcoes = explode("|", $setadas);

        foreach ($_opcoes AS $opcoes) {
            $opt = explode(",", $opcoes);
            $arr_opcoes[trim($opt[0])] = trim($opt[1]);
        }

        echo json_encode($arr_opcoes);
    }

    public function actionSocketIO() {
        header("Content-type: text/javascript");
        header('Access-Control-Allow-Origin: *');
        $Versao = $_GET['v'];
        if (file_exists(Yii::app()->request->baseUrl . "themes/classic/assets/js/socket.io-" . $Versao . ".js")) {
            echo file_get_contents(Yii::app()->request->baseUrl . "themes/classic/assets/js/socket.io-" . $Versao . ".js");
        } else {
            echo 'null';
        }
    }

    public function actionMinhasUnidades() {
//        Yii::import('application.modules.Configs.controllers.UsuariosController');
//        $UserSetup = new UsuariosController(true);
//        $model = $UserSetup->loadModel(Yii::app()->session['ID']);
//        $model->scenario = 'update';
//        $UserSetup->saveModelSetup($model);
        $this->layout = '//layouts/login';
//        echo $_GET['ref'];
        $this->render('minhasunidades');
    }
 
    /*
     * Componente de acesso do RBAC
     * 
     */
    /*public function behaviors()
    {
        return array(
            'RBACAccessComponent'=>array(
                'class'=>'application.modules.rbac.components.RBACAccessVerifier',
                // optional default settings
                'checkDefaultIndex'=>'id', // used with buisness Rules if no Index given
                'allowCaching'=>false,  // cache RBAC Tree -- do not enable while development ;)
                'accessDeniedUrl'=>'/user/login',// used if User is logged in
                'loginUrl'=>'/login'// used if User is NOT logged in
            ),
        );
    }*/
    
}
