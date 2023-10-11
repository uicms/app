<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TopicRepository")
 */
class Topic implements TranslatableInterface
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Topic")
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
     * @ORM\OneToMany(targetEntity="App\Entity\LinkContributorTopic", mappedBy="topic")
     */
    private $link_contributor_topic;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LinkContributionTopic", mappedBy="topic")
     */
    private $link_contribution_topic;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LinkResourceTopic", mappedBy="topic")
     */
    private $link_resource_topic;

/**
	 * @ORM\OneToMany(targetEntity="App\Entity\LinkEventTopic", mappedBy="topic")
	 */
	private $link_event_topic;



    public function __construct()
    {
        $this->link_contribution_topic = new ArrayCollection();
        $this->link_contributor_topic = new ArrayCollection();
        $this->link_resource_topic = new ArrayCollection();
        $this->link_event_topic = new ArrayCollection();
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
     * @return Collection<int, LinkContributorTopic>
     */
    public function getLinkContributorTopic(): Collection
    {
        return $this->link_contributor_topic;
    }

    public function addLinkContributorTopic(LinkContributorTopic $linkContributorTopic): self
    {
        if (!$this->link_contributor_topic->contains($linkContributorTopic)) {
            $this->link_contributor_topic[] = $linkContributorTopic;
            $linkContributorTopic->setTopic($this);
        }

        return $this;
    }

    public function removeLinkContributorTopic(LinkContributorTopic $linkContributorTopic): self
    {
        if ($this->link_contributor_topic->removeElement($linkContributorTopic)) {
            // set the owning side to null (unless already changed)
            if ($linkContributorTopic->getTopic() === $this) {
                $linkContributorTopic->setTopic(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LinkContributionTopic>
     */
    public function getLinkContributionTopic(): Collection
    {
        return $this->link_contribution_topic;
    }

    public function addLinkContributionTopic(LinkContributionTopic $linkContributionTopic): self
    {
        if (!$this->link_contribution_topic->contains($linkContributionTopic)) {
            $this->link_contribution_topic[] = $linkContributionTopic;
            $linkContributionTopic->setTopic($this);
        }

        return $this;
    }

    public function removeLinkContributionTopic(LinkContributionTopic $linkContributionTopic): self
    {
        if ($this->link_contribution_topic->removeElement($linkContributionTopic)) {
            // set the owning side to null (unless already changed)
            if ($linkContributionTopic->getTopic() === $this) {
                $linkContributionTopic->setTopic(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LinkResourceTopic[]
     */
    public function getLinkResourceTopic(): Collection
    {
        return $this->link_resource_topic;
    }

    public function addLinkResourceTopic(LinkResourceTopic $linkResourceTopic): self
    {
        if (!$this->link_resource_topic->contains($linkResourceTopic)) {
            $this->link_resource_topic[] = $linkResourceTopic;
            $linkResourceTopic->setTopic($this);
        }

        return $this;
    }

    public function removeLinkResourceTopic(LinkResourceTopic $linkResourceTopic): self
    {
        if ($this->link_resource_topic->removeElement($linkResourceTopic)) {
            // set the owning side to null (unless already changed)
            if ($linkResourceTopic->getTopic() === $this) {
                $linkResourceTopic->setTopic(null);
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
            $linkEventTopic->setTopic($this);
        }

        return $this;
    }

    public function removeLinkEventTopic(LinkEventTopic $linkEventTopic): self
    {
        if ($this->link_event_topic->removeElement($linkEventTopic)) {
            // set the owning side to null (unless already changed)
            if ($linkEventTopic->getTopic() === $this) {
                $linkEventTopic->setTopic(null);
            }
        }

        return $this;
    }
}