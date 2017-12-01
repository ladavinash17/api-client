ApiClient, PHP HTTP client
==========================

[![Build Status](https://travis-ci.org/guzzle/guzzle.svg?branch=master)](https://travis-ci.org/guzzle/guzzle)

ApiClient is a PHP HTTP client that makes it easy to send HTTP requests and
trivial to integrate with web services.

- Simple interface for building query strings, POST requests, uploading JSON data,
  etc...
- Abstracts away the underlying HTTP transport, allowing you to write
  environment and transport agnostic code; i.e., no hard dependency on cURL,
  PHP streams, sockets, or non-blocking event loops.

## Installing ApiClient

The recommended way to install ApiClient is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, run the Composer command to install the latest stable version of Guzzle:

```bash
php composer.phar require guzzlehttp/guzzle
```

Next, run the Composer command to install the latest stable version of ApiClient:

```bash
php composer.phar require ladavinash17/api-client
```

You can then later update ApiClient using composer:

 ```bash
composer.phar update