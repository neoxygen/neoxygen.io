<?php

namespace Neoxygen\WebBundle\Graph\Base;

use Neoxygen\AppBundle\Graph\BaseNode;
use Neoxygen\NeoClient\Formatter\Node;

class BaseBlogPost extends BaseNode
{
    const LABEL = 'NeoxygenWebBundleBlogPost';

    private $id;

    private $title;

    private $body;

    private $neoId;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    public function deserialize(Node $node)
    {
        $this->neoId = $node->getId();
        $this->id = $node->getProperty('_id');
        $this->title = $node->getProperty('title');
        $this->body = $node->getProperty('body');

        return $this;
    }

    public function save()
    {
        $node = $this->serialize();
        $q = 'MERGE (neoxygen_web_bundle_blog_post:'.self::LABEL.' {_id: {props}._id }) ';
        if (!empty($node['properties'])){
            $q .= 'SET ';
            $x = 0;
            foreach ($node['properties'] as $key => $v){
                $q .= 'neoxygen_web_bundle_blog_post.'.$key.' = {props}.'.$key;
                if ($x < count($node['properties']) -1){
                    $q .= ', ';
                }
                $x++;
            }
        }
        $q .= ';';
        $props = [
            'props' => [
                '_id' => $node['_id'],
                'title' => $node['properties']['title'],
                'body' => $node['properties']['body']
            ]
        ];
        $response = $this->getConnection()->sendCypherQuery($q, $props);

    }

    private function serialize()
    {
        $node = [
            'label' => self::LABEL,
            '_id' => $this->id,
            'properties' => [
                'title' => $this->title,
                'body' => $this->body
            ]
        ];

        return $node;
    }
}