<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ApiResource (normalizationContext: ['groups' => ['comment']])]
#[ApiFilter(SearchFilter::class, properties: [ 'id_article' => 'exact'])]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups("comment")]
    private $id;

    #[ORM\Column(type: 'integer')]
    #[Groups("comment")]
    private $id_article;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups("comment")]
    private $content;

    #[ORM\OneToMany(mappedBy: 'comment', targetEntity: CommentResponse::class, orphanRemoval: true)]
    #[Groups("comment")]
    private $commentResponses;

    #[ORM\Column(type: 'array', nullable: true)]
    #[Groups("comment")]
    private $rates = [];

    public function __construct()
    {
        $this->commentResponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdArticle(): ?int
    {
        return $this->id_article;
    }

    public function setIdArticle(int $id_article): self
    {
        $this->id_article = $id_article;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection|CommentResponse[]
     */
    public function getCommentResponses(): Collection
    {
        return $this->commentResponses;
    }

    public function addCommentResponse(CommentResponse $commentResponse): self
    {
        if (!$this->commentResponses->contains($commentResponse)) {
            $this->commentResponses[] = $commentResponse;
            $commentResponse->setComment($this);
        }

        return $this;
    }

    public function removeCommentResponse(CommentResponse $commentResponse): self
    {
        if ($this->commentResponses->removeElement($commentResponse)) {
            // set the owning side to null (unless already changed)
            if ($commentResponse->getComment() === $this) {
                $commentResponse->setComment(null);
            }
        }

        return $this;
    }

    public function getRates(): ?array
    {
        return $this->rates;
    }

    public function setRates(?array $rates): self
    {
        $this->rates = $rates;

        return $this;
    }
}
