<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $invoice_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $due_date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $total_amount = null;

    /**
     * @var Collection<int, InvoiceItem>
     */
    #[ORM\OneToMany(targetEntity: InvoiceItem::class, mappedBy: 'invoice', orphanRemoval: true)]
    private Collection $invoiceItems;

    #[ORM\OneToOne(mappedBy: 'invoice', cascade: ['persist', 'remove'])]
    private ?PaymentStatus $paymentStatus = null;

    #[ORM\OneToOne(mappedBy: 'invoice', cascade: ['persist', 'remove'])]
    private ?Client $client = null;

    public function __construct()
    {
        $this->invoiceItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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


    public function getInvoiceDate(): ?\DateTimeInterface
    {
        return $this->invoice_date;
    }

    public function setInvoiceDate(\DateTimeInterface $invoice_date): static
    {
        $this->invoice_date = $invoice_date;

        return $this;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->due_date;
    }

    public function setDueDate(\DateTimeInterface $due_date): static
    {
        $this->due_date = $due_date;

        return $this;
    }


    public function getTotalAmount(): ?string
    {
        return $this->total_amount;
    }

    public function setTotalAmount(string $total_amount): static
    {
        $this->total_amount = $total_amount;

        return $this;
    }

    /**
     * @return Collection<int, InvoiceItem>
     */
    public function getInvoiceItems(): Collection
    {
        return $this->invoiceItems;
    }

    public function addInvoiceItem(InvoiceItem $invoiceItem): static
    {
        if (!$this->invoiceItems->contains($invoiceItem)) {
            $this->invoiceItems->add($invoiceItem);
            $invoiceItem->setInvoice($this);
        }

        return $this;
    }

    public function removeInvoiceItem(InvoiceItem $invoiceItem): static
    {
        if ($this->invoiceItems->removeElement($invoiceItem)) {
            if ($invoiceItem->getInvoice() === $this) {
                $invoiceItem->setInvoice(null);
            }
        }

        return $this;
    }

    public function getPaymentStatus(): ?PaymentStatus
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(PaymentStatus $paymentStatus): static
    {
        if ($paymentStatus->getInvoice() !== $this) {
            $paymentStatus->setInvoice($this);
        }

        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        if ($client === null && $this->client !== null) {
            $this->client->setInvoice(null);
        }

        if ($client !== null && $client->getInvoice() !== $this) {
            $client->setInvoice($this);
        }

        $this->client = $client;

        return $this;
    }
}
