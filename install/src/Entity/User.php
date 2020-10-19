<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\RegistryInterface;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
	
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user=NULL;
	
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
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
     * @ORM\Column(type="integer")
     */
    private $position=0;
	
    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $is_dir=0;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;
	
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;
	
    /**
     * @Assert\Length(max=4096)
     */
    private $plain_password;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;
	
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $salt = '3rhs1+IWNtC8jPvlRD9RH1Gw53vNgFCanPj63TlD';

	/**
	 * @ORM\Column(type="json")
	 */
	private $roles = [];



    private $isActive;
	public function __construct()
   	{
          $this->isActive = true;
          // may not be needed, see section on salt below
          // $this->salt = md5(uniqid('', true));
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUser(): ?self
    {
        return $this->user;
    }

    public function setUser(?self $user): self
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
	
	public function getRoles()
   	{
  	    return $this->roles;
   	}
	
	public function getPassword()
   	{
   		return $this->password;
   	}
    
    public function setPassword(string $password): self
    {
        if(isset($this->plain_password) && $this->plain_password) {
            $this->password = $password;
        }

        return $this;
    }
    
	public function getUsername()
   	{
   		return $this->email;
   	}
	
	public function eraseCredentials()
   	{
   		
   	}
	
	public function getSalt()
   	{
   		return $this->salt;
   	}
	
	public function serialize()
   	{
   		return serialize(array(
   		            $this->id,
   		            $this->email,
   		            $this->password,
   		            $this->salt,
   		        ));
   	}
	
	public function unserialize($serialized)
   	{
   		list (
   		            $this->id,
   		            $this->email,
   		            $this->password,
   		            $this->salt
   		        ) = unserialize($serialized, array('allowed_classes' => false));
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setSalt(string $salt): self
    {
        $this->salt = $salt;

        return $this;
    }

    public function setRoles($roles): self
    {
        $this->roles = $roles;
        return $this;
    }
    
    public function getPlainPassword(): ?string
    {
        return $this->plain_password;
    }
    
    public function setPlainPassword(?string $plain_password): self
    {
        $this->plain_password = $plain_password;
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

    public function getIsDir(): ?string
    {
        return $this->is_dir;
    }

    public function setIsDir(string $is_dir): self
    {
        $this->is_dir = $is_dir;

        return $this;
    }
}
