<?php

namespace App\Entity;

use App\Repository\MailHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MailHistoryRepository::class)]
class MailHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $idMatch = null;

    #[ORM\Column(length: 255)]
    private ?string $score = null;

    #[ORM\Column(length: 255)]
    private ?string $scoreSet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdMatch(): ?string
    {
        return $this->idMatch;
    }

    public function setIdMatch(string $idMatch): static
    {
        $this->idMatch = $idMatch;

        return $this;
    }

    public function getScore(): ?string
    {
        return $this->score;
    }

    public function setScore(string $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getScoreSet(): ?string
    {
        return $this->scoreSet;
    }

    public function setScoreSet(string $scoreSet): static
    {
        $this->scoreSet = $scoreSet;

        return $this;
    }
}
