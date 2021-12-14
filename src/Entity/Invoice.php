<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InvoiceRepository::class)
 */
class Invoice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $number;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $client_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $client_address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $client_logo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $c_client_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $c_client_address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $c_client_phone;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="invoices")
     */
    private $client_order;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getClientName(): ?string
    {
        return $this->client_name;
    }

    public function setClientName(string $client_name): self
    {
        $this->client_name = $client_name;

        return $this;
    }

    public function getClientAddress(): ?string
    {
        return $this->client_address;
    }

    public function setClientAddress(string $client_address): self
    {
        $this->client_address = $client_address;

        return $this;
    }

    public function getClientLogo(): ?string
    {
        return $this->client_logo;
    }

    public function setClientLogo(string $client_logo): self
    {
        $this->client_logo = $client_logo;

        return $this;
    }

    public function getCClientName(): ?string
    {
        return $this->c_client_name;
    }

    public function setCClientName(string $c_client_name): self
    {
        $this->c_client_name = $c_client_name;

        return $this;
    }

    public function getCClientAddress(): ?string
    {
        return $this->c_client_address;
    }

    public function setCClientAddress(string $c_client_address): self
    {
        $this->c_client_address = $c_client_address;

        return $this;
    }

    public function getCClientPhone(): ?string
    {
        return $this->c_client_phone;
    }

    public function setCClientPhone(string $c_client_phone): self
    {
        $this->c_client_phone = $c_client_phone;

        return $this;
    }

    public function getClientOrder(): ?Order
    {
        return $this->client_order;
    }

    public function setClientOrder(?Order $client_order): self
    {
        $this->client_order = $client_order;

        return $this;
    }
}
