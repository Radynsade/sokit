<?php

namespace libraries;

class Crypter {
    protected $ciphering;
    protected $IVLength;
    protected $options;
    protected $encryptionIV;
    protected $encryptionKey;

    public function __construct(
        string $ciphering,
        string $encryptionIV,
        string $encryptionKey,
        int $options = 0
    ) {
        $this->ciphering = $ciphering;
        $this->IVLength = openssl_cipher_iv_length($ciphering);
        $this->encryptionIV = $encryptionIV;
        $this->encryptionKey = $encryptionKey;
        $this->options = $options;
    }

    public function encrypt(string $textToCrypt) {
        $encrypted = openssl_encrypt(
            $textToCrypt,
            $this->ciphering,
            $this->encryptionKey,
            $this->options,
            $this->encryptionIV
        );
        return $encrypted;
    }

    public function decrypt(string $textToDecrypt) {
        return openssl_decrypt(
            $textToDecrypt,
            $this->ciphering,
            $this->encryptionKey,
            $this->options,
            $this->encryptionIV
        );
    }
}
