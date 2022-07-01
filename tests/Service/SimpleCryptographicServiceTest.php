<?php

declare( strict_types=1 );

namespace Assistenzde\SimpleCryptographicBundle\UnitTests\Service;

use Assistenzde\SimpleCryptographicBundle\Service\SimpleCryptographicService;
use PHPUnit\Framework\TestCase;

/**
 * Class SimpleCryptographicServiceTest
 *
 * @package Assistenzde\SimpleCryptographicBundle
 */
final class SimpleCryptographicServiceTest extends TestCase
{
    /**
     * Returns a string with a variable length. The length will be randomized with the help of binomial distribution.
     *
     * @param int    $minLength the minimum lenght of the string
     * @param int    $maxLength the maximum lenght of the string
     * @param string $chars     [optional] all possible characters in the char, default is alphanum
     *
     * @return string a random string
     */
    protected function getRandomString(int $minLength, int $maxLength, string $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'): string
    {
        $length = mt_rand($minLength, $maxLength);

        $randomString = '';

        $charLength = strlen($chars);

        for( $_i = 0; $_i < $length; $_i++ )
        {
            $randomString .= $chars[ mt_rand(0, $charLength - 1) ];
        }

        return $randomString;
    }

    public function dataProvider(): array
    {
        return [
            [''],
            ['hello world'],
            ['´ÓºÜ¾ÃÒÔÇ°¿ªÊ¼从óÜ很0½“いう‡l¿ø久ﷰòñúrÛﷵ'],
        ];
    }

    /**
     * @dataProvider dataProvider
     *
     * @covers       SimpleCryptographicService::encrypt
     * @covers       SimpleCryptographicService::decrypt
     */
    public function testSimpleEncrypt($input)
    {
        $cryptographicService = new SimpleCryptographicService($this->getRandomString(64, 128));

        $encryptedString = $cryptographicService->encrypt($input);
        self::assertEquals($input, $cryptographicService->decrypt($encryptedString));
    }

    /**
     * @dataProvider dataProvider
     *
     * @covers       SimpleCryptographicService::encrypt
     * @covers       SimpleCryptographicService::decrypt
     */
    public function testBase64Encrypt($input)
    {
        $cryptographicService = new SimpleCryptographicService($this->getRandomString(64, 128), 'aes-192-cbc');

        $decryptedBase64 = base64_encode($input);
        $encryptedString = $cryptographicService->encrypt($decryptedBase64, true);
        self::assertEquals($decryptedBase64, $cryptographicService->decrypt($encryptedString, true));
    }

    /**
     * @dataProvider dataProvider
     *
     * @covers       SimpleCryptographicService::EncryptWithMethod
     * @covers       SimpleCryptographicService::DecryptWithMethod
     */
    public function testEncryptWithMethod($input)
    {
        foreach( ['des-cbc', 'camellia-128-ofb', 'aria-128-cfb1'] as $_method )
        {
            if( !in_array($_method, openssl_get_cipher_methods()) )
            {
                continue;
            }

            $_key = $this->getRandomString(64, 128);

            $_encryptedString = SimpleCryptographicService::EncryptWithMethod($input, $_method, $_key);

            self::assertEquals($input, SimpleCryptographicService::DecryptWithMethod($_encryptedString, $_method, $_key));
        }
    }
}
