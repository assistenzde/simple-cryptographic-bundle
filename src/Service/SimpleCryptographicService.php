<?php

declare( strict_types=1 );

namespace Assistenzde\SimpleCryptographicBundle\Service;

/**
 * Class SimpleCryptographicService
 *
 * @package Assistenzde\SimpleCryptographicBundle
 */
final class SimpleCryptographicService
{
    /**
     * default encryption key to use in all encrpyt/decrypt calls
     *
     * @var string
     */
    protected string $key = '';

    /**
     * default cipher method to use in all encrpyt/decrypt calls
     *
     * @var string
     */
    protected string $method = 'aes-256-ctr';

    /**
     * @param string      $encryptionKey encryption key (raw binary expected)
     * @param string|null $cipherMethod  [optional] the method to use, default is 'aes-256-ctr'
     */
    public function __construct(string $encryptionKey, ?string $cipherMethod = null)
    {
        $this->key = $encryptionKey;

        if( !is_null($cipherMethod) )
        {
            $this->method = $cipherMethod;
        }
    }

    /**
     * Encrypts (but does not authenticate) a message to a ciphertext.
     *
     * @param string  $message plaintext message
     * @param string  $method  the method to use, {@see openssl_get_cipher_methods()}
     * @param string  $key     encryption key (raw binary expected)
     * @param boolean $encode  [optional] set to `true` to return a base64-encoded string
     *
     * @return string (raw binary)
     */
    public static function EncryptWithMethod(string $message, string $method, string $key, bool $encode = false): string
    {
        $nonceSize = openssl_cipher_iv_length($method);
        $nonce     = openssl_random_pseudo_bytes($nonceSize);

        $ciphertext = openssl_encrypt(
            $message,
            $method,
            $key,
            OPENSSL_RAW_DATA,
            $nonce
        );

        // Now let's pack the IV and the ciphertext together
        // Naively, we can just concatenate
        if( $encode )
        {
            return base64_encode($nonce . $ciphertext);
        }
        return $nonce . $ciphertext;
    }

    /**
     * Decrypts (but does not verify) a ciphertext back to plaintext.
     *
     * @param string $message ciphertext message
     * @param string $method  the method to use, {@see openssl_get_cipher_methods()}
     * @param string $key     encryption key (raw binary expected)
     * @param bool   $encoded [optional] `true` if a base64 encoded string is expected, else `false`, default is `false`
     *
     * @return string
     */
    public static function DecryptWithMethod(string $message, string $method, string $key, bool $encoded = false): string
    {
        if( $encoded )
        {
            $message = base64_decode($message, true);
            if( false === $message )
            {
                throw new \RuntimeException('Encryption failure');
            }
        }

        $nonceSize  = openssl_cipher_iv_length($method);
        $nonce      = mb_substr($message, 0, $nonceSize, '8bit');
        $ciphertext = mb_substr($message, $nonceSize, null, '8bit');

        return openssl_decrypt(
            $ciphertext,
            $method,
            $key,
            OPENSSL_RAW_DATA,
            $nonce
        );
    }

    /**
     * Encrypts (but does not authenticate) a message to a ciphertext.
     *
     * @param string  $message the plaintext message to encrypt
     * @param boolean $encode  [optional] set to `true` to return a base64-encoded string
     *
     * @return string (raw binary)
     */
    public function encrypt(string $message, bool $encode = false): string
    {
        return self::EncryptWithMethod($message, $this->method, $this->key, $encode);
    }

    /**
     * Decrypts (but does not verify) a ciphertext back to plaintext.
     *
     * @param string $message the ciphertext message to decrpyt
     * @param bool   $encoded [optional] `true` if a base64 encoded string is expected, else `false`, default is `false`
     *
     * @return string
     */
    public function decrypt(string $message, bool $encoded = false): string
    {
        return self::DecryptWithMethod($message, $this->method, $this->key, $encoded);
    }
}
