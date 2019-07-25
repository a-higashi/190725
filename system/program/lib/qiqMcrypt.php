<?php
/*
    注意点 URLでencrypt()を使う場合は、必ずurlencode()を使うこと
*/

class Mcrypt
{
    private $td;
    private $iv;
    private $key;

    private function initialize()
    {
        /* Open the cipher */
        $this->td = mcrypt_module_open('des', '', 'ecb', '');

        /* Create the IV and determine the keysize length*/
        if (!$this->iv) {
            $this->iv = substr(md5(IV_KEY) , 0, -16);
        }
        $ks = mcrypt_enc_get_key_size($this->td);

         /* Create key */
        $this->key = substr(hash("sha256", SECRET), 0, $ks);
    }

    public function encrypt($value)
    {

        $value = base64_encode($value);

        $this->initialize();

        /* Intialize encryption */
        mcrypt_generic_init($this->td, $this->key, $this->iv);

        /* Encrypt data */
        $encrypted = mcrypt_generic($this->td, $value);

        // $encrypted = substr(rtrim($encrypted, "\0"), 0, -1);

        $this->finalize();

        $encrypted = base64_encode($encrypted);

        $encrypted = substr(rtrim($encrypted, "\0"), 0, -1);

        return $encrypted;
    }

    public function decrypt($value)
    {

        $decrypted = base64_decode($value, true);


        $this->initialize();

        /* Intialize encryption */
        mcrypt_generic_init($this->td, $this->key, $this->iv);


        /* Decrypt encrypted string */
        $decrypted = mdecrypt_generic($this->td, $decrypted);


        $decrypted = base64_decode($decrypted, true);


        $this->finalize();

        return $decrypted;
    }

    private function finalize()
    {
        /* Terminate handle and close module */
        mcrypt_generic_deinit($this->td);
        mcrypt_module_close($this->td);
    }
}