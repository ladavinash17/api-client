<?php
/**
 * Created by PhpStorm.
 * User: e90035929
 * Date: 29/11/17
 * Time: 7:18 PM
 */

namespace ApiClient\Api;


class ApiFactory extends BaseApi
{
    private     $env;
    private     $hosts;
    protected   $method;
    protected   $payload;
    private     $allowedMethods;

    public function __construct()
    {
        $config = $this->getConfig();
        $this->hosts = $config['hosts'];
        $this->env = strtolower($config['env']);
        $this->allowedMethods = $config['allowed_methods'];
    }

    public function __call(  $method,  $arguments) {
        if(in_array($method, $this->allowedMethods)) {

            $url     = (isset($arguments[0])) ? $arguments[0] : [];
            $payload = (isset($arguments[1])) ? $arguments[1] : [];
            $headers = (isset($arguments[2])) ? $arguments[2] : [];

            $this->setMethod(strtoupper($method));
            return $this->processApi($url, $payload, $headers);
        } else {
            return null;
        }
    }

    public static function __callStatic($method, $arguments)
    {
        $apiFactory = new static();
        $apiFactory->__call($method, $arguments);
    }

    public function processApi($url, $payload=array(), $headers=array())
    {
        $url = (is_array($url) && !empty($url)) ? $this->buildUrl($url[0], $url[1]) : $url;
        $this->setUrl($url);
        $this->setHeaders($headers);
        $this->setBody($payload);
        $this->exec();

        return $this->getResponse();
    }

    public function buildUrl($baseName, $endpoint) {

        $baseUri = $this->hosts[$this->env][$baseName] . $endpoint;
        return $baseUri;
    }

}