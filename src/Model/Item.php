<?php

namespace Superbrave\PartnerizeBundle\Model;

use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * Class Item
 *
 * @package Superbrave\PartnerizeBundle\Model
 */
class Item
{
    /**
     * @var string
     */
    private $category;

    /**
     * @var string|null
     */
    private $sku;

    /**
     * @var float|null
     */
    private $value;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var string|null
     * @SerializedName("product_name")
     */
    private $productName;

    /**
     * @var string|null
     * @SerializedName("product_brand")
     */
    private $productBrand;

    /**
     * @var string|null
     * @SerializedName("product_type")
     */
    private $productType;

    /**
     * Item constructor.
     * @param string $category
     * @param int    $quantity
     */
    public function __construct(string $category, int $quantity = 1)
    {
        $this->setCategory($category);
        $this->setQuantity($quantity);
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    /**
     * @return string|null
     */
    public function getSku(): ?string
    {
        return $this->sku;
    }

    /**
     * @param string|null $sku
     */
    public function setSku(?string $sku): void
    {
        $this->sku = $sku;
    }

    /**
     * @return float|null
     */
    public function getValue(): ?float
    {
        return $this->value;
    }

    /**
     * @param float|null $value
     */
    public function setValue(?float $value): void
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    /**
     * @return string|null
     */
    public function getProductName(): ?string
    {
        return $this->productName;
    }

    /**
     * @param string|null $productName
     */
    public function setProductName(?string $productName): void
    {
        $this->productName = $productName;
    }

    /**
     * @return string|null
     */
    public function getProductBrand(): ?string
    {
        return $this->productBrand;
    }

    /**
     * @param string|null $productBrand
     */
    public function setProductBrand(?string $productBrand): void
    {
        $this->productBrand = $productBrand;
    }

    /**
     * @return string|null
     */
    public function getProductType(): ?string
    {
        return $this->productType;
    }

    /**
     * @param string|null $productType
     */
    public function setProductType(?string $productType): void
    {
        $this->productType = $productType;
    }
}
