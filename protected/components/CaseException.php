<?php

class CaseException extends CException
{
    const CODE = 300;

    public function __construct($message, $code=self::CODE, $paramsMessage = array()) {
        parent::__construct(Yii::t($message, $code, $paramsMessage), $code);
    }
}
