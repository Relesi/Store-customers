<?php

class SecurityCrypto extends CApplicationComponent
{
    private $encryptKey = 'GaWByHnCwdMEeKXW';
    private $iv = '6354736478937123';
    private $blocksize = 16;

    public function Decrypt($data)
    {
        return $this->UnPad(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, 
            $this->encryptKey, 
            hex2bin($data),
            MCRYPT_MODE_CBC, $this->iv), $this->blocksize);
    }

    public function Encrypt($data)
    {
        //don't use default php padding which is '\0'
        $pad = $this->blocksize - (strlen($data) % $this->blocksize);
        $data = $data . str_repeat(chr($pad), $pad);
        return bin2hex(mcrypt_encrypt(MCRYPT_RIJNDAEL_128,
            $this->encryptKey,
            $data, MCRYPT_MODE_CBC, $this->iv));
    }

    private function UnPad($str, $blocksize)
    {
        $len = mb_strlen($str);
        $pad = ord( $str[$len - 1] );
        if ($pad && $pad < $blocksize) {
            $pm = preg_match('/' . chr($pad) . '{' . $pad . '}$/', $str);
            if( $pm ) {
                return mb_substr($str, 0, $len - $pad);
            }
        }
        return $str;
    }
}