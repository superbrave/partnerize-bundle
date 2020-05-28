<?php

namespace Superbrave\PartnerizeBundle\Tests\Encoder;

use PHPUnit\Framework\TestCase;
use Superbrave\PartnerizeBundle\Encoder\PartnerizeS2SEncoder;

/**
 * Class PartnerizeS2SEncoderTest
 * @package Superbrave\PartnerizeBundle\Tests\Encoder
 */
class PartnerizeS2SEncoderTest extends TestCase
{
    /**
     * @var PartnerizeS2SEncoder
     */
    private $encoder;

    /**
     * Setup class variables for testing
     */
    public function setUp(): void
    {
        $this->encoder = new PartnerizeS2SEncoder();
    }

    /**
     * Clear class variables after testing
     */
    public function breakDown(): void
    {
        $this->encoder = null;
    }

    /**
     * Test that everything properly encodes
     *
     * @dataProvider encodingDataProvider
     */
    public function testEncode($data, $expectedResult)
    {
        $result = $this->encoder->encode($data, PartnerizeS2SEncoder::FORMAT, []);
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Test that the encoder throws a RuntimeException on bad input data
     */
    public function testEncodeThrowsRuntimeExceptionIfDataNotAnArray(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Expected first parameter to be an array, but got string.');

        $this->encoder->encode('test', 'json');
    }

    /**
     * Test that the encoder throws a RuntimeException on unnormalized data
     */
    public function testEncodeThrowsRuntimeExceptionWhenDataValueIsNotScalar(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Value for test has not been normalized properly.');

        $this->encoder->encode(['test' => []], 'json');
    }

    /**
     * Test that the support method only returns true on proper format
     *
     * @dataProvider formatDataProvider
     */
    public function testSupportsEncoding($encoding, $expected): void
    {
        $result = $this->encoder->supportsEncoding($encoding);
        $this->assertEquals($expected, $result);
    }

    /**
     * @return array
     */
    public function formatDataProvider(): array
    {
        return [
            [
                PartnerizeS2SEncoder::FORMAT,
                true
            ],
            [
                'notSupportedEncoding',
                false
            ]
        ];
    }

    /**
     * @return array
     */
    public function encodingDataProvider(): array
    {
        return [
            [
                ['abc' => 123, 'def' => 456, 'ghi' => 'jkl'],
                'abc:123/def:456/ghi:jkl',
            ],
            [
                ['abc' => 123, 'items' => [
                    ['def' => 'ghi', 'jkl' => 'mno'],
                    ['pqr' => 'stu', 'vwx' => 'yz'],
                ]],
                'abc:123/[def:ghi/jkl:mno][pqr:stu/vwx:yz]',
            ],
            [
                ['var' => 'a weird value'],
                'var:a%20weird%20value',
            ],
            [
                [
                    'tracking_mode' => 'api',
                    'campaign' => 'XXXXX',
                    'clickref' => 'def456',
                    'conversionref' => 19970608,
                    'voucher' => 'tenpercent',
                    'items' => [
                        ['category' => 'bag', 'sku' => '3245ds', 'value' => 10.00, 'quantity' => 2],
                        ['category' => 'book', 'sku' => '2123bk', 'value' => 40.00, 'quantity' => 1]
                    ],
                ],
                'tracking_mode:api/campaign:XXXXX/clickref:def456/conversionref:19970608/voucher:tenpercent/' .
                '[category:bag/sku:3245ds/value:10/quantity:2][category:book/sku:2123bk/value:40/quantity:1]',
            ],
            [
                ['abc' => null, 'def' => 123, 'ghi' => null, 'jkl' => 456, 'mno' => null],
                'def:123/jkl:456',
            ],
        ];
    }
}
