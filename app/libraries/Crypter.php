<?php

namespace libraries;

class Crypter {
    protected $ciphering;
    protected $IVLength;
    protected $options;
    protected $encryptionIV;
    protected $encryptionKey;
    protected $privateKey;
    protected $publicKey;

    public function __construct(
        string $ciphering,
        string $encryptionIV,
        string $encryptionKey,
        int $options = 0
    ) {
        $this->ciphering = $ciphering;
        $this->IVLength = openssl_cipher_iv_length($ciphering);
        $this->encryptionIV = $encryptionIV;
        $this->encryptionKey = sha1($encryptionKey);
        $this->options = $options;
    }

    public function encrypt(string $textToCrypt) : string {
        $encrypted = openssl_encrypt(
            $textToCrypt,
            $this->ciphering,
            $this->encryptionKey,
            $this->options,
            $this->encryptionIV
        );
        return $encrypted;
    }

    public function decrypt(string $textToDecrypt) : string {
        return openssl_decrypt(
            $textToDecrypt,
            $this->ciphering,
            $this->encryptionKey,
            $this->options,
            $this->encryptionIV
        );
    }

    // Experimental
    public function setPrivateKey(string $pathToFile) : void {
        $this->privateKey = openssl_pkey_get_private($pathToFile);
    }

    // Experimental
    public function setPublicKey(string $pathToFile) : void {
        $this->publicKey = openssl_pkey_get_public($pathToFile);
    }
}
