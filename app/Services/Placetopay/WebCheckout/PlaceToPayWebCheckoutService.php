<?php


namespace App\Services\Placetopay\WebCheckout;

use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;

class PlaceToPayWebCheckoutService
{
    /** @var string */
    protected $endpoint;

    const TIMEOUT = 15;

    const CONNECT_TIMEOUT = 5;

    /**
     * PoBoxPurchaseService constructor.
     */
    public function __construct()
    {
        $this->endpoint = env('TEST_PLACE_TO_PAY_URL','https://dev.placetopay.com/redirection/');
    }

    /**
     * @param $data
     * @return mixed
     * @throws GuzzleException
     */
    public function createRequest($data): object
    {
        $client = $this->createClient(null);

        $nonce = $this->createNonce();

        $nonce_base64 = $this->createNonceBase64($nonce);

        $seed = $this->createSeed();

        $secretKey = env('TEST_PLACE_TO_PAY_SECRET_KEY', '024h1IlD');

        $tranKey = $this->createTranKey($nonce, $seed, $secretKey);

        $result_data = array_merge($data, ["auth" => [
            "login" => env('TEST_PLACE_TO_PAY_LOGIN', '6dd490faf9cb87a9862245da41170ff2'),
            "tranKey" => "{$tranKey}",
            "nonce" => "{$nonce_base64}",
            "seed" => "{$seed}"
        ]]);

        /** @var Response $response */
        $response = $client->post('api/session/', [
            'json' => $result_data,
        ]);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * @param $id
     * @return mixed
     * @throws GuzzleException
     */
    public function getRequestInformation($id): object
    {
        $client = $this->createClient(null);

        $nonce = $this->createNonce();

        $nonce_base64 = $this->createNonceBase64($nonce);

        $seed = $this->createSeed();

        $secretKey = env('TEST_PLACE_TO_PAY_SECRET_KEY', '024h1IlD');

        $tranKey = $this->createTranKey($nonce, $seed, $secretKey);

        $data = [
            "auth" => [
                "login" => env('TEST_PLACE_TO_PAY_LOGIN', '6dd490faf9cb87a9862245da41170ff2'),
                "tranKey" =>  "{$tranKey}",
                "nonce" =>  "{$nonce_base64}",
                "seed" => "{$seed}"
            ]
        ];

        /** @var Response $response */
        $response = $client->post("api/session/{$id}", [
            'json' => $data,
        ]);

        return json_decode($response->getBody()->getContents());
    }

    /**
     * @param $authorization
     * @return GuzzleHttpClient
     */
    private function createClient($authorization): GuzzleHttpClient
    {
        return new GuzzleHttpClient([
            'base_uri' => $this->endpoint,
            'headers'  => ['Content-Type' => 'application/json'],
            'verify'   => false,
            'timeout' => self::TIMEOUT,
            'connect_timeout' => self::CONNECT_TIMEOUT
        ]);
    }

    /**
     * @return string
     */
    private function createNonce(): string
    {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }

    /**
     * @param $nonce
     * @return string
     */
    private function createNonceBase64($nonce): string
    {
        return base64_encode($nonce);
    }

    /**
     * @return string
     */
    private function createSeed(): string
    {
        return Carbon::now()->format('c');
    }

    /**
     * @param $nonce
     * @param $seed
     * @param $secretKey
     * @return string
     */
    private function createTranKey($nonce, $seed, $secretKey): string
    {
        return base64_encode(hash('sha1', $nonce. $seed . $secretKey, true));
    }
}
