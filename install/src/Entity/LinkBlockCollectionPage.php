<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LinkBlockCollectionPageRepository")
 */
class LinkBlockCollectionPage 
{
    
    
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
     * @ORM\ManyToOne(targetEntity="App\Entity\LinkBlockCollectionPage")
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
        	 * @ORM\ManyToOne(targetEntity="App\Entity\BlockCollection", inversedBy="link_block_collection_page")
        	 * @ORM\JoinColumn(name="block_collection_id", referencedColumnName="id")
        	 */
        	private $block_collection;

/**
        	 * @ORM\ManyToOne(targetEntity="App\Entity\Page", inversedBy="link_block_collection_page")
        	 * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
        	 */
        	private  $page;

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

         public function getBlockCollection(): ?BlockCollection
         {
             return $this->block_collection;
         }

         public function setBlockCollection(?BlockCollection $block_collection): self
         {
             $this->block_collection = $block_collection;

             return $this;
         }

         public function getPage(): ?Page
         {
             return $this->page;
         }

         public function setPage(?Page $page): self
         {
             $this->page = $page;

             return $this;
         }




}