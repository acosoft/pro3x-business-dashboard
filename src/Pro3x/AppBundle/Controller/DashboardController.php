<?php

namespace Pro3x\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin")
 */
class DashboardController extends Controller
{
    /**
     * @Route("/", name="dashboard")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}
