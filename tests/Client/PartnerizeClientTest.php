<?php

namespace Superbrave\PartnerizeBundle\Tests\Client;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Exception\InvalidArgumentException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Superbrave\PartnerizeBundle\Client\PartnerizeClient;
use Superbrave\PartnerizeBundle\Encoder\PartnerizeS2SEncoder;
use Superbrave\PartnerizeBundle\Exception\ClientException;
use Superbrave\PartnerizeBundle\Model\Job;
use Superbrave\PartnerizeBundle\Model\Response as PartnerizeResponse;
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
     * Test happy path of creating a conversion
     */
    public function testCreateConversion(): void
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
     * Test that creating a conversion returns an empty response, meaning it did not create a conversion
     */
    public function testCreateConversionReturnsEmptyResponse(): void
    {
        $sale = new Sale('clickReference', 'conversionReference');

        $this->serializer
            ->expects($this->once())
            ->method('serialize')
            ->with($sale, PartnerizeS2SEncoder::FORMAT)
            ->willReturn('ApiData');

        $response = new Response(200, [], '');
        $this->trackingClient
            ->expects($this->once())
            ->method('request')
            ->with('GET', 'ApiData')
            ->willReturn($response);

        $conversionId = $this->partnerizeClient->createConversion($sale);
        $this->assertEmpty($conversionId);
    }

    /**
     * Test that creating a conversion throws a client exception when it catches a guzzle exception
     */
    public function testCreateConversionThrowsClientException(): void
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

    /**
     * Test happy path of approving a conversion
     */
    public function testApproveConversion(): void
    {
        $response = new Response(200);
        $expectedJob = new Job();
        $partnerizeResponse = new PartnerizeResponse();
        $partnerizeResponse->setJob($expectedJob);

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

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->willReturn($partnerizeResponse);

        $job = $this->partnerizeClient->approveConversion('testId');

        $this->assertEquals($expectedJob, $job);
    }

    /**
     * Test that approving a conversion throws a client exception when it catches a guzzle exception
     */
    public function testApproveConversionThrowsClientException(): void
    {
        $this->apiClient
            ->expects($this->once())
            ->method('request')
            ->willThrowException(new TransferException());

        $this->expectException(ClientException::class);
        $this->partnerizeClient->approveConversion('testId');
    }

    /**
     * Test that approving a conversion throws a client exception when it receives a response with a bad status code
     */
    public function testApproveConversionThrowsClientExceptionOnBadStatusCode(): void
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

        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('Received bad status code (should be 200)');
        $this->partnerizeClient->approveConversion('testId');
    }

    public function testJobReturnedOnReject(): void
    {
        $response = new Response(200);
        $expectedJob = new Job();
        $partnerizeResponse = new PartnerizeResponse();
        $partnerizeResponse->setJob($expectedJob);

        $this->apiClient
            ->expects($this->once())
            ->method('request')
            ->with('POST', 'campaign/' . $this->campaignId . '/conversion', [
                'json' => [
                    'conversions' => [
                        [
                            'conversion_id' => 'testId',
                            'status' => 'rejected',
                            'reject_reason' => 'testReason',
                        ]
                    ]
                ]
            ])
            ->willReturn($response);

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->willReturn($partnerizeResponse);

        $client = new PartnerizeClient($this->trackingClient, $this->apiClient, $this->campaignId, $this->serializer);
        $job = $client->rejectConversion('testId', 'testReason');

        $this->assertEquals($expectedJob, $job);
    }

    /**
     * Test that the client throws a ClientException when a GuzzleException is caught in the getJobUpdate method
     */
    public function testGuzzleExceptionThrowsClientExceptionOnGetJobUpdate(): void
    {
        $this->apiClient
            ->expects($this->once())
            ->method('request')
            ->willThrowException(new InvalidArgumentException('Exception returned'));

        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('Exception returned');

        $client = new PartnerizeClient($this->trackingClient, $this->apiClient, $this->campaignId, $this->serializer);
        $client->getJobUpdate('testId');
    }

    /**
     * Test that the client returns a Job model from the getJobUpdate method
     */
    public function testJobReturnedOnGetJobUpdate(): void
    {
        $response = new Response(200);
        $expectedJob = new Job();
        $partnerizeResponse = new PartnerizeResponse();
        $partnerizeResponse->setJob($expectedJob);

        $this->apiClient
            ->expects($this->once())
            ->method('request')
            ->with('GET', 'job/testId')
            ->willReturn($response);

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->willReturn($partnerizeResponse);

        $client = new PartnerizeClient($this->trackingClient, $this->apiClient, $this->campaignId, $this->serializer);
        $job = $client->getJobUpdate('testId');

        $this->assertEquals($expectedJob, $job);
    }

    /**
     * Test that the client throws a ClientException when a GuzzleException is caught in the getJobResponse method
     */
    public function testGuzzleExceptionThrowsClientExceptionOnGetJobResponse(): void
    {
        $this->apiClient
            ->expects($this->once())
            ->method('request')
            ->willThrowException(new InvalidArgumentException('Exception returned'));

        $this->expectException(ClientException::class);
        $this->expectExceptionMessage('Exception returned');

        $client = new PartnerizeClient($this->trackingClient, $this->apiClient, $this->campaignId, $this->serializer);
        $client->getJobResponse('testId');
    }

    /**
     * Test that the client returns a Job model from the getJobResponse method
     */
    public function testResponseReturnedOnGetJobResponse(): void
    {
        $response = new Response(200);
        $expectedPartnerizeResponse = new PartnerizeResponse();

        $this->apiClient
            ->expects($this->once())
            ->method('request')
            ->with('GET', 'job/testId/response')
            ->willReturn($response);

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->willReturn($expectedPartnerizeResponse);

        $client = new PartnerizeClient($this->trackingClient, $this->apiClient, $this->campaignId, $this->serializer);
        $partnerizeResponse = $client->getJobResponse('testId');

        $this->assertEquals($expectedPartnerizeResponse, $partnerizeResponse);
    }
}
