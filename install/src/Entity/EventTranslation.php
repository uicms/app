<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventTranslationRepository")
 */
class EventTranslation implements TranslationInterface
{
	use TranslationTrait;
	
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
                * @ORM\Column(type="string",nullable=true)
                */
                private $name;

    /**
                * @ORM\Column(type="text",nullable=true)
                */
                private $description;

                public function getId(): ?int
                {
                    return $this->id;
                }

                public function getName(): ?string
                {
                    return $this->name;
                }

                public function setName(?string $name): self
                {
                    $this->name = $name;

                    return $this;
                }

                public function getDescription(): ?string
                {
                    return $this->description;
                }

                public function setDescription(?string $description): self
                {
                    $this->description = $description;

                    return $this;
                }




}
