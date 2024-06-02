<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $articles = [];

    #[ORM\ManyToOne(inversedBy: 'carts')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticles(): array
    {
        return $this->articles;
    }

    public function addArticle(Article $article, int $quantity): self
    {
        $articleId = $article->getId();

        if (isset($this->articles[$articleId])) {
            $this->articles[$articleId]['quantity'] += $quantity;
        } else {
            $this->articles[$articleId] = [
                'article' => $article,
                'quantity' => $quantity,
            ];
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        $articleId = $article->getId();

        if (isset($this->articles[$articleId])) {
            unset($this->articles[$articleId]);
        }

        return $this;
    }

    public function getQuantity(Article $article): ?int
    {
        $articleId = $article->getId();

        return $this->articles[$articleId]['quantity'] ?? null;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
