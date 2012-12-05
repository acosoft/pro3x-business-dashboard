<?php

namespace Pro3x\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;
use Pro3x\SecurityBundle\Entity\User;

class DefaultController extends Controller
{
	/**
	 * @Route("/register", name="register")
	 * @Template()
	 */
	public function registerAction()
	{
		$form = $this->createFormBuilder(null, array('attr' => array('class' => 'pro3x_form_content')))
				->setAttribute('attr', array('class' => 'pro3x_form_content'))
				->add('username', 'text', array('label' => 'Korisničko ime'))
				->add('email', 'email', array('label' => 'Email'))
				->add('password', 'repeated', array(
					'first_name' => 'password', 
					'first_options' => array('label' => 'Zaporka'), 
					'second_name' => 'confirm_password', 
					'second_options' => array('label' => 'Ponovite zaporku'),
					'type' => 'password'))
				->getForm();
		
		if($this->getRequest()->isMethod('post'))
		{
			/* @var $form \Symfony\Component\Form\Form */
			$form->bind($this->getRequest());
			
			if($form->isValid())
			{
				$user = new User();
				$data = $form->getData();
				
				$encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
				$encodedPassword = $encoder->encodePassword($data['password'], $user->getSalt());
				$user->setPassword($encodedPassword);
				
				$user->setEmail($data['email']);
				$user->setUsername($data['username']);
				
				$manager = $this->getDoctrine()->getManager();
				$userGroup = $manager->getRepository('Pro3xSecurityBundle:Group')->findOneBy(array('role' => 'ROLE_USER'));
				$user->getGroups()->add($userGroup);
				
				$manager->persist($user);
				$manager->flush();
				
				return $this->redirect($this->generateUrl('dashboard'));
			}
		}
		
		return array('register' => $form->createView());
	}
	
	/**
     * @Route("/login", name="login")
     * @Template()
     */
	public function loginAction()
	{
		if ($this->get('request')->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->get('request')->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $this->get('request')->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
            'last_username' => $this->get('request')->getSession()->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        );
	}
	
	/**
	 * @todo Delete Acme bundle and change logout pattern
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
    }
}
