<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostUserController extends AbstractController
{
    public static function getSubscribedServices(): array
    {
        $services = parent::getSubscribedServices();
        $services['knp_paginator'] = PaginatorInterface::class;
        return $services;
    }

    /**
     * @Route("/user/post", name="post_user")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request,User $user)
    {
        $paginator = $this->container->get('knp_paginator');
        $posts = $this->getDoctrine()->getRepository(Post::class)->findPostsOfUser($user);

        $result=$paginator->paginate(
            $posts,
            $request->query->getInt('page',1),
            10
        );

        return $this->render('user_post/index.html.twig', [
            'controller_name' => 'PostUserController',
            'posts' => $result
        ]);
    }
}
