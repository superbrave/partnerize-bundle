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
     * @dataProvider encodingDataProvider
     */
    public function testEncoding($data, $expectedResult)
    {
        $encoder = new PartnerizeS2SEncoder();
        $result = $encoder->encode($data, PartnerizeS2SEncoder::FORMAT, []);
        $this->assertEquals($expectedResult, $result);
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
            ]
        ];
    }
}
