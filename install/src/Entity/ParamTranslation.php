<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParamTranslationRepository")
 */
class ParamTranslation implements TranslationInterface
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
                private $value;

                public function getId(): ?int
                {
                    return $this->id;
                }

                public function getValue(): ?string
                {
                    return $this->value;
                }

                public function setValue(?string $value): self
                {
                    $this->value = $value;

                    return $this;
                }


}
