<?php

namespace Pro3x\SecurityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Pro3x\SecurityBundle\Entity\User
 *
 * @ORM\Table(name="pro3x_users")
 * @ORM\Entity
 */
class User implements UserInterface, EquatableInterface, AdvancedUserInterface
{
	function __construct()
	{
		$this->isActive = true;
		$this->salt = md5(uniqid(null, true));
		$this->groups = new ArrayCollection();
	}

	
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

	public function eraseCredentials()
	{
		
	}
	
	/**
	 *
	 * @var User
	 * @ORM\ManyToMany(targetEntity="Group", inversedBy="users")
	 * @ORM\JoinTable(name="pro3x_users_groups")
	 */
	private $groups;
	
	/**
	 * 
	 * @return ArrayCollection
	 */
	public function getGroups()
	{
		return $this->groups;
	}

	/**
	 *
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private $password;
	
	public function getPassword()
	{
		return $this->password;
	}

	public function setPassword($password)
	{
		$this->password = $password;
	}
	
	public function getRoles()
	{
		return $this->groups->toArray();
	}

	/**
	 *
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private $salt;
	
	public function getSalt()
	{
		return $this->salt;
	}

	/**
	 *
	 * @var string
	 * @ORM\Column(type="string", unique=true)
	 */
	private $username;
	
	public function getUsername()
	{
		return $this->username;
	}
	
	public function setUsername($username)
	{
		$this->username = $username;
	}
	
	/**
	 *
	 * @var string
	 * @ORM\Column(type="string", unique=true)
	 */
	private $email;

	public function getEmail()
	{
		return $this->email;
	}
	
	public function setEmail($email)
	{
		$this->email = $email;
	}
	
	/**
	 *
	 * @var boolean
	 * @ORM\Column(type="boolean", name="is_active")
	 */
	private $isActive;
	
	public function isActive()
	{
		return $this->isActive;
	}

	public function setActive($isActive)
	{
		$this->isActive = $isActive;
	}

	/**
	 *
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $oib;
	
	public function getOib()
	{
		return $this->oib;
	}

	public function setOib($oib)
	{
		$this->oib = $oib;
	}

	public function isEqualTo(UserInterface $user)
	{
		return $this->username === $user->getUsername();
	}

	public function isAccountNonExpired()
	{
		return true;
	}

	public function isAccountNonLocked()
	{
		return true;
	}

	public function isCredentialsNonExpired()
	{
		return true;
	}

	public function isEnabled()
	{
		return $this->isActive;
	}
}
