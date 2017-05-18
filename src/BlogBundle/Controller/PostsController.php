<?php

namespace BlogBundle\Controller;

use BlogBundle\Entity\Post;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;
use JMS\Serializer\SerializationContext;
use Symfony\Component\HttpFoundation\Response;

class PostsController extends FOSRestController
{
    public function optionsPostsAction()
    {

    } // "options_posts" [OPTIONS] /posts

    public function getPostsAction()
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();

        $context = new SerializationContext();
        $context->setSerializeNull(true);

        $serializer = $this->get('jms_serializer');

        $response = new Response($serializer->serialize($posts, 'json', $context));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    } // "get_posts"     [GET] /posts

    public function newPostsAction()
    {} // "new_posts"     [GET] /posts/new

    public function postPostsAction()
    {} // "post_posts"    [POST] /posts

    public function patchPostsAction()
    {} // "patch_posts"   [PATCH] /posts
}
