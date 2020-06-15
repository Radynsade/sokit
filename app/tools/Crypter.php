<?php

namespace tools;

class Crypter {
    private $ciphering;
    private $IVLength;
    private $options;
    private $encryptionIV;
    private $encryptionKey;

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
        return openssl_encrypt(
            $textToCrypt,
            $this->ciphering,
            $this->encryptionKey,
            $this->options,
            $this->encryptionIV
        );
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
