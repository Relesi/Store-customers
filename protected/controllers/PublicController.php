<?php

class PublicController extends CController {

    public $layout = "public";
    
    public function actionGetLogo($workspace = null) {
        if (is_null($workspace))
            $workspace = Yii::app()->session['WorkSpace'];

        $file = "/mnt/storage/docsystem_dscloud_" . strtoupper(md5($workspace)) . "/Geral/logo.jpg";
        if (is_file($file))
            $file = file_get_contents($file);
        else
            $file = file_get_contents(__DIR__ . '/../../uploads/logo/default.png');

        header("Content-type: image/jpeg");
        echo $file;        
    }

    /**
     * (PHP 5)
     * 
     * In case URL is invalid
     */
    private function invalidURL() {
        $this->render('invalid_url');
    }

    /**
     * (PHP 5)
     * 
     * In case URL expired
     * 
     * @param String $last_valid_date
     */
    private function expiredURL($last_valid_date) {
        $this->render('expired_url', [
            'last_valid_date' => $last_valid_date
        ]);
    }

    private function authenticateAccess() {
        $this->render('auth_access');
    }

    /**
     * (PHP 5)
     * 
     * Render Document thumbnails
     * 
     * @param String $filepath
     */
    public function actionLoadThumbnail($filepath) {
        //Download file
        $storage = Yii::app()->storage;
        $storage->server()->convertFile($filepath);
        $nfilepath = $storage->server()->createThumbnail($filepath);
        
        if (!is_null($nfilepath))
            $filepath = $nfilepath;
        
        echo "<img src=\"/Downloads/{$storage->server()->getRepositoryName()}_" . basename($filepath) . "\">";
    }

    /**
     * (PHP 5)
     * 
     * Render public Documents
     * 
     * @param String $key
     */
    public function actionDocument($key) {
        Yii::import('application.modules.ECM.models.DocumentoLink');
        Yii::import('application.modules.ECM.models.Documento');
        Yii::import('application.modules.ECM.models.Template');
        Yii::import('application.modules.ECM.models.Arquivo');
        $request = Yii::app()->request;

        $link = DocumentoLink::model()->findByAttributes(['codigo' => $key]);
        if (is_null($link)) {
            $this->invalidURL();
        } else if ($link->isValid()) {
            //Checks if Document needs authentication
            if ($link->hasPass() && !$request->isPostRequest)
                return $this->authenticateAccess();

            //Checks if password is correct
            if ($request->isPostRequest && $link->senha != strtoupper(md5($request->getPost('authpass'))))
                return $this->authenticateAccess();
            
            //Show off Document
            return $this->render('public_ecm_documento', [
                        'documento' => Documento::model($link->fk_id_template)->with(['template'])->findByPk($link->fk_id_documento)
            ]);
        } else {
            //Disable link
            if ($link->ativo == 1) {
                $link->ativo = 0;
                $link->save(false);
            }

            $this->expiredURL(Yii::app()->localtime->fromUTC($link->validade, 'd/m/Y H:i:s'));
        }
    }

}
