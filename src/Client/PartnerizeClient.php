<?php

namespace Superbrave\PartnerizeBundle\Client;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Superbrave\PartnerizeBundle\Encoder\PartnerizeS2SEncoder;
use Superbrave\PartnerizeBundle\Exception\ClientException;
use Superbrave\PartnerizeBundle\Model\Sale;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class PartnerizeClient
 */
class PartnerizeClient
{
    public const
        STATUS_APPROVED = 'approved',
        STATUS_REJECTED = 'rejected';

    /**
     * @var GuzzleClientInterface
     */
    private $trackingClient;

    /**
     * @var GuzzleClientInterface
     */
    private $apiClient;

    /**
     * @var string
     */
    private $campaignId;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * PartnerizeClient constructor.
     *
     * @param GuzzleClientInterface $trackingClient
     * @param GuzzleClientInterface $apiClient
     * @param string                $campaignId
     * @param SerializerInterface   $serializer
     */
    public function __construct(
        GuzzleClientInterface $trackingClient,
        GuzzleClientInterface $apiClient,
        string $campaignId,
        SerializerInterface $serializer
    ) {
        $this->trackingClient = $trackingClient;
        $this->apiClient = $apiClient;
        $this->campaignId = $campaignId;
        $this->serializer = $serializer;
    }

    /**
     * Create a new conversion if a new order was created
     *
     * @param Sale $sale
     *
     * @return string An empty string indicates failure to store the conversion.
     *
     * @throws ClientException
     */
    public function createConversion(Sale $sale): string
    {
        $sale->setTrackingMode(Sale::TRACKING_MODE_API);
        $sale->setCampaign($this->campaignId);

        $s2sUri = $this->serializer->serialize($sale, PartnerizeS2SEncoder::FORMAT);
        $requestUri = $this->trackingClient->getConfig('base_uri') . $s2sUri;
        try {
            $response = $this->trackingClient->request('GET', $requestUri);
        } catch (GuzzleException $exception) {
            throw new ClientException($exception->getMessage(), 0, $exception);
        }

        // An empty response means failure to create a conversion.
        return $response->getBody();
    }

    /**
     * Approve a conversion.
     *
     * @param string $conversionId
     *
     * @throws ClientException
     */
    public function approveConversion(string $conversionId): void
    {
        $this->setConversionStatus($conversionId, self::STATUS_APPROVED);
    }

    /**
     * Reject a conversion.
     *
     * @param string $conversionId
     * @param string $reason
     *
     * @throws ClientException
     */
    public function rejectConversion(string $conversionId, string $reason): void
    {
        $this->setConversionStatus($conversionId, self::STATUS_REJECTED);
    }

    /**
     * Sends the status of the conversion to partnerize
     *
     * @param string $conversionId
     * @param string $status
     * @param string $reason
     *
     * @throws ClientException
     */
    private function setConversionStatus(string $conversionId, string $status, string $reason = ''): void
    {
        try {
            $response = $this->apiClient->request(
                'POST',
                sprintf('campaign/%s/conversion', $this->campaignId),
                [
                    'json' => [
                        'conversions' => [
                            [
                                'conversion_id' => $conversionId,
                                'status' => $status,
                                'reject_reason' => $reason,
                            ],
                        ],
                    ],
                ]
            );
        } catch (GuzzleException $exception) {
            throw new ClientException($exception->getMessage(), 0, $exception);
        }

        if ($response->getStatusCode() !== 200) {
            throw new ClientException('Received bad status code (should be 200)');
        }
    }
}
