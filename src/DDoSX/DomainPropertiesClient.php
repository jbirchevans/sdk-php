<?php

namespace UKFast\SDK\DDoSX;

use UKFast\SDK\Client as BaseClient;
use UKFast\SDK\DDoSX\Entities\DomainProperty;
use UKFast\SDK\SelfResponse;

class DomainPropertiesClient extends BaseClient
{
    protected $basePath = 'ddosx/';

    const MAP = [];

    /**
     * @param $domainName
     * @param int $page
     * @param int $perPage
     * @param array $filter
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPage($domainName, $page = 1, $perPage = 15, $filter = [])
    {
        $response = $this->paginatedRequest('v1/domains/' . $domainName . '/properties', $page, $perPage);

        $response->serializeWith(function ($item) {
            return $this->serializeData($item);
        });
    }

    /**
     * Get a property by it's ID
     * @param $domainId
     * @param $propertyId
     * @return DomainProperty
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getById($domainId, $propertyId)
    {
        $response = $this->get('v1/domains/' . $domainId . '/properties/' . $propertyId);

        $body = $this->decodeJson($response->getBody()->getContents());

        return $this->serializeData($body->data);
    }

    /**
     * Update a domain's property
     * @param $domainId
     * @param $propertyId
     * @param $data
     * @return SelfResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update($domainId, $propertyId, $data)
    {
        $payload = [
            'value' => $data->value
        ];

        $response = $this->patch('v1/domains/' . $domainId . '/properties/' . $propertyId, $payload);

        $response = $this->decodeJson($response->getBody()->getContents());

        return (new SelfResponse($response))
            ->setClient($this)
            ->serializeWith(function ($response) {
                return $this->serializeData($response->data);
            });
    }

    /**
     * @return DomainProperty
     */
    public function serializeData($raw)
    {
        return new DomainProperty($this->apiToFriendly($raw, self::MAP));
    }
}
