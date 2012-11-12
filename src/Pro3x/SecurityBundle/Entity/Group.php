<?php

namespace Pro3x\SecurityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * Group
 *
 * @ORM\Table(name="pro3x_groups")
 * @ORM\Entity
 */
class Group implements RoleInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
	
	/**
	 *
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private $name;
	
	/**
	 *
	 * @var string
	 * @ORM\Column(type="string", unique=true)
	 */
	private $role;
	
	/**
	 *
	 * @var User[]
	 * @ORM\ManyToMany(targetEntity="User", mappedBy="groups")
	 */
	private $users;

	function __construct($name = "", $role = "")
	{
		$this->users = new ArrayCollection();
		$this->name = $name;
		$this->role = $role;
	}

	    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

	public function getRole()
	{
		return $this->role;
	}
}
