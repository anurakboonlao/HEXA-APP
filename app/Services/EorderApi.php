<?php

namespace App\Services;

use GuzzleHttp\Client;

class EorderApi
{
    protected $baseUrl;
    protected $http;
    protected $headers;

    public function __construct(Client $client)
    {
        $this->url = env('EORDER_API_URL', 'http://e-order.netforce.co.th/testeorder/eorder/api.php');
        $this->http = $client;
        $this->headers = [
            'cache-control' => 'no-cache',
            'content-type' => 'application/json;charset=utf-8',
        ];
    }

    /**
     * 
     * 
     * 
     */
    public function callApi($params)
    {
        $request = $this->http->request('GET', $this->url, [
            'query' => $params,
            'connect_timeout' => 30
        ]);

        if ($request->getStatusCode() == 500 || $request->getStatusCode() == 404) {
            return [
                'status' => false,
                'message' => $request->getBody()
            ];    
        }

        return [
            'status' => true,
            'message' => 'success',
            'data' => $request->getBody()->getContents()
        ];
    }
}