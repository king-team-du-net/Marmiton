<?php

namespace App\Entity\Blog;

use App\Entity\Traits\HasIdTrait;
use App\Entity\Traits\HasIPTrait;
use App\Entity\Traits\HasIsApprovedTrait;
use App\Entity\Traits\HasIsRGPDTrait;
use App\Entity\User;
use App\Repository\Blog\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use function Symfony\Component\String\u;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    use HasIdTrait;
    use HasIPTrait;
    use HasIsApprovedTrait;
    use HasIsRGPDTrait;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Please don't leave your comment blank!")]
    #[Assert\Length(
        min: 100,
        minMessage: 'Comment is too short ({ limit } characters minimum)',
        max: 10000,
        maxMessage: 'Comment is too long ({ limit } characters maximum)'
    )]
    private ?string $content = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(onDelete: 'CASCADE', nullable: false)]
    private ?User $author = null;

    #[ORM\ManyToOne(targetEntity: Article::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $article = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTime $publishedAt;

    public function __toString(): string
    {
        return "{$this->author->getNickname()} {$this->publishedAt->format('d/m/y à H:i:s')}";
    }

    #[Assert\IsTrue(message: 'The content of this comment is considered spam.')]
    public function isLegitComment(): bool
    {
        $containsInvalidCharacters = null !== u($this->content)->indexOf('@');

        return !$containsInvalidCharacters;
    }

    public function __construct()
    {
        $this->publishedAt = new \DateTime();
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;

        return $this;
    }

    public function getPublishedAt(): \DateTime
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTime $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }
}
