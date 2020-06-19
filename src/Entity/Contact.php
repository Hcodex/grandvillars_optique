<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class Contact
{
    /**
     */
    private $id;

    /**
     * @Assert\Email(message="Adresse mail invalide")
     */
    private $email;

    /**
     * @Assert\NotBlank(message="Ce champ ne peut être vide")
     */
    private $lastName;

    /**
     * @Assert\NotBlank(message="Ce champ ne peut être vide")
     */
    private $subject;

    /**
     * @Assert\Length(min=20, minMessage="Votre message doit comporter au moins 20 caractères")
     */
    private $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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
