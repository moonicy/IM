<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmployeeRepository")
 */
class Employee
{
    const ROLE_ADMIN = 0;
    const ROLE_EMPLOYEE = 1;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fio;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $position;

    /**
     * @ORM\Column(type="smallint")
     */
    private $role;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFio(): ?string
    {
        return $this->fio;
    }

    public function setFio(string $fio): self
    {
        $this->fio = $fio;

        return $this;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role): self
    {
        $this->role = $role;

        return $this;
    }
}
