<?php


namespace roofikk\VkApiBundle\ApiClient;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\Scopes\VKOAuthGroupScope;
use VK\OAuth\VKOAuthResponseType;

class VkApiAuth
{
    protected $clientId;
    protected string $redirect_uri;
    protected string $client_secret;

    protected HttpClientInterface $client;
    public function __construct($id, string $redirect_uri, string $client_secret = "")
    {
        $this->clientId = $id;
        $this->redirect_uri = $redirect_uri;
        $this->client_secret = $client_secret;

        $this->client = HttpClient::create([]);
    }

    public function get_token()
    {
        return $this->authorize();
    }

    protected function authorize()
    {
        $oauth = new VKOAuth('5.130');
        $client_id = $this->clientId;
        $redirect_uri = $this->redirect_uri;
        $display = VKOAuthDisplay::PAGE;
        $scope = array(VKOAuthUserScope::WALL, VKOAuthUserScope::GROUPS);
        $state = 'secret_state_code';

        $browser_url = $oauth->getAuthorizeUrl(VKOAuthResponseType::CODE, $client_id, $redirect_uri, $display, $scope, $state);

//        $response = $this->client->request('GET', $browser_url, [
//            'headers' => [
//                'content-type' => 'text/html',
//                'authority' => 'oauth.vk.com',
//            ],
//        ]);

        var_dump($browser_url);
    }

    protected function get_access_token(string $get_code)
    {
        $oauth = new VKOAuth();
        $client_id = $this->clientId;
        $client_secret = $this->client_secret;
        $redirect_uri = $this->redirect_uri;
        $code = $get_code;

        $response = $oauth->getAccessToken($client_id, $client_secret, $redirect_uri, $code);
        $access_token = $response['access_token'];

        return $access_token;
    }
}