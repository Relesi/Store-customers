<?php

class SecurityAdvanced extends CApplicationComponent {

    ///////////////////////////////////////////////////////////////////////
    /// ENCRYPT & DECRYPT (ATUALMENTE UTILIZADO NO GERENCIADOR DE EMAIL ///
    //////////////////////// Encode(); Decode(); //////////////////////////
    ///////////////////////////////////////////////////////////////////////

    public function sonKey() {
        return "*(=_=)*<@#QWERTY96UIOPASDFG69HJKLÇZ96XCVBNM#@>*(=_=)*"; //CHAVE
    }

    public function Encode($str) {
        $key = $this->sonKey();
        return strtr(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $str, MCRYPT_MODE_CBC, md5(md5($key)))), '+/=', '-_~');
    }

    public function Decode($encoded) {
        $key = $this->sonKey();
        return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode(strtr($encoded, '-_~', '+/=')), MCRYPT_MODE_CBC, md5(md5($key))), "");
    }

}
