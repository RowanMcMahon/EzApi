<?php

namespace EzApi\Contracts;

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

    protected $validSslOptions = [
        null,
        'http',
        'https',
    ];

    public function __construct($method, $host, $endpoint = '', $data = [], $headers = null, $scheme = null)
    {
        if (!in_array($scheme, $this->validSslOptions))
        {
            throw new \InvalidArgumentException('Invalid SSL option, must be either "https or "http"');
        }

        $this->method = $method;
        $this->host = trim($host, '/');
        $this->endpoint = trim($endpoint, '/');
        $this->data = $data;
        $this->headers = $headers;
        $this->scheme = $scheme ?? 'https';
    }

    public function getUrl()
    {
        $url = $this->scheme . '://' . $this->host . ($this->endpoint !== '' ? '/' . $this->endpoint : $this->endpoint);
        if ($this->method === 'GET' && !empty($this->data))
        {
            $url .= '?' . http_build_query($this->data);
        }

        return $url;
    }

    public function disableSSL()
    {
        $this->disableSSL = true;
    }

    function isJsonRequest()
    {
        foreach ($this->headers as $header => $value)
        {
            if (strtolower($header) == 'content-type' && $value == 'application/json')
            {
                return true;
            }
        }

        return false;
    }

    public function send()
    {
        $client = new Client();

        $body = [
            'verify' => !$this->disableSSL
        ];

        if (isset($this->headers) && !empty($this->headers))
        {
            $body['headers'] = $this->headers;
        }

        if ($this->method !== 'GET')
        {
            if ($this->isJsonRequest())
            {
                $body['json'] = $this->data;
            }
            else
            {
                $body['form_params'] = $this->data;
            }
        }

        $res = $client->request(
            $this->method,
            $this->getUrl(),
            $body
        );

        try
        {
            $contents = $res->getBody()->getContents();
            return json_decode($contents, true) ?? $contents;
        }
        catch (\Throwable $th)
        {
            throw $th;
        }
    }
}
