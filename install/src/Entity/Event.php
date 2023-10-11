<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event implements TranslatableInterface
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Event")
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
                * @ORM\Column(type="date",nullable=true)
                */
                private $date_begin;

    /**
                * @ORM\Column(type="date",nullable=true)
                */
                private $date_end;

    /**
                * @ORM\Column(type="time",nullable=true)
                */
                private $time_begin;

    /**
                * @ORM\Column(type="time",nullable=true)
                */
                private $time_end;

/**
	 * @ORM\OneToMany(targetEntity="App\Entity\LinkEventVenue", mappedBy="event")
	 */
	private $link_event_venue;

/**
	 * @ORM\OneToMany(targetEntity="App\Entity\LinkEventContributor", mappedBy="event")
	 */
	private $link_event_contributor;

/**
	 * @ORM\OneToMany(targetEntity="App\Entity\LinkEventMedia", mappedBy="event")
	 */
	private $link_event_media;

/**
	 * @ORM\OneToMany(targetEntity="App\Entity\LinkEventTopic", mappedBy="event")
	 */
	private $link_event_topic;

/**
	 * @ORM\OneToMany(targetEntity="App\Entity\LinkEventKeyword", mappedBy="event")
	 */
	private $link_event_keyword;

/**
                * @ORM\ManyToOne(targetEntity="App\Entity\Contributor")
                * @ORM\JoinColumn(name="contributor_id", referencedColumnName="id")
                */
                private $contributor;



    public function __construct()
    {
        $this->link_event_venue = new ArrayCollection();
        $this->link_event_contributor = new ArrayCollection();
        $this->link_event_media = new ArrayCollection();
        $this->link_event_topic = new ArrayCollection();
        $this->link_event_keyword = new ArrayCollection();
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

                public function getDateBegin(): ?\DateTimeInterface
                {
                    return $this->date_begin;
                }

                public function setDateBegin(?\DateTimeInterface $date_begin): self
                {
                    $this->date_begin = $date_begin;

                    return $this;
                }

                public function getDateEnd(): ?\DateTimeInterface
                {
                    return $this->date_end;
                }

                public function setDateEnd(?\DateTimeInterface $date_end): self
                {
                    $this->date_end = $date_end;

                    return $this;
                }

                public function getTimeBegin(): ?\DateTimeInterface
                {
                    return $this->time_begin;
                }

                public function setTimeBegin(?\DateTimeInterface $time_begin): self
                {
                    $this->time_begin = $time_begin;

                    return $this;
                }

                public function getTimeEnd(): ?\DateTimeInterface
                {
                    return $this->time_end;
                }

                public function setTimeEnd(?\DateTimeInterface $time_end): self
                {
                    $this->time_end = $time_end;

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
                        $linkEventVenue->setEvent($this);
                    }

                    return $this;
                }

                public function removeLinkEventVenue(LinkEventVenue $linkEventVenue): self
                {
                    if ($this->link_event_venue->removeElement($linkEventVenue)) {
                        // set the owning side to null (unless already changed)
                        if ($linkEventVenue->getEvent() === $this) {
                            $linkEventVenue->setEvent(null);
                        }
                    }

                    return $this;
                }

                /**
                 * @return Collection|LinkEventContributor[]
                 */
                public function getLinkEventContributor(): Collection
                {
                    return $this->link_event_contributor;
                }

                public function addLinkEventContributor(LinkEventContributor $linkEventContributor): self
                {
                    if (!$this->link_event_contributor->contains($linkEventContributor)) {
                        $this->link_event_contributor[] = $linkEventContributor;
                        $linkEventContributor->setEvent($this);
                    }

                    return $this;
                }

                public function removeLinkEventContributor(LinkEventContributor $linkEventContributor): self
                {
                    if ($this->link_event_contributor->removeElement($linkEventContributor)) {
                        // set the owning side to null (unless already changed)
                        if ($linkEventContributor->getEvent() === $this) {
                            $linkEventContributor->setEvent(null);
                        }
                    }

                    return $this;
                }

                /**
                 * @return Collection|LinkEventMedia[]
                 */
                public function getLinkEventMedia(): Collection
                {
                    return $this->link_event_media;
                }

                public function addLinkEventMedium(LinkEventMedia $linkEventMedium): self
                {
                    if (!$this->link_event_media->contains($linkEventMedium)) {
                        $this->link_event_media[] = $linkEventMedium;
                        $linkEventMedium->setEvent($this);
                    }

                    return $this;
                }

                public function removeLinkEventMedium(LinkEventMedia $linkEventMedium): self
                {
                    if ($this->link_event_media->removeElement($linkEventMedium)) {
                        // set the owning side to null (unless already changed)
                        if ($linkEventMedium->getEvent() === $this) {
                            $linkEventMedium->setEvent(null);
                        }
                    }

                    return $this;
                }

                /**
                 * @return Collection|LinkEventTopic[]
                 */
                public function getLinkEventTopic(): Collection
                {
                    return $this->link_event_topic;
                }

                public function addLinkEventTopic(LinkEventTopic $linkEventTopic): self
                {
                    if (!$this->link_event_topic->contains($linkEventTopic)) {
                        $this->link_event_topic[] = $linkEventTopic;
                        $linkEventTopic->setEvent($this);
                    }

                    return $this;
                }

                public function removeLinkEventTopic(LinkEventTopic $linkEventTopic): self
                {
                    if ($this->link_event_topic->removeElement($linkEventTopic)) {
                        // set the owning side to null (unless already changed)
                        if ($linkEventTopic->getEvent() === $this) {
                            $linkEventTopic->setEvent(null);
                        }
                    }

                    return $this;
                }

                /**
                 * @return Collection|LinkEventKeyword[]
                 */
                public function getLinkEventKeyword(): Collection
                {
                    return $this->link_event_keyword;
                }

                public function addLinkEventKeyword(LinkEventKeyword $linkEventKeyword): self
                {
                    if (!$this->link_event_keyword->contains($linkEventKeyword)) {
                        $this->link_event_keyword[] = $linkEventKeyword;
                        $linkEventKeyword->setEvent($this);
                    }

                    return $this;
                }

                public function removeLinkEventKeyword(LinkEventKeyword $linkEventKeyword): self
                {
                    if ($this->link_event_keyword->removeElement($linkEventKeyword)) {
                        // set the owning side to null (unless already changed)
                        if ($linkEventKeyword->getEvent() === $this) {
                            $linkEventKeyword->setEvent(null);
                        }
                    }

                    return $this;
                }

                public function getContributor(): ?Contributor
                {
                    return $this->contributor;
                }

                public function setContributor(?Contributor $contributor): self
                {
                    $this->contributor = $contributor;

                    return $this;
                }








}