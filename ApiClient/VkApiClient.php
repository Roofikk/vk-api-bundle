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

use roofikk\VkApiBundle\Dto\AddressDto;


class VkApiClient
{
    protected HttpClientInterface $client;
    protected string $accessToken;

    /**
     * RussianPostClient constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->client = HttpClient::create([
//            'base_uri' => 'https://address.pochta.ru/validate/api/v7_1'
        ]);

        $this->accessToken = $token;
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

