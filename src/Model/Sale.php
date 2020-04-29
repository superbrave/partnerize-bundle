<?php

namespace Superbrave\PartnerizeBundle\Model;

use DateTime;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * Class Sale
 *
 * @package Superbrave\PartnerizeBundle\Model
 */
class Sale
{
    public const
        CUSTOMERTYPE_NEW = 'new',
        CUSTOMERTYPE_EXISTING = 'existing',
        TRACKING_MODE_API = 'api';

    /**
     * @var string
     * @SerializedName("clickref")
     */
    private $clickReference;

    /**
     * @var string
     * @SerializedName("conversionref")
     */
    private $conversionReference;

    /**
     * @var string|null ISO-4217 three-letter currency code
     */
    private $currency;

    /**
     * @var string|null
     * @SerializedName("custref")
     */
    private $customerReference;

    /**
     * @var string|null ISO-3166 two-letter country code
     */
    private $country;

    /**
     * @var string|null
     * @SerializedName("customertype")
     */
    private $customerType;

    /**
     * @var string|null
     */
    private $voucher;

    /**
     * @var DateTime|null
     * @SerializedName("conversion_time")
     */
    private $conversionTime;

    /**
     * @var Item[]
     */
    private $items = [];

    /**
     * @var string|null
     * @SerializedName("tracking_mode")
     */
    private $trackingMode;

    /**
     * @var string|null
     */
    private $campaign;

    /**
     * Conversion constructor.
     *
     * @param string $clickReference      clickReference from Partnerize platform (clickref)
     * @param string $conversionReference unique reference for this sale
     */
    public function __construct(string $clickReference, string $conversionReference)
    {
        $this->clickReference = $clickReference;
        $this->conversionReference = $conversionReference;
    }

    /**
     * @return string
     */
    public function getClickReference(): string
    {
        return $this->clickReference;
    }

    /**
     * @param string $clickReference
     */
    public function setClickReference(string $clickReference): void
    {
        $this->clickReference = $clickReference;
    }

    /**
     * @return string
     */
    public function getConversionReference(): string
    {
        return $this->conversionReference;
    }

    /**
     * @param string $conversionReference
     */
    public function setConversionReference(string $conversionReference): void
    {
        $this->conversionReference = $conversionReference;
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param string|null $currency
     */
    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return string|null
     */
    public function getCustomerReference(): ?string
    {
        return $this->customerReference;
    }

    /**
     * @param string|null $customerReference
     */
    public function setCustomerReference(?string $customerReference): void
    {
        $this->customerReference = $customerReference;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     */
    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return string|null
     */
    public function getCustomerType(): ?string
    {
        return $this->customerType;
    }

    /**
     * @param string|null $customerType
     */
    public function setCustomerType(?string $customerType): void
    {
        $this->customerType = $customerType;
    }

    /**
     * @return string|null
     */
    public function getVoucher(): ?string
    {
        return $this->voucher;
    }

    /**
     * @param string|null $voucher
     */
    public function setVoucher(?string $voucher): void
    {
        $this->voucher = $voucher;
    }

    /**
     * @return DateTime|null
     */
    public function getConversionTime(): ?DateTime
    {
        return $this->conversionTime ? clone $this->conversionTime : null;
    }

    /**
     * @param DateTime|null $conversionTime
     */
    public function setConversionTime(?DateTime $conversionTime): void
    {
        $this->conversionTime = $conversionTime ? clone $conversionTime : null;
    }

    /**
     * @return Item[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param Item $item
     */
    public function addItem(Item $item): void
    {
        $this->items[] = $item;
    }

    /**
     * @return string|null
     */
    public function getTrackingMode(): ?string
    {
        return $this->trackingMode;
    }

    /**
     * @param string|null $trackingMode
     */
    public function setTrackingMode(?string $trackingMode): void
    {
        $this->trackingMode = $trackingMode;
    }

    /**
     * @return string|null
     */
    public function getCampaign(): ?string
    {
        return $this->campaign;
    }

    /**
     * @param string|null $campaign
     */
    public function setCampaign(?string $campaign): void
    {
        $this->campaign = $campaign;
    }
}
