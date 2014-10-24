<?php

namespace Neoxygen\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('NeoxygenAppBundle:Default:index.html.twig', array('name' => $name));
    }
}
