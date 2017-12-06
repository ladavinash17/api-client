<?php
/**
 * Created by PhpStorm.
 * User: Avinash Lad
 * Date: 29/11/17
 * Time: 7:18 PM
 */

namespace ApiClient\Api;


use Symfony\Component\HttpFoundation\Response;

class ApiClient extends BaseApi
{
    private $host;
    private $allowedMethods;

    public function __construct($options, $configKey)
    {
        //Setting Config Headers
        $headers            = (isset($options[$configKey]['headers'])) ? $options[$configKey]['headers'] : [];
        $options['headers'] = $headers;
        parent::__construct($options);

        $host = (isset($options[$configKey]['host'])) ? $options[$configKey]['host'] : "";
        $this->setHost($host);

        $allowedMethods = (isset($options[$configKey]['allowed_methods'])) ? $options[$configKey]['allowed_methods'] : [];
        $this->setAllowedMethods($allowedMethods);
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return mixed
     */
    public function getAllowedMethods()
    {
        return $this->allowedMethods;
    }

    /**
     * @param mixed $allowedMethods
     */
    private function setAllowedMethods($allowedMethods)
    {
        $this->allowedMethods = $allowedMethods;
    }

    public function checkIfMethodAllowed($method)
    {
        $allowedMethods = $this->getAllowedMethods();

        if (empty($allowedMethods)) {
            return true;
        }

        if (!in_array(strtoupper($method), $allowedMethods)) {
            return false;
        }

        return true;
    }

    public function __call($method, $arguments)
    {
        if (!$this->checkIfMethodAllowed($method)) {
            return null;
        }

        $url     = (isset($arguments[0])) ? $arguments[0] : [];
        $params  = (isset($arguments[1])) ? $arguments[1] : [];
        $payload = (isset($arguments[2])) ? $arguments[2] : [];
        $headers = (isset($arguments[3])) ? $arguments[3] : [];

        $this->setMethod(strtoupper($method));

        return $this->processApi($url, $params, $payload, $headers);
    }

    public static function __callStatic($method, $arguments)
    {
        $apiFactory = new static();
        $apiFactory->__call($method, $arguments);
    }

    public function processApi($url, $params = [], $payload = [], $headers = [])
    {
        $url = $this->buildUrl($url, $params);
        $this->setUrl($url);
        $this->setHeaders($headers);
        if (!empty($payload)) {
            if ('GET' === $this->getMethod()) {
                $this->addQueries($payload);
            }
            if ('POST' === $this->getMethod() || 'PUT' === $this->getMethod()) {
                $this->setBody($payload);
            }
        }
        $this->exec();

        return $this->getResponse();
    }

    public function buildUrl($endpoint, $params)
    {
        if (false === empty($params)) {
            $endpoint = vsprintf($endpoint, $params);
        }

        $host = $this->getHost();

        if (empty($host)) {
            return $endpoint;
        }

        $baseUri = $host . $endpoint;

        return $baseUri;
    }

    public function getResponse()
    {
        return new Response(parent::getResponse(), $this->getStatusCode(), $this->getResponseHeaders());
    }

}