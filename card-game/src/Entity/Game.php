<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    private ?User $playerOne = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    private ?User $playerTwo = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    private ?Card $playerOneCard = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    private ?Card $playerTwoCard = null;

    #[ORM\Column(length: 255)]
    private ?string $result = null;

    #[ORM\ManyToOne(inversedBy: 'winnedGames')]
    private ?User $winner = null;

    #[ORM\ManyToOne]
    private ?Card $playerOneSecondCard = null;

    #[ORM\ManyToOne]
    private ?Card $playerTwoSecondCard = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPlayerOne(): ?User
    {
        return $this->playerOne;
    }

    public function setPlayerOne(?User $playerOne): static
    {
        $this->playerOne = $playerOne;

        return $this;
    }

    public function getPlayerTwo(): ?User
    {
        return $this->playerTwo;
    }

    public function setPlayerTwo(?User $playerTwo): static
    {
        $this->playerTwo = $playerTwo;

        return $this;
    }

    public function getPlayerOneCard(): ?Card
    {
        return $this->playerOneCard;
    }

    public function setPlayerOneCard(?Card $playerOneCard): static
    {
        $this->playerOneCard = $playerOneCard;

        return $this;
    }

    public function getPlayerTwoCard(): ?Card
    {
        return $this->playerTwoCard;
    }

    public function setPlayerTwoCard(?Card $playerTwoCard): static
    {
        $this->playerTwoCard = $playerTwoCard;

        return $this;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(string $result): static
    {
        $this->result = $result;

        return $this;
    }

    public function getWinner(): ?User
    {
        return $this->winner;
    }

    public function setWinner(?User $winner): static
    {
        $this->winner = $winner;

        return $this;
    }

    public function getPlayerOneSecondCard(): ?Card
    {
        return $this->playerOneSecondCard;
    }

    public function setPlayerOneSecondCard(?Card $playerOneSecondCard): static
    {
        $this->playerOneSecondCard = $playerOneSecondCard;

        return $this;
    }

    public function getPlayerTwoSecondCard(): ?Card
    {
        return $this->playerTwoSecondCard;
    }

    public function setPlayerTwoSecondCard(?Card $playerTwoSecondCard): static
    {
        $this->playerTwoSecondCard = $playerTwoSecondCard;

        return $this;
    }
}
