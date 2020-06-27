<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 */
class Rdv
{
    /**
     * @Assert\Email(message="Adresse mail invalide")
     */
    private $email;

    /**
     * @Assert\NotBlank(message="Ce champ ne peut être vide")
     */
    private $name;

    /**
     * @Assert\NotBlank(message="Ce champ ne peut être vide")
     */
    private $subject;

    /**
     * 
     */
    private $slot1;

    /**
     * 
     */
    private $slot2;

    /**
     * 
     */
    private $slot3;

    /**
     *
     */
    private $message;


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getSlot1(): ?string
    {
        return $this->slot1;
    }

    public function setSlot1(string $slot1): self
    {
        $this->slot1 = $slot1;

        return $this;
    }

    public function getSlot2(): ?string
    {
        return $this->slot2;
    }

    public function setSlot2(string $slot2): self
    {
        $this->slot2 = $slot2;

        return $this;
    }

    public function getSlot3(): ?string
    {
        return $this->slot3;
    }

    public function setSlot3(string $slot3): self
    {
        $this->slot3 = $slot3;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
