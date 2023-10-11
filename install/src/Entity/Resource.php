<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResourceRepository")
 */
class Resource implements TranslatableInterface
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Resource")
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
    * @ORM\Column(type="string",nullable=false)
    */
    private $status='pending';

    /**
	 * @ORM\OneToMany(targetEntity="App\Entity\LinkResourceMedia", mappedBy="resource")
	 */
	private $link_resource_media;

    /**
	 * @ORM\OneToMany(targetEntity="App\Entity\LinkResourceTopic", mappedBy="resource")
	 */
	private $link_resource_topic;

    /**
	 * @ORM\OneToMany(targetEntity="App\Entity\LinkResourceKeyword", mappedBy="resource")
	 */
	private $link_resource_keyword;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LinkContributionResource", mappedBy="resource")
     */
    private $link_contribution_resource;

/**
                * @ORM\ManyToOne(targetEntity="App\Entity\Contributor")
                * @ORM\JoinColumn(name="contributor_id", referencedColumnName="id")
                */
                private $contributor;






    public function __construct()
    {
        $this->link_resource_media = new ArrayCollection();
        $this->link_resource_topic = new ArrayCollection();
        $this->link_resource_keyword = new ArrayCollection();
        $this->link_contribution_resource = new ArrayCollection();
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

                public function getStatus(): ?string
                {
                    return $this->status;
                }

                public function setStatus(?string $status): self
                {
                    $this->status = $status;

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
                 * @return Collection|LinkResourceMedia[]
                 */
                public function getLinkResourceMedia(): Collection
                {
                    return $this->link_resource_media;
                }

                public function addLinkResourceMedium(LinkResourceMedia $linkResourceMedium): self
                {
                    if (!$this->link_resource_media->contains($linkResourceMedium)) {
                        $this->link_resource_media[] = $linkResourceMedium;
                        $linkResourceMedium->setResource($this);
                    }

                    return $this;
                }

                public function removeLinkResourceMedium(LinkResourceMedia $linkResourceMedium): self
                {
                    if ($this->link_resource_media->removeElement($linkResourceMedium)) {
                        // set the owning side to null (unless already changed)
                        if ($linkResourceMedium->getResource() === $this) {
                            $linkResourceMedium->setResource(null);
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
                        $linkResourceTopic->setResource($this);
                    }

                    return $this;
                }

                public function removeLinkResourceTopic(LinkResourceTopic $linkResourceTopic): self
                {
                    if ($this->link_resource_topic->removeElement($linkResourceTopic)) {
                        // set the owning side to null (unless already changed)
                        if ($linkResourceTopic->getResource() === $this) {
                            $linkResourceTopic->setResource(null);
                        }
                    }

                    return $this;
                }

                /**
                 * @return Collection|LinkResourceKeyword[]
                 */
                public function getLinkResourceKeyword(): Collection
                {
                    return $this->link_resource_keyword;
                }

                public function addLinkResourceKeyword(LinkResourceKeyword $linkResourceKeyword): self
                {
                    if (!$this->link_resource_keyword->contains($linkResourceKeyword)) {
                        $this->link_resource_keyword[] = $linkResourceKeyword;
                        $linkResourceKeyword->setResource($this);
                    }

                    return $this;
                }

                public function removeLinkResourceKeyword(LinkResourceKeyword $linkResourceKeyword): self
                {
                    if ($this->link_resource_keyword->removeElement($linkResourceKeyword)) {
                        // set the owning side to null (unless already changed)
                        if ($linkResourceKeyword->getResource() === $this) {
                            $linkResourceKeyword->setResource(null);
                        }
                    }

                    return $this;
                }

                /**
                 * @return Collection<int, LinkContributionResource>
                 */
                public function getLinkContributionResource(): Collection
                {
                    return $this->link_contribution_resource;
                }

                public function addLinkContributionResource(LinkContributionResource $linkContributionResource): self
                {
                    if (!$this->link_contribution_resource->contains($linkContributionResource)) {
                        $this->link_contribution_resource[] = $linkContributionResource;
                        $linkContributionResource->setResource($this);
                    }

                    return $this;
                }

                public function removeLinkContributionResource(LinkContributionResource $linkContributionResource): self
                {
                    if ($this->link_contribution_resource->removeElement($linkContributionResource)) {
                        // set the owning side to null (unless already changed)
                        if ($linkContributionResource->getResource() === $this) {
                            $linkContributionResource->setResource(null);
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