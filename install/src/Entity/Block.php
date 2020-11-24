<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BlockRepository")
 */
class Block implements TranslatableInterface
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Block")
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
    private $type='default';

    /**
	 * @ORM\OneToMany(targetEntity="App\Entity\LinkPageBlock", mappedBy="block")
	 */
	private $link_page_block;

    /**
	 * @ORM\OneToMany(targetEntity="App\Entity\LinkBlockMedia", mappedBy="block")
	 */
	private $link_block_media;

    /**
    * @ORM\Column(type="string",nullable=true)
    */
    private $class;





    public function __construct()
    {
        $this->link_page_block = new ArrayCollection();
        $this->link_block_media = new ArrayCollection();
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

                public function getType(): ?string
                {
                    return $this->type;
                }

                public function setType(string $type): self
                {
                    $this->type = $type;

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
                        $linkPageBlock->setBlock($this);
                    }

                    return $this;
                }

                public function removeLinkPageBlock(LinkPageBlock $linkPageBlock): self
                {
                    if ($this->link_page_block->removeElement($linkPageBlock)) {
                        // set the owning side to null (unless already changed)
                        if ($linkPageBlock->getBlock() === $this) {
                            $linkPageBlock->setBlock(null);
                        }
                    }

                    return $this;
                }

                /**
                 * @return Collection|LinkBlockMedia[]
                 */
                public function getLinkBlockMedia(): Collection
                {
                    return $this->link_block_media;
                }

                public function addLinkBlockMedium(LinkBlockMedia $linkBlockMedium): self
                {
                    if (!$this->link_block_media->contains($linkBlockMedium)) {
                        $this->link_block_media[] = $linkBlockMedium;
                        $linkBlockMedium->setBlock($this);
                    }

                    return $this;
                }

                public function removeLinkBlockMedium(LinkBlockMedia $linkBlockMedium): self
                {
                    if ($this->link_block_media->removeElement($linkBlockMedium)) {
                        // set the owning side to null (unless already changed)
                        if ($linkBlockMedium->getBlock() === $this) {
                            $linkBlockMedium->setBlock(null);
                        }
                    }

                    return $this;
                }

                public function getClass(): ?string
                {
                    return $this->class;
                }

                public function setClass(?string $class): self
                {
                    $this->class = $class;

                    return $this;
                }


}