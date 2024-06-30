<?php

namespace App\Entity;

use App\Repository\QuoteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuoteRepository::class)]
class Quote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTimeInterface $quote_date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $total_amount = null;

    /**
     * @var Collection<int, QuoteItem>
     */
    #[ORM\OneToMany(targetEntity: QuoteItem::class, mappedBy: 'quote', orphanRemoval: true)]
    private Collection $quoteItems;

    #[ORM\ManyToOne(inversedBy: 'quotes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    #[ORM\OneToOne(mappedBy: 'quote', cascade: ['persist', 'remove'])]
    private ?Client $client = null;

    #[ORM\Column(length: 255)]
    private ?string $quote_number = null;

    #[ORM\Column]
    private ?bool $accepted = false;

    public function __construct()
    {
        $this->quoteItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuoteDate(): ?\DateTimeInterface
    {
        return $this->quote_date;
    }

    public function setQuoteDate(\DateTimeInterface $quote_date): static
    {
        $this->quote_date = $quote_date;

        return $this;
    }

    public function getTotalAmount(): string
    {
        return $this->total_amount;
    }

    public function setTotalAmount(string $total_amount): static
    {
        $this->total_amount = $total_amount;

        return $this;
    }

    /**
     * @return Collection<int, QuoteItem>
     */
    public function getQuoteItems(): Collection
    {
        return $this->quoteItems;
    }

    public function addQuoteItem(QuoteItem $quoteItems): static
    {
        if (!$this->quoteItems->contains($quoteItems)) {
            $this->quoteItems->add($quoteItems);
            $quoteItems->setQuoteId($this);
        }

        return $this;
    }

    public function removeQuoteItem(QuoteItem $quoteItems): static
    {
        if ($this->quoteItems->removeElement($quoteItems)) {
            if ($quoteItems->getQuoteId() === $this) {
                $quoteItems->setQuoteId(null);
            }
        }

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(Client $client): static
    {
        if ($client->getQuote() !== $this) {
            $client->setQuote($this);
        }

        $this->client = $client;

        return $this;
    }

    public function getQuoteNumber(): ?string
    {
        return $this->quote_number;
    }

    public function setQuoteNumber(string $quote_number): static
    {
        $this->quote_number = $quote_number;

        return $this;
    }

    public function isAccepted(): ?bool
    {
        return $this->accepted;
    }

    public function setAccepted(bool $accepted): static
    {
        $this->accepted = $accepted;

        return $this;
    }
}
