<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VenueRepository")
 */
class Venue implements TranslatableInterface
{
    use TranslatableTrait;
    
	public function __call($method, $args)
                                                                                              	{
                                                                                                   return $this->proxyCurrentLocaleTranslation($method, $args);
                                                                                              	}
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
	
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user=NULL;
	
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Venue")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent=NULL;
	
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;
	
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modified;
	
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $published;
	
    /**
     * @ORM\Column(type="boolean")
     */
    private $is_concealed=0;

	/**
     * @ORM\Column(type="boolean")
     */
    private $is_locked=0;
	
	/**
     * @ORM\Column(type="boolean")
     */
    private $is_dir=0;
	
	/**
     * @ORM\Column(type="integer")
     */
    private $position=0;

        /**
                * @ORM\Column(type="float",nullable=true)
                */
                private $latitude;

    /**
                * @ORM\Column(type="float",nullable=true)
                */
                private $longitude;

/**
	 * @ORM\OneToMany(targetEntity="App\Entity\LinkEventVenue", mappedBy="venue")
	 */
	private $link_event_venue;

/**
	 * @ORM\OneToMany(targetEntity="App\Entity\LinkVenueMedia", mappedBy="venue")
	 */
	private $link_venue_media;



    public function __construct()
    {
        $this->link_event_venue = new ArrayCollection();
        $this->link_venue_media = new ArrayCollection();
    }



                public function getCreated(): ?\DateTimeInterface
                {
                    return $this->created;
                }

                public function setCreated(?\DateTimeInterface $created): self
                {
                    $this->created = $created;

                    return $this;
                }

                public function getModified(): ?\DateTimeInterface
                {
                    return $this->modified;
                }

                public function setModified(?\DateTimeInterface $modified): self
                {
                    $this->modified = $modified;

                    return $this;
                }

                public function getPublished(): ?\DateTimeInterface
                {
                    return $this->published;
                }

                public function setPublished(?\DateTimeInterface $published): self
                {
                    $this->published = $published;

                    return $this;
                }

                public function getIsConcealed(): ?bool
                {
                    return $this->is_concealed;
                }

                public function setIsConcealed(bool $is_concealed): self
                {
                    $this->is_concealed = $is_concealed;

                    return $this;
                }

                public function getIsLocked(): ?bool
                {
                    return $this->is_locked;
                }

                public function setIsLocked(bool $is_locked): self
                {
                    $this->is_locked = $is_locked;

                    return $this;
                }

                public function getIsDir(): ?bool
                {
                    return $this->is_dir;
                }

                public function setIsDir(bool $is_dir): self
                {
                    $this->is_dir = $is_dir;

                    return $this;
                }

                public function getPosition(): ?int
                {
                    return $this->position;
                }

                public function setPosition(int $position): self
                {
                    $this->position = $position;

                    return $this;
                }

                public function getLatitude(): ?string
                {
                    return $this->latitude;
                }

                public function setLatitude(?string $latitude): self
                {
                    $this->latitude = $latitude;

                    return $this;
                }

                public function getLongitude(): ?float
                {
                    return $this->longitude;
                }

                public function setLongitude(?float $longitude): self
                {
                    $this->longitude = $longitude;

                    return $this;
                }

                public function getUser(): ?User
                {
                    return $this->user;
                }

                public function setUser(?User $user): self
                {
                    $this->user = $user;

                    return $this;
                }

                public function getParent(): ?self
                {
                    return $this->parent;
                }

                public function setParent(?self $parent): self
                {
                    $this->parent = $parent;

                    return $this;
                }

                /**
                 * @return Collection|LinkEventVenue[]
                 */
                public function getLinkEventVenue(): Collection
                {
                    return $this->link_event_venue;
                }

                public function addLinkEventVenue(LinkEventVenue $linkEventVenue): self
                {
                    if (!$this->link_event_venue->contains($linkEventVenue)) {
                        $this->link_event_venue[] = $linkEventVenue;
                        $linkEventVenue->setVenue($this);
                    }

                    return $this;
                }

                public function removeLinkEventVenue(LinkEventVenue $linkEventVenue): self
                {
                    if ($this->link_event_venue->removeElement($linkEventVenue)) {
                        // set the owning side to null (unless already changed)
                        if ($linkEventVenue->getVenue() === $this) {
                            $linkEventVenue->setVenue(null);
                        }
                    }

                    return $this;
                }

                /**
                 * @return Collection|LinkVenueMedia[]
                 */
                public function getLinkVenueMedia(): Collection
                {
                    return $this->link_venue_media;
                }

                public function addLinkVenueMedium(LinkVenueMedia $linkVenueMedium): self
                {
                    if (!$this->link_venue_media->contains($linkVenueMedium)) {
                        $this->link_venue_media[] = $linkVenueMedium;
                        $linkVenueMedium->setVenue($this);
                    }

                    return $this;
                }

                public function removeLinkVenueMedium(LinkVenueMedia $linkVenueMedium): self
                {
                    if ($this->link_venue_media->removeElement($linkVenueMedium)) {
                        // set the owning side to null (unless already changed)
                        if ($linkVenueMedium->getVenue() === $this) {
                            $linkVenueMedium->setVenue(null);
                        }
                    }

                    return $this;
                }




}