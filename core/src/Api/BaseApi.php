<?php
/**
 * Created by PhpStorm.
 * User: Avinash Lad
 * Date: 29/11/17
 * Time: 4:24 PM
 */

namespace ApiClient\Api;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

class BaseApi
{
    private $config;
    private $url;
    private $method;
    private $headers;
    private $body;
    private $query;
    private $client;
    private $response;
    private $statusCode;
    private $responseHeaders;
    private $isDefaultHeaders;

    public function __construct()
    {
        $this->config = $this->get('config')->get('api');
        $this->client = new Client([
            'timeout' => $this->config['connection_timeout']
        ]);

        $this->setIsDefaultHeaders($this->config['is_default_headers']);
    }

    /**
     * @return array
     */
    protected function getConfig()
    {
        return $this->config;
    }

    public function setDefaultHeaders() {
        $defaultHeaders = $this->config['default_headers'];

        if(!empty($defaultHeaders)) {
            foreach($defaultHeaders as $key => $value) {
                $this->headers[str_replace("_", "-", $key)] = $value;
            }
        }
    }

    /**
     * @return boolean
     */
    public function getIsDefaultHeaders()
    {
        return $this->isDefaultHeaders;
    }

    /**
     * @param boolean $isDefaultHeaders
     */
    public function setIsDefaultHeaders($isDefaultHeaders)
    {
        $this->isDefaultHeaders = (boolean) $isDefaultHeaders;
        if($this->isDefaultHeaders === true) {
            $this->setDefaultHeaders();
        }
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->method = strtoupper($method);
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param mixed $headers
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param mixed $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return mixed
     */
    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }

    protected function exec()
    {
        try {
            $this->response = $this->client->request(
                $this->getMethod(),
                $this->getUrl(),
                [
                    'headers' => $this->getHeaders(),
                    'query' => $this->getQuery(),
                    'body' => $this->getBody()
                ]
            );
        } catch (ClientException $e) {
            $this->response   = $e->getResponse()->getBody()->getContents();
            $this->statusCode = 400;
            //var_dump($this->getUrl(), $this->getBody());
        } catch (ServerException $e) {
            echo ($e->getResponse()->getBody()->getContents());
            exit;
        }
    }
}