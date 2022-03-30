<?php

namespace App\Services\HttpClient;

use GuzzleHttp\Client as Guzzle;

class Service
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $guzzle;

    /**
     * Service constructor.
     */
    public function __construct()
    {
        $this->instantiateGuzzle();
    }

    /**
     * @param $body
     * @return mixed
     */
    protected function bodyToArray($body)
    {
        return json_decode($this->bodyToJson($body), true);
    }

    /**
     * @param $body
     * @return mixed
     */
    protected function bodyToJson($body)
    {
        return $body;
    }

    /**
     * @return \Illuminate\Config\Repository|mixed
     */
    protected function getTimeout()
    {
        return config('app.remote_request.timeout', 20);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return mixed
     */
    public function request(string $method, string $uri = '', array $options = [])
    {
        return $this->guzzle->request($method, $uri, $options);
    }

    /**
     * @param $url
     * @return array
     */
    public function readJson($url)
    {
        $response = $this->get($url);

        $data = [
            'status_code' => $response->getStatusCode(),
            'success' => ($success = $response->getStatusCode() == 200),
            'data' => [],
        ];

        if ($success) {
            $data['data'] = $this->toCollection($this->bodyToArray((string) $response->getBody()));
        }

        return coollect($data);
    }

    /**
     * @param $url
     * @return mixed
     */
    public function getArray($url)
    {
        return $this->toJson($this->getRaw($url));
    }

    /**
     * @param $url
     * @return string
     */
    public function getRaw($url)
    {
        return (string) $this->get($url)->getBody();
    }

    /**
     * @param $url
     * @return mixed
     */
    public function get($url)
    {
        return $this->request('GET', $url, [
            'connect_timeout' => $this->getTimeout(),
            'read_timeout' => $this->getTimeout(),
            'verify' => false,
        ]);
    }

    /**
     *
     */
    protected function instantiateGuzzle()
    {
        $this->guzzle = new Guzzle();
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function sanitizeJson($data)
    {
        $data = str_replace("\n", '', $data);

        $data = str_replace('=\>', ' - ', $data);

        return $data;
    }

    /**
     * @param $body
     * @return \Illuminate\Support\Collection|null
     */
    private function toCollection($body)
    {
        return is_array($body) ? coollect($body) : null;
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function toJson($data)
    {
        return json_decode($this->sanitizeJson($data), true);
    }
}
