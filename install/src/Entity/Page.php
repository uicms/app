<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PageRepository")
 */
class Page implements TranslatableInterface
{
	use TranslatableTrait;
	
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
	/* Links */
   
	/**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user=NULL;
	
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Page")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent=NULL;

	public function __call($method, $args)
                                   	{
                                   	    return $this->proxyCurrentLocaleTranslation($method, $args);
                                   	}

	
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
     * @ORM\Column(type="integer")
     */
    private $position=0;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $is_dir=0;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $controller = 'default';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $action = 'index';

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $template;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $menu = 'menu';
	
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug = '';

/**
	 * @ORM\OneToMany(targetEntity="App\Entity\LinkPageBlock", mappedBy="page")
	 */
	private $link_page_block;

    /**
                * @ORM\Column(type="string",nullable=true)
                */
                private $helper;

/**
	 * @ORM\OneToMany(targetEntity="App\Entity\LinkPageMedia", mappedBy="page")
	 */
	private $link_page_media;





    public function __construct()
    {
        $this->link_page_block = new ArrayCollection();
        $this->link_page_media = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
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

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getIsDir(): ?string
    {
        return $this->is_dir;
    }

    public function setIsDir(string $is_dir): self
    {
        $this->is_dir = $is_dir;

        return $this;
    }

    public function getController(): ?string
    {
        return $this->controller;
    }

    public function setController(string $controller): self
    {
        $this->controller = $controller;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getMenu(): ?string
    {
        return $this->menu;
    }

    public function setMenu(?string $menu): self
    {
        $this->menu = $menu;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

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
     * @return Collection|LinkPageBlock[]
     */
    public function getLinkPageBlock(): Collection
    {
        return $this->link_page_block;
    }

    public function addLinkPageBlock(LinkPageBlock $linkPageBlock): self
    {
        if (!$this->link_page_block->contains($linkPageBlock)) {
            $this->link_page_block[] = $linkPageBlock;
            $linkPageBlock->setPage($this);
        }

        return $this;
    }

    public function removeLinkPageBlock(LinkPageBlock $linkPageBlock): self
    {
        if ($this->link_page_block->removeElement($linkPageBlock)) {
            // set the owning side to null (unless already changed)
            if ($linkPageBlock->getPage() === $this) {
                $linkPageBlock->setPage(null);
            }
        }

        return $this;
    }

    public function getHelper(): ?string
    {
        return $this->helper;
    }

    public function setHelper(?string $helper): self
    {
        $this->helper = $helper;

        return $this;
    }

    /**
     * @return Collection|LinkPageMedia[]
     */
    public function getLinkPageMedia(): Collection
    {
        return $this->link_page_media;
    }

    public function addLinkPageMedium(LinkPageMedia $linkPageMedium): self
    {
        if (!$this->link_page_media->contains($linkPageMedium)) {
            $this->link_page_media[] = $linkPageMedium;
            $linkPageMedium->setPage($this);
        }

        return $this;
    }

    public function removeLinkPageMedium(LinkPageMedia $linkPageMedium): self
    {
        if ($this->link_page_media->removeElement($linkPageMedium)) {
            // set the owning side to null (unless already changed)
            if ($linkPageMedium->getPage() === $this) {
                $linkPageMedium->setPage(null);
            }
        }

        return $this;
    }
}
