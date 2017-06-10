<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
        public $CodeError;
        public $UsuariosModel;
        public $ByPass;
        /*
         * Autentica o usuário
         */
	public function authenticate()
	{
            $NomeDeUsuario = $this->username;
            $Senha = $this->password;
            $WorkSpace = $this->VerificaLicenca($this->VerificaDominio());
            $this->ByPass = $this->VerificaUsuarioLogado($this->username);
            
            if ($WorkSpace == 1) //WorkSpace Ativa
            {
                $this->UsuariosModel = Usuarios::model();
                $this->CodeError = $this->UsuariosModel->AutenticarUsuario($NomeDeUsuario, $this->UsuariosModel->EncryptPassword($Senha));
            } 
            else if ($WorkSpace == 0) //WorkSpace Inativa
            {
                $this->CodeError = 0;
            }           
            else if ($WorkSpace == 4) // Trial Expirado
            {
                $this->CodeError = 4; 
            }
            else if ($WorkSpace == 5) // Limite de Usuários Logados Atingido
            {
                $this->CodeError = 5; 
            }
		return $this->CodeError;
	}
        
        /**
         * Verifica Se o Usuário que esta tentando logar já esta logado
         * @param type $LoginEmail
         * @return boolean
         */
        function VerificaUsuarioLogado($LoginEmail)
        {
            $Login = Usuarios::model()->findByAttributes(array('login'=>$LoginEmail));
            $Email = Usuarios::model()->findByAttributes(array('email'=>$LoginEmail));
            if (count($Login) > 0)
            {
                $Count = count(SessoesAtivas::model()->findByPk($Login->id_usuario)); 
                if ($Count > 0)
                {
                    SessoesAtivas::model()->FinalizarForce($Login->id_usuario);
                    HistoricoLogin::model()->SalvaLogout($Login->id_usuario);
                    return true;
                }
                else
                {
                    return false;
                }
                
            }
            if (count($Email) > 0)
            {
                $Count = count(SessoesAtivas::model()->findByPk($Email->id_usuario)); 
                if ($Count > 0)
                {
                    SessoesAtivas::model()->FinalizarForce($Email->id_usuario);
                    HistoricoLogin::model()->SalvaLogout($Email->id_usuario);
                    return true;
                }
                else
                {
                    return false;
                }
            }
            return false;
        }
        
        /**
         * Verifica a licença da WorkSpace
         * @param type $Dominio
         * @return int
         */
        function VerificaLicenca($Dominio)
        {
            try
            {
                $connection = Yii::app()->db;
                $sql = "SELECT ativo, workspace, CRM, ERP, ECM, BPM, BI, WCM, CMS, SNK, IMOB, trial, trial_expire, unidades_limite, usuarios_limite, sessoes_limite, ComercialDS, tamanho_disco FROM dev.ds_workspaces where workspace='{$Dominio}' AND ativo=1";
                $WS = $connection->createCommand($sql)->query();
                $WS->bindColumn(1, $Ativo);
                $WS->bindColumn(2, $WorkSpace);
                $WS->bindColumn(3, $CRM);
                $WS->bindColumn(4, $ERP);
                $WS->bindColumn(5, $ECM);
                $WS->bindColumn(6, $BPM);
                $WS->bindColumn(7, $BI);
                $WS->bindColumn(8, $WCM);
                $WS->bindColumn(9, $CMS);
                $WS->bindColumn(10, $SNK);
                $WS->bindColumn(11, $IMOB);
                $WS->bindColumn(12, $Trial);
                $WS->bindColumn(13, $TrialExpire);
                $WS->bindColumn(14, $UnLimite);
                $WS->bindColumn(15, $UsLimite);
                $WS->bindColumn(16, $SesLimite);
                $WS->bindColumn(17, $ComercialDS);
                $WS->bindColumn(18, $TamanhoDisco);
                while($WS->read()!==false)
                {
                    // Faz a leitura das colunas
                }
                Yii::app()->session['WorkSpace'] = $WorkSpace;
                Yii::app()->session['CRM'] = $CRM;
                Yii::app()->session['ERP'] = $ERP;
                Yii::app()->session['ECM'] = $ECM;
                Yii::app()->session['BPM'] = $BPM;
                Yii::app()->session['BI'] = $BI;
                Yii::app()->session['WCM'] = $WCM;
                Yii::app()->session['CMS'] = $CMS;
                Yii::app()->session['SNK'] = $SNK;
                Yii::app()->session['IMOB'] = $IMOB;
                Yii::app()->session['DS'] = $ComercialDS;
                Yii::app()->session['LimiteUsuario'] = $UsLimite;
                Yii::app()->session['LimiteUnidade'] = $UnLimite;
                Yii::app()->session['LimiteSession'] = $SesLimite;
                Yii::app()->session['TamanhoDisco'] = $TamanhoDisco;
                                
                if ($Trial == 1)
                {
                    Yii::app()->session['Trial'] = $Trial;
                    Yii::app()->session['TrialDate'] = $TrialExpire;
                    $Ativo = $this->VerifyTrial();
                }
                if($SesLimite > 0 && $this->ByPass == false)
                {
                    $Ativo = SessoesAtivas::model()->VerifySession();
                }
            }
            catch(Exception $e) // caso a consulta falhe
            {
                $Ativo = 0; //WorkSpace Não Encontrada ou Inativa
            }
            return $Ativo;
        }
        
        /*
         * Verifica o dominio acessado
         */
        function VerificaDominio()
        {
            $dominio = explode(".",$_SERVER['HTTP_HOST']); 
            return $dominio[0];
        }
        
        /**
         * Verifica se a workspace trial esta expirada ou não
         */
        protected function VerifyTrial()
        {
            $DataAtual = explode(' ', Yii::app()->localtime->toLocalDateTime(Yii::app()->localtime->UTCNow, 'medium', 'medium'));
            if ($DataAtual[0] >= Yii::app()->session['TrialDate'])
            {
                return 4;
            }
            return 1;
        }
}