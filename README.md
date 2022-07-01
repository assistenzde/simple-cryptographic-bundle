# Simple Cryptographic Bundle

[![Latest Stable Version](https://poser.pugx.org/assistenzde/simple-cryptographic-bundle/version)](https://packagist.org/packages/assistenzde/simple-cryptographic-bundle)
[![Total Downloads](https://poser.pugx.org/assistenzde/simple-cryptographic-bundle/downloads)](https://packagist.org/packages/assistenzde/simple-cryptographic-bundle)
[![License](https://poser.pugx.org/assistenzde/simple-cryptographic-bundle/license)](https://packagist.org/packages/assistenzde/simple-cryptographic-bundle)
[![PHP ≥v7.4](https://img.shields.io/badge/PHP-%E2%89%A57%2E4-0044aa.svg)](https://www.php.net/manual/en/migration72.new-features.php)
[![Symfony ≥5](https://img.shields.io/badge/Symfony-%E2%89%A55-0044aa.svg)](https://symfony.com/)

The **SimpleCryptographicService** class contains method to encrypt/decrypt phrases with symmetric encryption. With the usage
of [OpenSSL](https://www.php.net/manual/en/intro.openssl.php) each encoding of the same phrase results in a different encoded string.

Quick example usage:

```php
$cryptographicService = new SimpleCryptographicService('$ecr3t');

$encryptedString1 = $cryptographicService->encrypt('Hello world!');  // some encrypted string, i.e. 'ABCDEFG'
echo $cryptographicService->decrypt($encryptedString1);              // outputs 'Hello world!'

$encryptedString2 = $cryptographicService->encrypt('Hello world!');  // some encrypted string, i.e. 'HIJKLMNOP'
echo $cryptographicService->decrypt($encryptedString2);              // outputs 'Hello world!'
```

This library can be used easily in Symfony projects but also in non-Symfony projects.

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Symfony Configuration](#symfony-configuration)
- [Usage](#usage)
- [Credits](#credits)

## Requirements

The usage of [**PHP ≥ v7.4**](https://www.php.net/manual/en/migration74.php)
and [**Symfony ≥ 5**](https://symfony.com/doc/5.0/setup.html) is recommended.

## Installation

Please install via [composer](https://getcomposer.org/).

```bash
composer require assistenzde/simple-cryptographic-bundle
```

The bundle will be automatically added to your `bundles.yaml` configuration.

## Symfony Configuration

By default the bundle depends on the `APP_SECRET` environment variable and uses the `aes-256-ctr` cipher method. If you want to use a custom cipher key **OR** a customer cipher method create the
`config/packates/simple-cryptographic-bundle.yaml` configuration file and set the related values.

**simple-cryptographic-bundle.yaml**:

```yaml
simple-cryptographic-bundle.yaml:
  # set a custom cipher key or comment out to use the default value
  # default is %kernel.secret% (which contains the APP_SECRET value)
  key: My-cu5t0m-c1ph3r-k3y
  # set a custom cipher method or comment out to use the default value
  # default is aes-256-ctr
  cipher: camellia-128-ofb
```

## Usage

Use dependency injection to access the cryptographic service.

```php
<?php

namespace MyNamespace;

use Assistenzde\SimpleCryptographicBundle\Service\SimpleCryptographicService;

/**
 * Class AuthenticationSubscriber
 */
final class MyClass
{
    /**
     * the crypto service to encode/dedoce strings
     * 
     * @var SimpleCryptographicService
     */
    protected SimpleCryptographicService $simpleCryptographicService;

    /**
     * MyClass constructor
     * 
     * @param SimpleCryptographicService $simpleCryptographicService
     */
    public function __construct(SimpleCryptographicService $simpleCryptographicService)
    {       
        $this->simpleCryptographicService = $simpleCryptographicService;
    }

    /**
     * 
     */
    public function doSomething()
    {       
         // do some calculation and get a user token
         
         // encrypt the user token        
         $encryptedUserToken = $this->simpleCryptographicService->encrypt($userToken);
         
         // and do some more stuff,
         // esp. use the encrypted token (i.e. as request parameter or to save in a file)

         // derypt the encrypted stuff        
         $userToken = $this->simpleCryptographicService->decrypt($encryptedUserToken); 
    }
}
```

We suggest using the public methods

- **SimpleCryptographicService::encrypt** and
- **SimpleCryptographicService::decrypt**.

To temporarily use custom settings switch to the static methods

- **SimpleCryptographicService::EncryptWithMethod** and/or
- **SimpleCryptographicService::DecryptWithMethod**.

## Credits

A big thank you to stackoverflow's user [Scott Arciszewski](https://stackoverflow.com/users/2224584/scott-arciszewski) for the explanations in of
the [https://stackoverflow.com/a/30189841/4351778](https://stackoverflow.com/a/30189841/4351778) answer.


