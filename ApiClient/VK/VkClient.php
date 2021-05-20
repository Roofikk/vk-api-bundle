<?php

declare(strict_types=1);

namespace roofikk\VkApiBundle\ApiClient\VK;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use VK\Client\VKApiClient;


class VkClient
{
    protected HttpClientInterface $client;
    protected string $accessToken;
    protected $vkClient;

    /**
     * RussianPostClient constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->accessToken = $token;
        $this->vkClient = new VKApiClient('5.130');
    }

    public function wall_post($owner_id, string $message)
    {
        $access_token = $this->accessToken;
        $params = [
            'owner_id' => $owner_id,
            'message' => $message,
            'friends_only' => '0',
            'from_group' => '1',
        ];

        $response = $this->vkClient->wall()->post($access_token, $params);

        return $response;
    }

    public function wallPostWithPict($group_id, $array_files)
    {
        $server = $this->getWallUploadServer();
        $response = $this->vkClient->getRequest()->upload($server['upload_url'], 'photo', $array_files[0]);

        $response = $this->vkClient->photos()->saveWallPhoto($this->accessToken, [
            'server' => $response['server'],
            'photo'  => $response['photo'],
            'hash'   => $response['hash'],
        ]);

        $params = [
            'owner_id' => $group_id > 0 ? -$group_id : $group_id,
            'message' => "Я фотка, я фотка",
            'friends_only' => '0',
            'from_group' => '1',
            'attachments' => 'photo'.$response[0]['owner_id'].'_'.$response[0]['id'],
        ];

        $this->vkClient->wall()->post($this->accessToken, $params);

        return $response;
    }

    public function getWallUploadServer()
    {
        $server = $this->vkClient->photos()->getWallUploadServer($this->accessToken);

        return $server;
    }
}

