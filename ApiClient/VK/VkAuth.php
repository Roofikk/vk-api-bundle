<?php

declare(strict_types=1);

namespace roofikk\VkApiBundle\ApiClient\VK;

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
use VK\OAuth\VKOAuthResponseType;

class VkAuth
{
    protected $clientId;
    protected string $redirect_uri;
    protected string $client_secret;
    protected $oauth;

    #protected HttpClientInterface $client;

    public function __construct($id, string $client_secret = "")
    {
        $this->clientId = $id;
        $this->client_secret = $client_secret;
        $this->oauth = new VKOAuth('5.130');
    }

    public function authorize()
    {
        $client_id = $this->clientId;
        $redirect_uri = 'https://oauth.vk.com/blank.html';
        $display = VKOAuthDisplay::PAGE;
        $scope = array(VKOAuthUserScope::WALL, VKOAuthUserScope::GROUPS, VKOAuthUserScope::PHOTOS, VKOAuthUserScope::VIDEO);
        $state = 'secret_state_code';

        $browser_url = $this->oauth->getAuthorizeUrl(VKOAuthResponseType::CODE, $client_id, $redirect_uri, $display, $scope, $state);

        return $browser_url;
    }

    public function get_access_token(string $get_code)
    {
        $client_id = $this->clientId;
        $client_secret = $this->client_secret;
        $redirect_uri = 'https://oauth.vk.com/blank.html';
        $code = $get_code;

        $response = $this->oauth->getAccessToken($client_id, $client_secret, $redirect_uri, $code);
        $access_token = $response['access_token'];

        return $access_token;
    }
}