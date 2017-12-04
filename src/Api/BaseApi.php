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
    private $url;
    private $method;
    private $headers;
    private $body;
    private $query;
    private $client;
    private $response;
    private $statusCode;
    private $responseHeaders;

    public function __construct(array $options)
    {
        $this->client = new Client(
            [
                'timeout' => $options['connection_timeout'],
            ]
        );

        /** Setting default headers */
        $defaultHeaders = [
            'accept'       => 'application/json',
            'content-type' => 'application/json',
        ];

        /** Adding headers from config/parameters to default headers */
        if (isset($options['headers']) && !empty($options['headers'])) {
            $defaultHeaders = array_merge($defaultHeaders, $options['headers']);
        }

        $this->setHeaders($defaultHeaders);
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
        foreach ($headers as $key => $value) {
            $this->setHeader($key, $value);
        }
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function setHeader($key, $value)
    {
        $this->headers[str_replace("_", "-", $key)] = $value;
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
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public function setQuery($key, $value)
    {
        $this->query[$key] = $value;

        return $this;
    }


    protected function addQueries(array $query = [])
    {
        foreach ($query as $key => $value) {
            $this->setQuery($key, $value);
        }

        return $this;
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

            $response = $this->client->request(
                $this->getMethod(),
                $this->getUrl(),
                [
                    'headers' => $this->getHeaders(),
                    'query'   => $this->getQuery(),
                    'body'    => $this->getBody(),
                ]
            );

            $this->response        = $response->getBody()->getContents();
            $this->statusCode      = $response->getStatusCode();
            $this->responseHeaders = $response->getHeaders();

        } catch (ClientException $e) {
            echo 1;
            exit;
            $this->response   = $e->getResponse()->getBody()->getContents();
            $this->statusCode = 400;
        } catch (ServerException $e) {
            echo 1;
            exit;
            echo($e->getResponse()->getBody()->getContents());
            exit;
        } catch (\Exception $e) {
            echo 1;
            exit;
            echo($e->getCode() . ": " . $e->getMessage());
            exit;
        }
    }
}