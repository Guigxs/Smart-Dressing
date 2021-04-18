<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"show_category", "show_cloth"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show_category", "show_cloth"})
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"show_category", "show_cloth"})
     * @Assert\NotBlank
     * @Assert\Choice({-10, -5, 0, 5, 10, 20, 30})
     */
    private $temperature;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show_cloth", "show_category"})
     * @Assert\NotBlank
     * @Assert\Choice({"sunny", "rainy", "foggy", "cloudy", "thunderstorm", "snowy"})
     */
    private $weather;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show_category", "show_cloth"})
     * @Assert\NotBlank
     * @Assert\Choice({"none", "drizzle", "medium", "heavy"})
     */
    private $rainLevel;

    /**
     * @ORM\ManyToMany(targetEntity=Cloth::class, mappedBy="category")
     * @Groups("show_category")
     */
    private $cloths;

    public function __construct()
    {
        $this->cloths = new ArrayCollection();
    }



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

    public function getWeather(): ?string
    {
        return $this->weather;
    }

    public function setWeather(string $weather): self
    {
        $this->weather = $weather;

        return $this;
    }

    public function getRainLevel(): ?string
    {
        return $this->rainLevel;
    }

    public function setRainLevel(string $rainLevel): self
    {
        $this->rainLevel = $rainLevel;

        return $this;
    }

    /**
     * @return Collection|Cloth[]
     */
    public function getCloths(): Collection
    {
        return $this->cloths;
    }

    public function addCloth(Cloth $cloth): self
    {
        if (!$this->cloths->contains($cloth)) {
            $this->cloths[] = $cloth;
            $cloth->addCategory($this);
        }

        return $this;
    }

    public function removeCloth(Cloth $cloth): self
    {
        if ($this->cloths->removeElement($cloth)) {
            $cloth->removeCategory($this);
        }

        return $this;
    }

}
