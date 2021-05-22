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
            'owner_id' => $owner_id > 0 ? -$owner_id : $owner_id,
            'message' => $message,
            'friends_only' => '0',
            'from_group' => '1',
        ];

        $response = $this->vkClient->wall()->post($access_token, $params);
        $post_id = 0;
        if (is_numeric($response))
            $post_id = $response;
        else
            $post_id = $response['post_id'];

        $likeResponse = $this->vkClient->likes()->add($access_token, [
            'type' => 'post',
            'owner_id' => $owner_id > 0 ? -$owner_id : $owner_id,
            'item_id' => $post_id,
        ]);

        return $post_id;
    }

    public function wallPostWithPict($group_id, $array_files, $message = "")
    {
        $server = $this->vkClient->photos()->getWallUploadServer($this->accessToken);
        $attachment = "";

        if (count($array_files) > 0) {
            for ($i = 0; $i < count($array_files); $i++) {
                $response[$i] = $this->vkClient->getRequest()->upload(
                    $server['upload_url'],
                    'photo',
                    $array_files[$i]
                );
                $response[$i] = $this->vkClient->photos()->saveWallPhoto($this->accessToken, [
                    'server' => $response[$i]['server'],
                    'photo' => $response[$i]['photo'],
                    'hash' => $response[$i]['hash'],
                ]);

                $attachment = $attachment . 'photo' . $response[$i][0]['owner_id'] . '_' . $response[$i][0]['id'] . ',';
            }
        } else {
            return "not enough files";
        }

        $params = [
            'owner_id' => $group_id > 0 ? -$group_id : $group_id,
            'message' => $message,
            'friends_only' => '0',
            'from_group' => '1',
            'attachments' => $attachment,
        ];

        $response = $this->vkClient->wall()->post($this->accessToken, $params);

        if (is_numeric($response))
            $post_id = $response;
        else
            $post_id = $response['post_id'];

        $likeResponse = $this->vkClient->likes()->add($this->accessToken, [
            'type' => 'post',
            'owner_id' => $group_id > 0 ? -$group_id : $group_id,
            'item_id' => (int)$post_id,
        ]);

        return $post_id;
    }

    public function wallPostWithVideo($group_id, $videoName, $path, $description = "", $message = "")
    {
        $videoInfo = $this->vkClient->video()->save($this->accessToken, [
            'name' => $videoName,
            #'wallpost' => 1,
            'description' => $description,
            'group_id' => $group_id,
        ]);

        $response = $this->vkClient->getRequest()->upload($videoInfo['upload_url'], 'video_file', $path);

        $params = [
            'owner_id' => $group_id > 0 ? -$group_id : $group_id,
            'message' => $message,
            'friends_only' => '0',
            'from_group' => '1',
            'attachments' => 'video'.$response['owner_id'].'_'.$response['video_id'].',',
        ];

        $response = $this->vkClient->wall()->post($this->accessToken, $params);

        if (is_numeric($response))
            $post_id = $response;
        else
            $post_id = $response['post_id'];

        $likeResponse = $this->vkClient->likes()->add($this->accessToken, [
            'type' => 'post',
            'owner_id' => $group_id > 0 ? -$group_id : $group_id,
            'item_id' => (int)$post_id,
        ]);

        return $post_id;
    }

    public function addPhotoToStories($group_id, $photo)
    {
        $storyInfo = $this->vkClient->stories()->getPhotoUploadServer($this->accessToken, [
            'add_to_news' => 1,
        ]);

        $address = $this->vkClient->getRequest()->upload($storyInfo['upload_url'], 'file', $photo);
        $response = $this->vkClient->getRequest()->post('stories.save', $this->accessToken, [
            'upload_results' => $address['upload_result'],
        ]);

        return $response['items'][0]['id'];
    }

    public function addVideoToStories($group_id, $video)
    {
        var_dump(pathinfo($video));
        $storyInfo = $this->vkClient->stories()->getVideoUploadServer($this->accessToken, [
            'add_to_news' => 1,
            'group_id' => $group_id,
        ]);
        var_dump($storyInfo['upload_url']);

        $address = $this->vkClient->getRequest()->upload($storyInfo['upload_url'], 'video_file', $video);
        $response = $this->vkClient->getRequest()->post('stories.save', $this->accessToken, [
            'upload_results' => $address['upload_result'],
        ]);
        var_dump($response);

        return $response['items'][0]['id'];
    }

    public function getStoryStats($owner_id, $story_id)
    {
        $response = $this->vkClient->stories()->getStats($this->accessToken, [
            'owner_id' => $owner_id > 0 ? -$owner_id : $owner_id,
            'story_id' => $story_id,
        ]);

        return $response;
    }

    public function getStoryReplies($owner_id, $story_id)
    {
        $response = $this->vkClient->stories()->getReplies($this->accessToken, [
            'owner_id' => $owner_id > 0 ? -$owner_id : $owner_id,
            'story_id' => $story_id,
            'extended' => 0,
        ]);

        return $response['count'];
    }
}

