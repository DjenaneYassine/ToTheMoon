<?php

namespace App\Entity;

use App\Repository\StatsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatsRepository::class)]
class Stats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $league = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $nameHome = null;

    #[ORM\Column(length: 255)]
    private ?string $nameAway = null;

    #[ORM\Column]
    private ?int $idMatch = null;

    #[ORM\Column(length: 255)]
    private ?string $scoreSet = null;

    #[ORM\Column(length: 255)]
    private ?string $score = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $winner = null;

    #[ORM\Column(nullable: true)]
    private ?bool $win = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLeague(): ?string
    {
        return $this->league;
    }

    public function setLeague(string $league): static
    {
        $this->league = $league;

        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getNameHome(): ?string
    {
        return $this->nameHome;
    }

    public function setNameHome(string $nameHome): static
    {
        $this->nameHome = $nameHome;

        return $this;
    }

    public function getNameAway(): ?string
    {
        return $this->nameAway;
    }

    public function setNameAway(string $nameAway): static
    {
        $this->nameAway = $nameAway;

        return $this;
    }

    public function getIdMatch(): ?int
    {
        return $this->idMatch;
    }

    public function setIdMatch(int $idMatch): static
    {
        $this->idMatch = $idMatch;

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

    public function getScore(): ?string
    {
        return $this->score;
    }

    public function setScore(string $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function getWinner(): ?string
    {
        return $this->winner;
    }

    public function setWinner(?string $winner): static
    {
        $this->winner = $winner;

        return $this;
    }

    public function isWin(): ?bool
    {
        return $this->win;
    }

    public function setWin(?bool $win): static
    {
        $this->win = $win;

        return $this;
    }
}
