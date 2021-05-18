<?php

declare(strict_types=1);

namespace roofikk\VkApiBundle\ApiClient;

use DateTime;
use Exception;
use Illuminate\Support\Arr;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use VK\Client\VKApiClient as OtherVkApiClient;

use roofikk\VkApiBundle\Dto\AddressDto;


class VkApiClient
{
    protected HttpClientInterface $client;
    protected string $accessToken;

    protected $mime;
    protected $file_name;
    protected $content;

    /**
     * RussianPostClient constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->accessToken = $token;
    }

    public function wall_post($owner_id, string $message)
    {
        $vk = new OtherVkApiClient('5.130');
        $access_token = $this->accessToken;
        $params = [
            'owner_id' => $owner_id,
            'message' => $message,
            'friends_only' => '0',
            'from_group' => '1',
        ];

        $response = $vk->wall()->post($access_token, $params);

        return $response;
    }

    public function wallPostWithPict($group_id, $array_files)
    {
        $server = $this->getWallUploadServer($group_id);
        var_dump($server['upload_url']);
        $client = HttpClient::create();
        for($i = 0; $i < count($array_files); ++$i)
        {
            $content = $this->initFile($array_files[$i]);
            $response = $client->request('POST', $server['upload_url'], [
                'json' => [
                    'photo' => $content,
                ]
            ]);
        }

        return $response;
    }

    public function getWallUploadServer($group_id)
    {
        $vk = new OtherVkApiClient('5.130');
        $access_token = $this->accessToken;

        if ($group_id > 0)
            $group_id = $group_id * -1;

        $params = [
            'group_id' => $group_id,
        ];

        $server = $vk->photos()->getWallUploadServer($access_token ,$params);

        return $server;
    }

//    public function testFile($name)
//    {
//        $this->initFile($name);
//
//        return True;
//    }

    protected function initFile($name, $mime=null, $content=null)
    {
// Проверяем, если $content=null, значит в переменной $name - путь к файлу
        if(is_null($content))
        {
// Получаем информацию по файлу (путь, имя и расширение файла)
            $info = pathinfo($name);
// проверяем содержится ли в строке имя файла и можно ли прочитать файл
            if(!empty($info['basename']) && is_readable($name))
            {
                $this->file_name = $info['basename'];
// Определяем MIME тип файла
                $this->mime = mime_content_type($name);
// Загружаем файл
                $content = file_get_contents($name);
// Проверяем успешно ли был загружен файл
                if($content!==false)
                    $this->content = $content;
                else
                    throw new Exception('Don`t get content - "'.$name.'"');
            }
            else
                throw new Exception('Error param');
        }
        else
        {
// сохраняем имя файла
            $this->file_name = $name;
// Если не был передан тип MIME пытаемся сами его определить
            if(is_null($mime)) $mime = mime_content_type($name);
// Сохраняем тип MIME файла
            $this->mime = $mime;
// Сохраняем в свойстве класса содержимое файла
            $this->content = $content;
        };

        return $content;
    }

    public function validate(string $address)
    {

        $response = $this->client->request('POST', 'https://address.pochta.ru/validate/api/v7_1/', [
            'headers' => [
                'Content-Type' => 'application/json',
                'AuthCode' => $this->accessToken,
            ],
            'json' => [
                "addr" => [ ["val" => $address] ],
                "version" => "v7_2",
                "reqId" => "12204cb4-37fb-4059-91e6-c6e17e946d7f"
            ]
        ]);

//        if ($response->getStatusCode() !== Response::HTTP_OK) {
//            $this->logger->critical('Cannot fetch accounts', [
//                'statusCode' => $response->getStatusCode(),
//                'response' => $response->getContent()
//            ]);
//
//            return [];
//        }

        $content = $response->toArray();
        var_dump($content);

        $result = new AddressDto();

        $result->setInaddr($content['addr']['inaddr']);
        $result->setOutaddr($content['addr']['outaddr']);

        if(!strcasecmp($content['state'], '302')){
            $result->setMistake($content['addr']['missing']);
        }

        if(!strcasecmp(strval($content['addr']['delivery']), '0')){
            $str = 'Пригодно для доставки';
        } elseif (!strcasecmp(strval($content['addr']['delivery']), '1')) {
            $str = 'Требует уточнения';
        } else {
            $str = 'Плохой адрес';
        }
        $result->setDelivery($str);

        foreach($content['addr']['element'] as $piece){

            if(!strcasecmp($piece['content'], 'C')){
                $result->setCountry($piece['val']);
            }

            if(!strcasecmp($piece['content'], 'R')){
                $result->setDistrictType($piece['stname']);
                $result->setDistrictName($piece['val']);
            }

            if(!strcasecmp($piece['content'], 'A')) {
                $result->setAreaType($piece['stname']);
                $result->setAreaName($piece['val']);
            }

            if(!strcasecmp($piece['content'], 'P')) {
                $result->setLocalityType($piece['stname']);
                $result->setLocalityName($piece['val']);
            }

            if(!strcasecmp($piece['content'], 'S')) {
                $result->setStreetType($piece['stname']);
                $result->setStreetName($piece['val']);
            }

            if(!strcasecmp($piece['content'], 'N')) {
                $result->setHouseType($piece['stname']);
                $result->setHouseName($piece['val']);
            }

            if(!strcasecmp($piece['content'], 'L')) {
                $result->setLetter($piece['val']);
            }

            if(!strcasecmp($piece['content'], 'D')) {
                $result->setDelimited($piece['val']);
            }

            if(!strcasecmp($piece['content'], 'E')) {
                $result->setExternal($piece['val']);
            }

            if(!strcasecmp($piece['content'], 'B')) {
                $result->setBuilding($piece['val']);
            }

            if(!strcasecmp($piece['content'], 'F')) {
                $result->setFlat($piece['val']);
            }

            if(!strcasecmp($piece['content'], 'BOX')) {
                $result->setBoxType($piece['stname']);
                $result->setBoxNumber($piece['val']);
            }

            if(!strcasecmp($piece['content'], 'M')) {
                $result->setMilitaryType($piece['stname']);
                $result->setMilitaryNumber($piece['val']);
            }

        }
        return $result;
    }
}

