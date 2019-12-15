<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LaptopRepository")
 */
class Laptop
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
    private $number;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firm;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $model;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateBuy;

    /**
     * @ORM\Column(type="dateinterval", nullable=true)
     */
    private $interval;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberCores;

    /**
     * @ORM\Column(type="integer")
     */
    private $memory;

    /**
     * @ORM\Column(type="integer")
     */
    private $disk;

    /**
     * @ORM\OneToMany(targetEntity="Status", mappedBy="laptop")
     */
    private $statuses;

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

    public function getFirm(): ?string
    {
        return $this->firm;
    }

    public function setFirm(string $firm): self
    {
        $this->firm = $firm;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getDateBuy(): ?\DateTimeInterface
    {
        return $this->dateBuy;
    }

    public function setDateBuy(\DateTimeInterface $dateBuy): self
    {
        $this->dateBuy = $dateBuy;

        return $this;
    }

    public function getInterval(): ?\DateInterval
    {
        return $this->interval;
    }

    public function setInterval(\DateInterval $interval): self
    {
        $this->interval = $interval;

        return $this;
    }

    public function getNumberCores(): ?int
    {
        return $this->numberCores;
    }

    public function setNumberCores(int $numberCores): self
    {
        $this->numberCores = $numberCores;

        return $this;
    }

    public function getMemory(): ?int
    {
        return $this->memory;
    }

    public function setMemory(int $memory): self
    {
        $this->memory = $memory;

        return $this;
    }

    public function getDisk(): ?int
    {
        return $this->disk;
    }

    public function setDisk(int $disk): self
    {
        $this->disk = $disk;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatuses()
    {
        return $this->statuses;
    }

    /**
     * @param mixed $statuses
     */
    public function setStatuses($statuses): void
    {
        $this->statuses = $statuses;
    }
}
