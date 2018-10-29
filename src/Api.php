<?php

namespace DevGroup\Dreamkas;


use Guzzle\Http\Client;

/**
 * Class Api
 */
class Api
{
    const MODE_PRODUCTION = 0;
    const MODE_MOCK = 1;
    const MODE_DEBUG = 2;

    public $accessToken = '';
    public $deviceId = 0;
    public $mode = self::MODE_PRODUCTION;

    /** @var Client */
    protected $client;


    protected static $baseUri = array(
        self::MODE_PRODUCTION => 'https://kabinet.dreamkas.ru/api/',
        self::MODE_MOCK => 'http://private-anon-7a5585a78f-kabinet.apiary-mock.com/api/',
        self::MODE_DEBUG => 'https://private-anon-2a1e26f7f7-kabinet.apiary-proxy.com/api/',
    );

    /**
     * Api constructor.
     * @param string $accessToken
     * @param int $deviceId
     * @param int $mode
     */
    public function __construct($accessToken, $deviceId, $mode = self::MODE_PRODUCTION)
    {
        $this->accessToken = $accessToken;
        $this->deviceId = $deviceId;
        $this->mode = $mode;
        $this->createClient();
    }

    protected function createClient()
    {
        $baseUri = isset(static::$baseUri[$this->mode]) ? static::$baseUri[$this->mode] : null;
        if ($baseUri === null) {
            throw new \RuntimeException('Unknown Dreamkas Api mode');
        }

        $this->client = new Client($baseUri);


    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $headers
     * @param null $json
     * @return \Guzzle\Http\Message\Response
     */
    public function request($method, $uri = '', $headers = array(), $json = null)
    {
        $headers['Authorization'] = 'Bearer ' . $this->accessToken;
        $headers['Accept'] = 'application/json';

        return $this->client->createRequest($method, $uri, $headers, $json)->send();
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $json
     * @return mixed
     */
    public function json($method, $uri = '', $json = null)
    {
        $response = $this->request($method, $uri, array(), $json === null ? null : json_encode($json));
        return json_decode($response->getBody(), true);
    }

    /**
     * @param Receipt $receipt
     * @return mixed
     * @throws exceptions\ValidationException
     */
    public function postReceipt(Receipt $receipt)
    {
        $receipt->validate();
        $data = $receipt->toArray();
        $data['deviceId'] = $this->deviceId;
        return $this->json('POST', 'receipts', array(
            'json' => $data,
        ));
    }
}