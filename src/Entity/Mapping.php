<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MappingRepository")
 * @ORM\Table(indexes={
 *  @ORM\Index(name="matches", columns={"shopify_url","stockx_url"}),
 * })
 */
class Mapping
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $shopifyUrl;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stockxUrl;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hashOldPriceAndSize = '';

    /**
     * @ORM\OneToMany(targetEntity=SizePrice::class,  mappedBy="mapping", cascade={"persist"})
     */
    private $sizePrices;

    /**
     * @ORM\Column(type="integer")
     */
    private $current = 0;

    public function __construct() {
        $this->sizePrices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShopifyUrl(): ?string
    {
        return $this->shopifyUrl;
    }

    public function setShopifyUrl(string $shopifyUrl): self
    {
        $this->shopifyUrl = $shopifyUrl;

        return $this;
    }

    public function getStockxUrl(): ?string
    {
        return $this->stockxUrl;
    }

    public function setStockxUrl(string $stockxUrl): self
    {
        $this->stockxUrl = $stockxUrl;

        return $this;
    }

    public function getHashOldPriceAndSize(): ?string
    {
        return $this->hashOldPriceAndSize;
    }

    public function setHashOldPriceAndSize(string $hashOldPriceAndSize): self
    {
        $this->hashOldPriceAndSize = $hashOldPriceAndSize;

        return $this;
    }

    public function getSizePrices() {
        return $this->sizePrices;
    }

    public function addSizePrice(SizePrice $sizePrice )
    {
        $this->sizePrices->add( $sizePrice );

        return $this;
    }

    public function removeSizePrice( SizePrice $sizePrice )
    {
        $this->sizePrices->remove( $sizePrice );

        return $this;
    }

    public function getCurrent(): ?int
    {
        return $this->current;
    }

    public function setCurrent(int $current): self
    {
        $this->current = $current;

        return $this;
    }
}
