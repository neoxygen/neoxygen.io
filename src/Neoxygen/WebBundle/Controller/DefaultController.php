<?php

namespace Neoxygen\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Neoxygen\WebBundle\Graph\BlogPost,
    Neoxygen\WebBundle\Graph\BlogPostQuery;
use Symfony\Component\Yaml\Tests\B;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
        $posts = BlogPostQuery::create()
            ->limit(3)
            ->match();
        return array(
            'posts' => $posts
        );
    }
}
