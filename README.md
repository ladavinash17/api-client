ApiClient, PHP HTTP client
==========================

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
```

## User Guide

First you need to set few parameters which can be configured for different environments, projects. 

In Symfony, for eg:
```yaml
parameters:
    api_client:
        connection_timeout:   2.0
        allowed_methods:      ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']

        your_project_key:
            host:     "http://your-domain.com"
            headers:
                #You can mention key-value pair here,
                # internal code will replace "_" with "-" in key
                token:  ThisIsYourSecretTokenForApiAuthorization
``` 

In above yaml file, ``api_client``, ``connection_timeout``, ``allowed_methods``, ``host``, ``headers`` are keywords which are to be used as it is. ApiClient reads those values based on those keys.
``your_project_key`` key is your own custom project key, You can change this as per your way.

To set those basic parameters you just need to pass the above array to ApiClient().
For Eg:
```php
//$apiOptions is the array from the above paramaters.yaml.
$apiOptions = $container->getParameter('api_client');
$apiClient = new ApiClient($apiOptions, 'your_project_key');
return $apiClient->get(
    '/v1/products',             // Route endpoint
    [],                         // url parameters to be replaced in route endpoint
    ['page'=> 1],               // query parameters
    ['x-total-count' => 'Yes']  // headers other than default set in parameters.yaml
);
``` 

It is not mandatory to use parameters.yaml only. You can keep those settings anywhere as per your framework, as per your convenience.
You just need to pass the array in the same format for ApiClient to work properly.

In Project settings, ``host`` is mandatory, failing which the ApiClient will throw error. You just need to mention Base URI/Host name in ``host``.
``headers`` is optional. But if you need to pass any headers by default with every request, you can set it here.
Also you can pass headers in get(), post() etc functions also.

Note: ApiClient always applies 'accept' = 'application/json' and 'content-type' = 'application/json' in headers by default.