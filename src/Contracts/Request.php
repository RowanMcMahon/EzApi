<?php

namespace Contracts;

use GuzzleHttp\Client;

class Request
{
    protected $scheme;
    protected $host;
    protected $endpoint;
    protected $method;
    protected $data;
    protected $headers;
    protected $disableSSL = false;

    public function __construct($host, $endpoint, $method, $data, $headers = null, $scheme = null)
    {
        $this->host = trim($host, '/');
        $this->endpoint = ltrim($endpoint, '/');
        $this->method = $method;
        $this->data = $data;
        $this->headers = $headers;
        $this->scheme = $scheme ?? 'https';
    }

    public function getUrl()
    {
        $url = $this->scheme . '://' . $this->host . '/' . $this->endpoint;
        if ($this->method === 'GET')
        {
            $url .= '?' . http_build_query($this->data);
        }

        return $url;
    }

    public function disableSSL()
    {
        $this->disableSSL = true;
    }

    public function send()
    {
        $client = new Client();

        $body = [
            'verify' => !$this->disableSSL
        ];

        if ($this->method !== 'GET')
        {
            $body['form_params'] = $this->data;
        }

        if (isset($this->headers) && !empty($this->headers))
        {
            $body['headers'] = $this->headers;
        }

        $res = $client->request(
            $this->method,
            $this->getUrl(),
            $body
        );

        try
        {
            $contents = $res->getBody()->getContents();
            return json_decode($contents, true);
        }
        catch (\Throwable $th)
        {
            throw $th;
        }
    }
}
