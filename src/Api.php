<?php

namespace StudServise\Dreamkas;

use GuzzleHttp\Client;

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

    protected static $baseUri = [
        self::MODE_PRODUCTION => 'https://kabinet.dreamkas.ru/api/',
        self::MODE_MOCK => 'https://private-anon-2a1e26f7f7-kabinet.apiary-mock.com/api/',
        self::MODE_DEBUG => 'https://private-anon-2a1e26f7f7-kabinet.apiary-proxy.com/api/',
    ];

    /**
     * Api constructor.
     * @param string $accessToken
     * @param int $deviceId
     * @param int $mode
     */
    public function __construct(string $accessToken, int $deviceId, int $mode = self::MODE_PRODUCTION)
    {
        $this->accessToken = $accessToken;
        $this->deviceId = $deviceId;
        $this->mode = $mode;
        $this->createClient();
    }

    protected function createClient()
    {
        $baseUri = static::$baseUri[$this->mode] ?? null;
        if ($baseUri === null) {
            throw new \RuntimeException('Unknown Dreamkas Api mode');
        }

        $this->client = new Client([
            'base_uri' => $baseUri,
        ]);


    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function request(string $method, string $uri = '', array $options = [])
    {
        if (isset($options['headers']) === false) {
            $options['headers'] = [];
        }
        $options['headers']['Authorization'] = 'Bearer ' . $this->accessToken;
        $options['headers']['Accept'] = 'application/json';
        return $this->client->request($method, $uri, $options);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return mixed
     */
    public function json(string $method, string $uri = '', array $options = [])
    {

        $response = $this->request($method, $uri, $options);
        return \GuzzleHttp\json_decode($response->getBody(), true);
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
        return $this->json('POST', 'receipts', [
            'json' => $data,
        ]);
    }
}