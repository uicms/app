<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ContributionRepository")
 */
class Contribution 
{
	public function __call($method, $args)
    {

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Contribution")
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
    * @ORM\Column(type="string",nullable=true)
    */
    private $name;

    /**
    * @ORM\Column(type="text",nullable=true)
    */
    private $description;

    /**
    * @ORM\Column(type="date",nullable=true)
    */
    private $date_begin;

    /**
    * @ORM\Column(type="date",nullable=true)
    */
    private $date_end;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Contributor")
    * @ORM\JoinColumn(name="contributor_id", referencedColumnName="id")
    */
    private $contributor;

    /**
	 * @ORM\OneToMany(targetEntity="App\Entity\LinkContributionKeyword", mappedBy="contribution")
	 */
	private $link_contribution_keyword;

    /**
	 * @ORM\OneToMany(targetEntity="App\Entity\LinkContributionTopic", mappedBy="contribution")
	 */
	private $link_contribution_topic;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LinkContributionResource", mappedBy="contribution")
     */
    private $link_contribution_resource;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\ContributionStatus")
    * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
    */
    private $status;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\ContributionStatus")
    * @ORM\JoinColumn(name="contribution_status_id", referencedColumnName="id")
    */
    private $contribution_status;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\ContributionType")
    * @ORM\JoinColumn(name="contribution_type_id", referencedColumnName="id")
    */
    private $contribution_type;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\ContributionObject")
    * @ORM\JoinColumn(name="contribution_object_id", referencedColumnName="id")
    */
    private $contribution_object;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Selection", mappedBy="contribution")
     */
    private $selection;
    
    public function __construct()
    {
        $this->link_contribution_keyword = new ArrayCollection();
        $this->link_contribution_topic = new ArrayCollection();
        $this->link_contribution_resource = new ArrayCollection();
        $this->selection = new ArrayCollection();
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

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

    public function getObject(): ?string
    {
        return $this->object;
    }

    public function setObject(?string $object): self
    {
        $this->object = $object;

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

    public function getContributor(): ?Contributor
    {
        return $this->contributor;
    }

    public function setContributor(?Contributor $contributor): self
    {
        $this->contributor = $contributor;

        return $this;
    }

    /**
     * @return Collection<int, LinkContributionKeyword>
     */
    public function getLinkContributionKeyword(): Collection
    {
        return $this->link_contribution_keyword;
    }

    public function addLinkContributionKeyword(LinkContributionKeyword $linkContributionKeyword): self
    {
        if (!$this->link_contribution_keyword->contains($linkContributionKeyword)) {
            $this->link_contribution_keyword[] = $linkContributionKeyword;
            $linkContributionKeyword->setContribution($this);
        }

        return $this;
    }

    public function removeLinkContributionKeyword(LinkContributionKeyword $linkContributionKeyword): self
    {
        if ($this->link_contribution_keyword->removeElement($linkContributionKeyword)) {
            // set the owning side to null (unless already changed)
            if ($linkContributionKeyword->getContribution() === $this) {
                $linkContributionKeyword->setContribution(null);
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
            $linkContributionTopic->setContribution($this);
        }

        return $this;
    }

    public function removeLinkContributionTopic(LinkContributionTopic $linkContributionTopic): self
    {
        if ($this->link_contribution_topic->removeElement($linkContributionTopic)) {
            // set the owning side to null (unless already changed)
            if ($linkContributionTopic->getContribution() === $this) {
                $linkContributionTopic->setContribution(null);
            }
        }

        return $this;
    }

    public function getContributionStatus(): ?ContributionStatus
    {
        return $this->contribution_status;
    }

    public function setContributionStatus(?ContributionStatus $contribution_status): self
    {
        $this->contribution_status = $contribution_status;

        return $this;
    }

    public function getContributionType(): ?ContributionType
    {
        return $this->contribution_type;
    }

    public function setContributionType(?ContributionType $contribution_type): self
    {
        $this->contribution_type = $contribution_type;

        return $this;
    }

    public function getContributionObject(): ?ContributionObject
    {
        return $this->contribution_object;
    }

    public function setContributionObject(?ContributionObject $contribution_object): self
    {
        $this->contribution_object = $contribution_object;

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
            $linkContributionResource->setContribution($this);
        }

        return $this;
    }

    public function removeLinkContributionResource(LinkContributionResource $linkContributionResource): self
    {
        if ($this->link_contribution_resource->removeElement($linkContributionResource)) {
            // set the owning side to null (unless already changed)
            if ($linkContributionResource->getContribution() === $this) {
                $linkContributionResource->setContribution(null);
            }
        }

        return $this;
    }
    
    /**
     * @return Collection<int, Selection>
     */
    public function getSelection(): Collection
    {
        return $this->selection;
    }

    public function addSelection(Selection $selection): self
    {
        if (!$this->selection->contains($selection)) {
            $this->selection[] = $selection;
            $selection->setContribution($this);
        }

        return $this;
    }

    public function removeSelection(Selection $selection): self
    {
        if ($this->selection->removeElement($selection)) {
            // set the owning side to null (unless already changed)
            if ($selection->getContribution() === $this) {
                $selection->setContribution(null);
            }
        }

        return $this;
    }
}