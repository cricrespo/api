<?php
namespace AppBundle\Services;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer;
class Helpers
{
    private function dataToJson($collection) {
        $encoders = array(new Serializer\Encoder\JsonEncoder());
        $normalizers = array(new Serializer\Normalizer\GetSetMethodNormalizer());
        $serializer = new Serializer\Serializer($normalizers, $encoders);
        $json = $serializer->serialize($collection, "json");
        return $json;
    }
    private function jsonToHttpRespose($json) {
        $httpResponse = new Response();
        $httpResponse->setContent($json);
        $httpResponse->headers->set("Content-type", "application/json");
        return $httpResponse;
    }
    public function collectionToHttpJsonResponse($collection) {
        return $this->jsonToHttpRespose($this->dataToJson($collection));
    }
}