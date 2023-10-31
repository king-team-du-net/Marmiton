<?php

namespace App\Entity\Blog;

use App\Entity\Traits\HasBadgeCategoryTrait;
use App\Repository\Blog\BadgeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BadgeRepository::class)]
class Badge
{
    use HasBadgeCategoryTrait;

    /**
     * @var Collection<int, Article>
     */
    #[ORM\ManyToMany(targetEntity: Article::class, inversedBy: 'badges')]
    #[ORM\JoinTable(name: 'tag_article')]
    private Collection $articles;

    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
        }

        return $this;
    }

    public function removeArticle(Article $article): static
    {
        $this->articles->removeElement($article);

        return $this;
    }
}
