<?php

namespace App\Controller;

use App\Entity\Post;
use Knp\Component\Pager\PaginatorInterface;
use \Knp\Component\Pager\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PopularController extends AbstractController
{

    public static function getSubscribedServices(): array
    {
        $services = parent::getSubscribedServices();
        $services['knp_paginator'] = PaginatorInterface::class;
        return $services;
    }


    /**
     * @Route("/popular/post", name="popular_post")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $paginator = $this->container->get('knp_paginator');

        $conn=$this->getDoctrine()->getConnection();
        $date = new \DateTime('7 days ago');
        $postRepo = $this->getDoctrine()->getRepository(Post::class);
        $posts = $postRepo->findAllPostsOrderByLoicesDesc();


        $result = $paginator->paginate(
            $posts,
            $request->query->get('page',1),
            10
        );


        return $this->render('popular_post/index.html.twig', [
            'controller_name' => 'PopularController',
            'date_filter' => $date,
            'posts' => $result]
        );
    }
}
