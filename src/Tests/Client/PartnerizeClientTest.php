<?php

namespace Superbrave\PartnerizeBundle\Tests\Client;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Superbrave\PartnerizeBundle\Client\PartnerizeClient;
use Superbrave\PartnerizeBundle\Encoder\PartnerizeS2SEncoder;
use Superbrave\PartnerizeBundle\Exception\ClientException;
use Superbrave\PartnerizeBundle\Model\Sale;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class PartnerizeClientTest
 */
class PartnerizeClientTest extends TestCase
{
    /**
     * @var GuzzleClientInterface|MockObject
     */
    private $apiClient;

    /**
     * @var GuzzleClientInterface|MockObject
     */
    private $trackingClient;

    /**
     * @var string
     */
    private $campaignId;

    /**
     * @var SerializerInterface|MockObject
     */
    private $serializer;

    /**
     * @var PartnerizeClient
     */
    private $partnerizeClient;

    /**
     * Setup the necessary variables for testing
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->apiClient = $this->createMock(GuzzleClientInterface::class);
        $this->trackingClient = $this->createMock(GuzzleClientInterface::class);
        $this->campaignId = 'test';
        $this->serializer = $this->createMock(SerializerInterface::class);

        $this->partnerizeClient = new PartnerizeClient(
            $this->trackingClient,
            $this->apiClient,
            $this->campaignId,
            $this->serializer
        );
    }

    /**
     * Clear all variables after testing
     */
    public function tearDown(): void
    {
        $this->partnerizeClient = null;
        $this->apiClient = null;
        $this->trackingClient = null;
        $this->campaignId = null;
        $this->serializer = null;
    }

    /**
     * Test that a bad status code from the guzzle client will throw an exception on approving a conversion
     */
    public function testBadStatusCodeThrowsExceptionOnApprove(): void
    {
        $response = new Response(500);

        $this->apiClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', 'campaign/' . $this->campaignId . '/conversion', [
                'json' => [
                    'conversions' => [
                        [
                            'conversion_id' => 'testId',
                            'status' => 'approved',
                            'reject_reason' => '',
                        ]
                    ]
                ]
            ])
            ->willReturn($response);

        $client = new PartnerizeClient($this->trackingClient, $this->apiClient, $this->campaignId, $this->serializer);

        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('Received bad status code (should be 200)');

        $client->approveConversion('testId');
    }

    /**
     * Test that a good status code from the guzzle client will return nothing on approving a conversion
     */
    public function testGoodStatusCodeReturnsNothingOnApprove(): void
    {
        $response = new Response(200);

        $this->apiClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', 'campaign/' . $this->campaignId . '/conversion', [
                'json' => [
                    'conversions' => [
                        [
                            'conversion_id' => 'testId',
                            'status' => 'approved',
                            'reject_reason' => '',
                        ]
                    ]
                ]
            ])
            ->willReturn($response);

        $client = new PartnerizeClient($this->trackingClient, $this->apiClient, $this->campaignId, $this->serializer);
        $client->approveConversion('testId');
    }
    /**
     * Test that the create conversion call returns the api response body.
     *
     * @throws ClientException
     */
    public function testCreateConversionSuccess(): void
    {
        $sale = new Sale('clickReference', 'conversionReference');

        $this->serializer
            ->expects($this->once())
            ->method('serialize')
            ->with($sale, PartnerizeS2SEncoder::FORMAT)
            ->willReturn('ApiData');

        $response = new Response(200, [], 'AShinyNewConversionId');
        $this->trackingClient
            ->expects($this->once())
            ->method('request')
            ->with('GET', 'ApiData')
            ->willReturn($response);

        $conversionId = $this->partnerizeClient->createConversion($sale);
        $this->assertEquals('AShinyNewConversionId', $conversionId);
    }

    /**
     * Test that the client throws client exceptions and doesnt "leak" guzzle exceptions.
     */
    public function testCreateConversionError(): void
    {
        $sale = new Sale('clickReference', 'conversionReference');

        $this->serializer
            ->expects($this->once())
            ->method('serialize')
            ->with($sale, PartnerizeS2SEncoder::FORMAT)
            ->willReturn('ApiData');

        $this->trackingClient
            ->expects($this->once())
            ->method('request')
            ->with('GET', 'ApiData')
            ->willThrowException(new TransferException());

        $this->expectException(ClientException::class);
        $this->partnerizeClient->createConversion($sale);
    }
}
