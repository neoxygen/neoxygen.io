<?php

namespace Neoxygen\WebBundle\Graph;

use Neoxygen\AppBundle\Service\Neo4jService;
use Neoxygen\NeoClient\Formatter\ResponseFormatter;
use Neoxygen\WebBundle\Graph\BlogPost;

class BlogPostQuery
{
    const LABEL = 'NeoxygenWebBundleBlogPost';

    const IDENTIFIER = 'neoxygen_web_bundle_blog_post';

    private $queryParts = [];

    private $filters = [];

    private $limit;

    public static function create()
    {
        return new self;
    }

    public function match()
    {
        $q = 'MATCH ('.self::IDENTIFIER.':'.self::LABEL.') ';
        if (!empty($this->filters)){
            $q .= 'WHERE ';
            $i = 0;
            foreach ($this->filters as $key => $filter){
                $q .= self::IDENTIFIER.'.'.$filter['key'].' = {'.$filter['key'].'}';
                if ($i < count($this->filters) -1){
                    $q .= ' AND WHERE ';
                }
            }
        }
        $q .= ' RETURN '.self::IDENTIFIER;
        if ($this->limit){
            $q .= ' LIMIT '.$this->limit;
        }
        $q .= ';';
        $props = [];
        foreach ($this->filters as $key => $filter){
            $props[$filter['key']] = $filter['params'];
        }

        $conn = Neo4jService::getNeo4jConnection();
        $response = $conn->sendCypherQuery($q, $props, null, array('graph'));

        $formatter = new ResponseFormatter();
        $results = $formatter->format($response);

        $blogPosts = [];
        foreach ($results->getNodesByLabel(self::LABEL) as $node){
            $blogPost = new BlogPost();
            $blogPost->deserialize($node);
            $blogPosts[] = $blogPost;
        }

        return $blogPosts;
    }

    public function filterByTitle($filter)
    {
        $this->filters[] = [
            'key' => 'title',
            'params' => $filter
        ];

        return $this;
    }

    public function filterById($id)
    {
        $this->filters[] = [
            'key' => '_id',
            'params' => $id
        ];

        return $this;
    }

    public function limit($limit)
    {
        $this->limit = (int) $limit;

        return $this;
    }

}