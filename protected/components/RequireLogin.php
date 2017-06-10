<?php

class RequireLogin extends CBehavior {

    public function attach($owner) {
        $owner->attachEventHandler('onBeginRequest', array($this, 'handleBeginRequest'));
    }

    public function handleBeginRequest($event) {
        $defaultUrl = '';
        
        $request = trim(Yii::app()->urlManager->parseUrl(Yii::app()->request), '/');
        $login = trim(Yii::app()->user->loginUrl[0], '/');
        $login = trim(is_array(Yii::app()->user->loginUrl) ? Yii::app()->user->loginUrl[0] : Yii::app()->user->loginUrl, '/'); //Pega a configuração do Login URL

//        $registration = trim(Yii::app()->params['registrationUrl'], '/'); //Pega na configuração a URL da página que sera desprotegida
//        $recovery = trim(Yii::app()->params['recoveryUrl'], '/'); //Pega na configuração a URL da página que sera desprotegida
//        $captchUrl = trim(Yii::app()->params['captchUrl'], '/'); //Pega na configuração a URL da página que sera desprotegida
        
        // Páginas publicas aos visitantes
        $allowed = array($login, $defaultUrl);

        if (Yii::app()->user->isGuest && !in_array($request, $allowed))
            Yii::app()->user->loginRequired();

        // Previne usuarios logados de vizualizarem a página de login
        $request = substr($request, 0, strlen($login));
        if (!Yii::app()->user->isGuest && $request == $login) {
            $url = Yii::app()->createUrl(Yii::app()->homeUrl[0]);
            Yii::app()->request->redirect($url);
        }
    }

}
