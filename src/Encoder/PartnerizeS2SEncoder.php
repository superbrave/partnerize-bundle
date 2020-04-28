<?php

namespace Superbrave\PartnerizeBundle\Encoder;

use RuntimeException;
use Symfony\Component\Serializer\Encoder\EncoderInterface;

/**
 * Class PartnerizeS2SEncoder
 * @package Superbrave\PartnerizeBundle\Encoder
 */
class PartnerizeS2SEncoder implements EncoderInterface
{
    public const FORMAT = 'parterize_s2s';

    /**
     * {@inheritDoc}
     */
    public function encode($data, $format, array $context = [])
    {
        if (!is_array($data)) {
            throw new RuntimeException('Expected first parameter to be an array, but got ' . gettype($data) . '.');
        }

        $items = null;
        if (array_key_exists('items', $data)) {
            $items = $data['items'];
            unset($data['items']);
        }

        $encoded = [];
        foreach ($data as $variable => $value) {
            if ($value === null) {
                continue;
            }
            if (!is_scalar($value)) {
                throw new RuntimeException('Value for ' . $variable . ' has not been normalized properly.');
            }
            $encoded[] = $variable . ':' . rawurlencode($value);
        }

        $encodedItems = [];
        if ($items) {
            foreach ($items as $item) {
                //TODO: fix possibility of nested "items", because in the api items cannot contain subitems.
                $encodedItems[] = '[' . $this->encode($item, $format, $context) . ']';
            }
            $encoded[] =  implode('', $encodedItems);
        }

        return implode('/', $encoded);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsEncoding($format): bool
    {
        return ($format === self::FORMAT);
    }
}
