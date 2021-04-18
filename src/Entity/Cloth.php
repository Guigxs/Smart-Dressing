<?php

namespace App\Entity;

use App\Repository\ClothRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ClothRepository::class)
 */
class Cloth
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"show_cloth", "show_category"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"show_category", "show_cloth"})
     * @Assert\NotBlank
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show_category","show_cloth"})
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show_category", "show_cloth"})
     * @Assert\NotBlank
     */
    private $fabric;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"show_category", "show_cloth"})
     * @Assert\NotBlank
     * @Assert\Choice({1, 2, 3, 4, 5, 6, 7, 8, 9, 10})
     */
    private $quantity;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="cloths")
     * @Groups("show_cloth")
     */
    private $category;

    public function __construct()
    {
        $this->category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
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

    public function getFabric(): ?string
    {
        return $this->fabric;
    }

    public function setFabric(string $fabric): self
    {
        $this->fabric = $fabric;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }
}
