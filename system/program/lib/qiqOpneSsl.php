<?php
  class OpenSsl
  {
    private $method     = 'aes256';
    private $raw_output = false;

    public function __construct()
    {
      $this->iv         = substr(md5(IV_KEY) , 0, -16);
    }
    /* 暗号化 */
    public function encrypt($value)
    {
      $encrypted = openssl_encrypt($value, $this->method, PASS_KEY, $this->raw_output, $this->iv);
      return base64_encode($encrypted);
    }
    //  復号化
    public function decrypt($value)
    {
      $decrypted = base64_decode($value, true);
      return openssl_decrypt($decrypted, $this->method, PASS_KEY, $this->raw_output, $this->iv);
    }
  }