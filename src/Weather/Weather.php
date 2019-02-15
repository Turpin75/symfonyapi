<?php

namespace App\Weather;

use GuzzleHttp\Client;
use JMS\Serializer\SerializerInterface;

class Weather
{
    private $weatherClient;
    private $serializer;
    private $apiKey;

    public function __construct(Client $weatherClient, SerializerInterface $serializer, $apiKey)
    {
        $this->weatherClient = $weatherClient;
        $this->serializer = $serializer;
        $this->apiKey = $apiKey;
    }

    public function getCurrent()
    {
        $uri = '/data/2.5/weather?q=Paris&APPID='.$this->apiKey;
        
        try{

            $response = $this->weatherClient->get($uri);
        }
        catch(\Exceptionc $e)
        {
            // Penser à loger l'erreur
            
            return ['error' => 'Les informations ne sont pas disponibles pour le moment.'];
        }

        $data = $this->serializer->deserialize($response->getBody()->getContents(), 'array', 'json');

        return [
            'city' => $data['name'],
            'description' => $data['weather'][0]['main']
        ];
    }
}