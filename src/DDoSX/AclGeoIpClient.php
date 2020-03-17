<?php

namespace UKFast\SDK\DDoSX;

use UKFast\SDK\Client as BaseClient;
use UKFast\SDK\DDoSX\Entities\AclGeoIp;
use UKFast\SDK\SelfResponse;

class AclGeoIpClient extends BaseClient
{
    protected $basePath = 'ddosx/';

    const MAP = [];

    /**
     * Return a page of the ACL GeoIP rules for a domain
     * @param $domainName
     * @param int $page
     * @param int $perPage
     * @param array $filters
     * @return int|\UKFast\SDK\Page
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPage($domainName, $page = 1, $perPage = 15, $filters = [])
    {
        $page = $this->paginatedRequest(
            'v1/domains/' . $domainName . '/acls/geo-ips',
            $page,
            $perPage,
            $filters
        );

        $page->serializeWith(function ($item) {
            return $this->serializeData($item);
        });

        return $page;
    }

    /**
     * Create a new GeoIp rule for a domain
     * @param $domainName
     * @param $data
     * @return SelfResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function create($domainName, $data)
    {
        $response = $this->post('v1/domains/' . $domainName . '/acls/geo-ips', json_encode($this->friendlyToApi(
            $data,
            self::MAP
        )));

        $response = $this->decodeJson($response->getBody()->getContents());

        return (new SelfResponse($response))
            ->setClient($this)
            ->serializeWith(function ($response) {
                return $this->serializeData($response->data);
            });
    }

    /**
     * Get a GeoIp rule for a domain by it's ID
     * @param $domainName
     * @param $geoIpId
     * @return AclGeoIp
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getById($domainName, $geoIpId)
    {
        $response = $this->get('v1/domains/' . $domainName . '/acls/geo-ips/' .$geoIpId);

        $body = $this->decodeJson($response->getBody()->getContents());

        return $this->serializeData($body->data);
    }

    /**
     * Update a GeoIp for a domain
     * @param $domainName
     * @param $geoIpId
     * @param $data
     * @return SelfResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update($domainName, $geoIpId, $data)
    {
        $response = $this->patch(
            'v1/domains/' . $domainName . '/acls/geo-ips/' . $geoIpId,
            json_encode($this->friendlyToApi($data, self::MAP))
        );

        $response = $this->decodeJson($response->getBody()->getContents());

        return (new SelfResponse($response))
            ->setClient($this)
            ->serializeWith(function ($response) {
                return $this->serializeData($response->data);
            });
    }

    /**
     * Delete a GeoIp for a domain
     * @param $domainName
     * @param $geoIpId
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function destroy($domainName, $geoIpId)
    {
        $response = $this->delete('v1/domains/' . $domainName . '/acls/geo-ips/' . $geoIpId);

        return $response->getStatusCode() == 204;
    }

    public function serializeData($raw)
    {
        return new AclGeoIp($this->apiToFriendly($raw, self::MAP));
    }
}
