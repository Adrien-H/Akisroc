<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BoardRepository")
 * @UniqueEntity("title", message="violation.title.not_unique")
 * @UniqueEntity("slug", message="violation.slug.not_unique")
 */
class Board extends AbstractEntity
{
    /**
     * @ORM\Column(type="string", length=63, nullable=false, unique=true)
     *
     * @Assert\NotBlank(message="violation.description.blank")
     * @Assert\Length(
     *     min=1,
     *     max=60,
     *     minMessage="violation.title.too_short",
     *     maxMessage="violation.title.too_long"
     * )
     *
     * @var string|null
     */
    private ?string $title = null;

    /**
     * @ORM\Column(type="string", length=511, nullable=false)
     *
     * @Assert\NotBlank(message="violation.description.blank")
     * @Assert\Length(
     *     min=1,
     *     max=500,
     *     minMessage="violation.description.too_short",
     *     maxMessage="violation.description.too_long"
     * )
     *
     * @var string|null
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="string", length=511, nullable=true)
     *
     * @Assert\Url(message="violation.uri.wrong_format")
     * @Assert\Length(
     *     min=1,
     *     max=500,
     *     minMessage="violation.uri.too_short",
     *     maxMessage="violation.uri.too_long"
     * )
     *
     * @var string|null
     */
    private ?string $image = null;

    /**
     * @ORM\Column(type="string", length=63, nullable=false, unique=true)
     *
     * @Gedmo\Slug(fields={"title"})
     *
     * @var string|null
     */
    private ?string $slug = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Topic", mappedBy="board", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     * @ORM\OrderBy({"id"="ASC"})
     */
    private ?Collection $topics = null;

    public function __construct()
    {
        $this->topics = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }

    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param null|string $title
     * @return void
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     * @return void
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     * @return void
     */
    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string|null $slug
     * @return void
     */
    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return Collection|null
     */
    public function getTopics(): ?Collection
    {
        return $this->topics;
    }

    /**
     * @param Topic $topic
     * @return void
     */
    public function addTopic(Topic $topic): void
    {
        if (!$this->topics->contains($topic)) {
            $this->topics->add($topic);
            $topic->setBoard($this);
        }
    }

    /**
     * @param Topic $topic
     * @return void
     */
    public function removeTopic(Topic $topic): void
    {
        if ($this->topics->contains($topic)) {
            $this->topics->removeElement($topic);
            if ($topic->getBoard() === $this) {
                $topic->setBoard(null);
            }
        }
    }
}
