<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $temperature;

    /**
     * @ORM\Column(type="integer")
     */
    private $weather;

    /**
     * @ORM\Column(type="integer")
     */
    private $rainLevel;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTemperature(): ?int
    {
        return $this->temperature;
    }

    public function setTemperature(int $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getWeather(): ?int
    {
        return $this->weather;
    }

    public function setWeather(int $weather): self
    {
        $this->weather = $weather;

        return $this;
    }

    public function getRainLevel(): ?int
    {
        return $this->rainLevel;
    }

    public function setRainLevel(int $rainLevel): self
    {
        $this->rainLevel = $rainLevel;

        return $this;
    }
}
