<?php

namespace status\V1\Rest\PingRest\Model;

use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: FindAlbum::class)]
#[ORM\Table(name: "album")]
class Album
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id;

    #[ORM\Column(length: 100)]
    private string $artist;

    #[ORM\Column(length: 100)]
    private string $title;
    public function __construct(string $artist, string $title)
    {
        $this->id = null;
        $this->artist = $artist;
        $this->title = $title;
    }
    /**
     * @return string
     */
    public function getArtist(): string
    {
        return $this->artist;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string $artist
     */
    public function setArtist(string $artist): void
    {
        $this->artist = $artist;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }


}