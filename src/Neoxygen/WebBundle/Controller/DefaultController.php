<?php

namespace Neoxygen\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Neoxygen\WebBundle\Entity\BlogPost;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('NeoxygenWebBundle:BlogPost')
            ->findBy(
                array(),
                array('created' => 'DESC'),
                3
            );
        $releases = $em->getRepository('NeoxygenWebBundle:Release')
            ->findBy(
                array(),
                array('created' => 'DESC'),
                3
            );
        return array(
            'posts' => $posts,
            'releases' => $releases
        );
    }

    /**
     * @Route("/components", name="components")
     * @Template()
     */
    public function componentsAction()
    {
        return array();
    }
}
