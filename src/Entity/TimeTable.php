<?php

namespace App\Entity;

use App\Repository\TimeTableRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TimeTableRepository::class)
 */
class TimeTable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Ce champ ne peut pas comporter plus de {{ limit }} caractères"
     * )
     */
    private $monAm;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Ce champ ne peut pas comporter plus de {{ limit }} caractères"
     * )
     */
    private $monPm;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Ce champ ne peut pas comporter plus de {{ limit }} caractères"
     * )
     */
    private $tueAm;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Ce champ ne peut pas comporter plus de {{ limit }} caractères"
     * )
     */
    private $tuePm;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Ce champ ne peut pas comporter plus de {{ limit }} caractères"
     * )
     */
    private $wedAm;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Ce champ ne peut pas comporter plus de {{ limit }} caractères"
     * )
     */
    private $wedPm;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Ce champ ne peut pas comporter plus de {{ limit }} caractères"
     * )
     */
    private $thuAm;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Ce champ ne peut pas comporter plus de {{ limit }} caractères"
     * )
     */
    private $thuPm;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Ce champ ne peut pas comporter plus de {{ limit }} caractères"
     * )
     */
    private $friAm;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Ce champ ne peut pas comporter plus de {{ limit }} caractères"
     * )
     */
    private $friPm;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Ce champ ne peut pas comporter plus de {{ limit }} caractères"
     * )
     */
    private $satAm;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Ce champ ne peut pas comporter plus de {{ limit }} caractères"
     * )
     */
    private $satPm;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Ce champ ne peut pas comporter plus de {{ limit }} caractères"
     * )
     */
    private $sunAm;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "Ce champ ne peut pas comporter plus de {{ limit }} caractères"
     * )
     */
    private $sunPm;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMonAm(): ?string
    {
        return $this->monAm;
    }

    public function setMonAm(string $monAm): self
    {
        $this->monAm = $monAm;

        return $this;
    }

    public function getMonPm(): ?string
    {
        return $this->monPm;
    }

    public function setMonPm(string $monPm): self
    {
        $this->monPm = $monPm;

        return $this;
    }

    public function getTueAm(): ?string
    {
        return $this->tueAm;
    }

    public function setTueAm(string $tueAm): self
    {
        $this->tueAm = $tueAm;

        return $this;
    }

    public function getTuePm(): ?string
    {
        return $this->tuePm;
    }

    public function setTuePm(string $tuePm): self
    {
        $this->tuePm = $tuePm;

        return $this;
    }

    public function getWedAm(): ?string
    {
        return $this->wedAm;
    }

    public function setWedAm(string $wedAm): self
    {
        $this->wedAm = $wedAm;

        return $this;
    }

    public function getWedPm(): ?string
    {
        return $this->wedPm;
    }

    public function setWedPm(string $wedPm): self
    {
        $this->wedPm = $wedPm;

        return $this;
    }

    public function getThuAm(): ?string
    {
        return $this->thuAm;
    }

    public function setThuAm(string $thuAm): self
    {
        $this->thuAm = $thuAm;

        return $this;
    }

    public function getThuPm(): ?string
    {
        return $this->thuPm;
    }

    public function setThuPm(string $thuPm): self
    {
        $this->thuPm = $thuPm;

        return $this;
    }

    public function getFriAm(): ?string
    {
        return $this->friAm;
    }

    public function setFriAm(string $friAm): self
    {
        $this->friAm = $friAm;

        return $this;
    }

    public function getFriPm(): ?string
    {
        return $this->friPm;
    }

    public function setFriPm(string $friPm): self
    {
        $this->friPm = $friPm;

        return $this;
    }

    public function getSatAm(): ?string
    {
        return $this->satAm;
    }

    public function setSatAm(string $satAm): self
    {
        $this->satAm = $satAm;

        return $this;
    }

    public function getSatPm(): ?string
    {
        return $this->satPm;
    }

    public function setSatPm(string $satPm): self
    {
        $this->satPm = $satPm;

        return $this;
    }

    public function getSunAm(): ?string
    {
        return $this->sunAm;
    }

    public function setSunAm(string $sunAm): self
    {
        $this->sunAm = $sunAm;

        return $this;
    }

    public function getSunPm(): ?string
    {
        return $this->sunPm;
    }

    public function setSunPm(string $sunPm): self
    {
        $this->sunPm = $sunPm;

        return $this;
    }
}
