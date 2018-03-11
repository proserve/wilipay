<?php

namespace App\Http;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class AccountKit
{
    /**
     * @var \GuzzleHttp\Client HttpClient
     */
    protected $client;

    /**
     * @var string
     */
    protected $accessTokenUrl;

    /**
     * @var string
     */
    protected $meTokenUrl;

    /**
     * AccountKit constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
        $this->accessTokenUrl = 'https://graph.accountkit.com/v1.3/access_token';
        $this->meTokenUrl = 'https://graph.accountkit.com/v1.3/me?access_token=';
    }

    /**
     * Set Token Url
     *
     * @param string $code
     * @param string $appId
     * @param string $appSecret
     *
     * @return string
     */
    private function tokenUrl($code, $appId, $appSecret)
    {
        return $this->accessTokenUrl . '?grant_type=authorization_code&code=' . $code . '&access_token=AA|' . $appId . '|' . $appSecret;
    }

    /**
     * Get App Id
     *
     * @return string
     */
    private function getFacebookAppID()
    {
        return Config::get('accountKit.appId');
    }

    /**
     * Get App Secret
     *
     * @return string
     */
    private function getFacebookAppSecret()
    {
        return Config::get('accountKit.appSecret');
    }

    /**
     * Set Token endpoint
     *
     * @param string $code
     *
     * @return string
     */
    private function tokenExchangeEndPoint($code)
    {
        return $this->tokenUrl($code, $this->getFacebookAppID(), $this->getFacebookAppSecret());
    }

    /**
     * Make Request To AccountKit
     *
     * @param string $url
     *
     * @return mixed
     */
    private function getContentBody($url)
    {
        $data = $this->client->request('GET', $url);

        return json_decode($data->getBody());
    }

    /**
     * Get User Data
     *
     * @param string $code
     *
     * @return mixed
     */
    private function getData($code)
    {
        $url = $this->tokenExchangeEndPoint($code);

        return $this->getContentBody($url);
    }

    /**
     * Get Access token
     *
     * @param string $code
     *
     * @return string
     */
    private function getAccessToken($code)
    {
        return $this->getData($code)->access_token;
    }

    /**
     * Set User Token Endpoint
     *
     * @param string $code
     *
     * @return string
     */
    private function meEndPoint($code)
    {
        $accessToken = $this->getAccessToken($code);
        $appsecret_proof = hash_hmac('sha256', $accessToken, $this->getFacebookAppSecret());
        return $this->meTokenUrl . '' . $accessToken . '&appsecret_proof=' . $appsecret_proof;
    }

    /**
     * Get Returned Data
     *
     * @param string $code
     *
     * @return mixed
     */
    public function data($code)
    {
        return $this->getContentBody($this->meEndPoint($code));
    }
}
