<?php

namespace App\Entity;

use App\Repository\QuoteItemRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuoteItemRepository::class)]
class QuoteItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private  $unit_price = null;

    #[ORM\Column(length: 20)]
    private ?string $quote_version = null;

    #[ORM\ManyToOne(inversedBy: 'quoteItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quote $quote = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUnitPrice(): ?string
    {
        return $this->unit_price;
    }

    public function setUnitPrice(string $unit_price): static
    {
        $this->unit_price = $unit_price;

        return $this;
    }

    public function getQuoteVersion(): ?string
    {
        return $this->quote_version;
    }

    public function setQuoteVersion(string $quote_version): static
    {
        $this->quote_version = $quote_version;

        return $this;
    }

    public function getQuote(): ?Quote
    {
        return $this->quote;
    }

    public function setQuote(?Quote $quote): static
    {
        $this->quote= $quote;

        return $this;
    }
}
