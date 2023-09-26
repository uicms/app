<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\ContributorRepository")
 */
class Contributor implements UserInterface, \Serializable
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Contributor")
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
    private $email;

    /**
    * @ORM\Column(type="string",nullable=true)
    */
    private $firstname;

    /**
    * @ORM\Column(type="string",nullable=true)
    */
    private $lastname;

    /**
    * @ORM\Column(type="string",nullable=true)
    */
    private $status = 'pending';

    /**
    * @ORM\Column(type="string",nullable=true)
    */
    private $phone;


    /**
    * @ORM\Column(type="string",nullable=true)
    */
    private $thumbnail;

    /**
    * @ORM\Column(type="string",nullable=true)
    */
    private $short_description;

    /**
    * @ORM\Column(type="text",nullable=true)
    */
    private $description;

    /**
    * @ORM\Column(type="array",nullable=true)
    */
    private $is_contactable;

    /**
    * @ORM\Column(type="array",nullable=true)
    */
    private $email_notification;
    
    /*
    /* USER INTERFACE
    */

    /**
    * @ORM\Column(type="json")
    */
    private $roles=[];

    /**
    * @ORM\Column(type="string",nullable=true)
    */
    private $salt = '3rhs1+IWNtC8jPvlRD9RH1Gw53vNgFCanPj63TlD';

    /**
    * @ORM\Column(type="string",nullable=true)
    */
    private $password;

    

    /**
    * @ORM\Column(type="boolean",nullable=true)
    */
    private $has_agreed;

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\ContributorType")
    * @ORM\JoinColumn(name="contributor_type_id", referencedColumnName="id")
    */
    private $contributor_type;

    /**
	 * @ORM\OneToMany(targetEntity="App\Entity\LinkContributorTopic", mappedBy="contributor")
	 */
	private $link_contributor_topic;

    

    /**
    * @ORM\ManyToOne(targetEntity="App\Entity\Contributor")
    * @ORM\JoinColumn(name="parent_contributor_id", referencedColumnName="id")
    */
    private $parent_contributor;

    /**
     * @Assert\Length(max=4096)
     */
    private $plain_password;



    public function __construct()
    {
        $this->link_contributor_topic = new ArrayCollection();
    }
    
                
	public function getUsername()
   	{
   		return $this->email;
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

	public function eraseCredentials()
   	{

   	}

	public function getRoles()
   	{
  	    return $this->roles;
   	}

    public function setRoles($roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    public function setSalt(?string $salt): self
    {
        $this->salt = $salt;

        return $this;
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

    public function getPlainPassword(): ?string
    {
        return $this->plain_password;
    }

    public function setPlainPassword(?string $plain_password): self
    {
        $this->plain_password = $plain_password;
        return $this;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

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

    public function getStatusVisit(): ?array
    {
        return $this->status_visit;
    }

    public function setStatusVisit(?array $status_visit): self
    {
        $this->status_visit = $status_visit;

        return $this;
    }

    public function getHasAgreed(): ?bool
    {
        return $this->has_agreed;
    }

    public function setHasAgreed(?bool $has_agreed): self
    {
        $this->has_agreed = $has_agreed;

        return $this;
    }

    public function getContributorType(): ?ContributorType
    {
        return $this->contributor_type;
    }

    public function setContributorType(?ContributorType $contributor_type): self
    {
        $this->contributor_type = $contributor_type;

        return $this;
    }

    public function getFunction(): ?string
    {
        return $this->function;
    }

    public function setFunction(?string $function): self
    {
        $this->function = $function;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

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
            $linkContributorTopic->setContributor($this);
        }

        return $this;
    }

    public function removeLinkContributorTopic(LinkContributorTopic $linkContributorTopic): self
    {
        if ($this->link_contributor_topic->removeElement($linkContributorTopic)) {
            // set the owning side to null (unless already changed)
            if ($linkContributorTopic->getContributor() === $this) {
                $linkContributorTopic->setContributor(null);
            }
        }

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->short_description;
    }

    public function setShortDescription(?string $short_description): self
    {
        $this->short_description = $short_description;

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

    public function getIsContactable(): ?array
    {
        return $this->is_contactable;
    }

    public function setIsContactable(?array $is_contactable): self
    {
        $this->is_contactable = $is_contactable;

        return $this;
    }

    public function getIsContactableByEmail(): ?array
    {
        return $this->is_contactable_by_email;
    }

    public function setIsContactableByEmail(?array $is_contactable_by_email): self
    {
        $this->is_contactable_by_email = $is_contactable_by_email;

        return $this;
    }

    public function getIsContactableByPhone(): ?array
    {
        return $this->is_contactable_by_phone;
    }

    public function setIsContactableByPhone(?array $is_contactable_by_phone): self
    {
        $this->is_contactable_by_phone = $is_contactable_by_phone;

        return $this;
    }

    public function getEmailNotification(): ?array
    {
        return $this->email_notification;
    }

    public function setEmailNotification(?array $email_notification): self
    {
        $this->email_notification = $email_notification;

        return $this;
    }

    public function getParentContributor(): ?self
    {
        return $this->parent_contributor;
    }

    public function setParentContributor(?self $parent_contributor): self
    {
        $this->parent_contributor = $parent_contributor;

        return $this;
    }
}