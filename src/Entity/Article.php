<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;
use App\Weather\Weather;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * 
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "app_article_show",
 *          parameters = {"id" = "expr(object.getId())"},
 *          absolute = true
 *      )
 * )
 * @Hateoas\Relation(
 *      "update",
 *      href = @Hateoas\Route(
 *          "app_article_update",
 *          parameters = {"id" = "expr(object.getId())"},
 *          absolute = true
 *      )
 * )
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "app_article_delete",
 *          parameters = {"id" = "expr(object.getId())"},
 *          absolute = true
 *      )
 * )
 * @Hateoas\Relation(
 *      "author",
 *      embedded = @Hateoas\Embedded("expr(object.getAuthor())")
 * )
 * @Hateoas\Relation(
 *      "weather",
 *      embedded = @Hateoas\Embedded("expr(service('app.weather').getCurrent())")
 * )
 * @Hateoas\Relation(
 *      "authencicated_user",
 *      embedded = @Hateoas\Embedded("expr(service('security.token_storage').getToken().getUser())")
 * )
 * 
 * @Serializer\ExclusionPolicy("all")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * 
     * @Serializer\Expose
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank
     * 
     * @Serializer\Expose
     * 
     * @Serializer\Since("1.0")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * 
     * @Assert\NotBlank
     * 
     * @Serializer\Expose
     * 
     * @Serializer\Since("1.0")
     */
    private $content;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     * 
     * @Serializer\Expose
     * 
     * @Serializer\Since("2.0")
     */
    private $shortDescription;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Author", inversedBy="articles", cascade={"persist"})
     * 
     */
    private $author;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }
}
