<?php

namespace App;

class Crypto
{
    public static function encrypt($plainText, $key)
    {
        $secretKey = hex2bin(md5($key));
        $initVector = hex2bin('000102030405060708090a0b0c0d0e0f');
        $encryptedText = openssl_encrypt(
            $plainText,
            'AES-128-CBC',
            $secretKey,
            OPENSSL_RAW_DATA,
            $initVector
        );
        return bin2hex($encryptedText);
    }

    public static function decrypt($encryptedText, $key)
    {
        $secretKey = hex2bin(md5($key));
        $initVector = hex2bin('000102030405060708090a0b0c0d0e0f');
        $encryptedText = hex2bin($encryptedText);
        $decryptedText = openssl_decrypt(
            $encryptedText,
            'AES-128-CBC',
            $secretKey,
            OPENSSL_RAW_DATA,
            $initVector
        );
        return $decryptedText;
    }
}
