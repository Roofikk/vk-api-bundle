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

class VkApiAuth
{
    protected $clientId;
    protected string $redirect_uri;
    protected string $client_secret;
    public function __construct($id, string $redirect_uri, $client_secret)
    {
        $this->clientId = $id;
        $this->redirect_uri = $redirect_uri;
        $this->client_secret = $client_secret;
    }

    public function get_token()
    {
        $this->autorize();
    }

    protected function autorize()
    {

    }

    protected function get_access_token()
    {

    }
}