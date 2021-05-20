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

    public function wallPostWithPict($group_id, $array_files, $message)
    {
        $server = $this->vkClient->photos()->getWallUploadServer($this->accessToken);
        for ($i = 0; $i < count($array_files); $i++)
        {
            $response = $this->vkClient->getRequest()->upload($server['upload_url'], 'photo', $array_files[$i]);
            var_dump($response);
        }

        var_dump("HER");
        $result = $this->vkClient->photos()->saveWallPhoto($this->accessToken, [
            'server' => $response['server'],
            'photo'  => $response['photo'],
            'hash'   => $response['hash'],
        ]);

        var_dump($result);
        $attachments = "";

        for ($i = 0; $i < count($result); $i++)
        {
            $attachments = $attachments.'photo'.$result[$i]['owner_id'].'_'.$result[$i]['id'].',';
        }

        var_dump($attachments);

        $params = [
            'owner_id' => $group_id > 0 ? -$group_id : $group_id,
            'message' => $message,
            'friends_only' => '0',
            'from_group' => '1',
            'attachments' => $attachments,
        ];

        $response = $this->vkClient->wall()->post($this->accessToken, $params);

        return $response;
    }
}

