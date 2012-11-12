<?php

namespace Pro3x\SecurityBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Pro3x\SecurityBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Pro3x\SecurityBundle\Entity\Group;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
	private $container;
	
	public function load(ObjectManager $manager)
	{
		$user = new User();
		$user->setUsername('user');
		$user->setEmail('info@mali-zeleni.hr');
		
		$encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
		$encodedPassword = $encoder->encodePassword('login', $user->getSalt());
		$user->setPassword($encodedPassword);
		
		$admin = new User();
		$admin->setUsername('admin');
		$admin->setEmail('info@pro3x.com');
		
		$encoder = $this->container->get('security.encoder_factory')->getEncoder($admin);
		$encodedPassword = $encoder->encodePassword('login', $admin->getSalt());
		$admin->setPassword($encodedPassword);
		
		$adminGroup = new Group('Administrators', 'ROLE_ADMIN');
		$userGroup = new Group('Users', 'ROLE_USER');
		
		$user->getGroups()->add($userGroup);
		
		$admin->getGroups()->add($userGroup);
		$admin->getGroups()->add($adminGroup);
		
		$manager->persist($adminGroup);
		$manager->persist($userGroup);

		$manager->persist($admin);
		$manager->persist($user);
		
		$manager->flush();
	}

	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}
}

?>
