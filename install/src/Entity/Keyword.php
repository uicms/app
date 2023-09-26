<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\KeywordRepository")
 */
class Keyword implements TranslatableInterface
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Keyword")
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
	 * @ORM\OneToMany(targetEntity="App\Entity\LinkResourceKeyword", mappedBy="keyword")
	 */
	private $link_resource_keyword;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LinkContributionKeyword", mappedBy="keyword")
     */
    private $link_contribution_keyword;


    public function __construct()
    {
        $this->link_resource_keyword = new ArrayCollection();
        $this->link_contribution_keyword = new ArrayCollection();
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
            $linkContributionKeyword->setKeyword($this);
        }

        return $this;
    }

    public function removeLinkContributionKeyword(LinkContributionKeyword $linkContributionKeyword): self
    {
        if ($this->link_contribution_keyword->removeElement($linkContributionKeyword)) {
            // set the owning side to null (unless already changed)
            if ($linkContributionKeyword->getKeyword() === $this) {
                $linkContributionKeyword->setKeyword(null);
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
            $linkResourceKeyword->setKeyword($this);
        }

        return $this;
    }

    public function removeLinkResourceKeyword(LinkResourceKeyword $linkResourceKeyword): self
    {
        if ($this->link_resource_keyword->removeElement($linkResourceKeyword)) {
            // set the owning side to null (unless already changed)
            if ($linkResourceKeyword->getKeyword() === $this) {
                $linkResourceKeyword->setKeyword(null);
            }
        }

        return $this;
    }
}