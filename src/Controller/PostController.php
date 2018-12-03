<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Like;
use App\Entity\Loice;
use App\Entity\Post;
use App\Entity\Tag;
use App\Form\CommentType;
use App\Entity\User;
use App\Form\LikeType;
use App\Form\LoiceType;
use App\Form\PostType;
use App\Utils\FileToImageConverter;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use \Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use App\Menu\Builder;

/**
 * @Route("/post")
 */
class PostController extends AbstractController
{

    public static function getSubscribedServices(): array
    {
        $services = parent::getSubscribedServices();
        $services['knp_paginator'] = PaginatorInterface::class;
        $services['knp_menu']=Builder::class;
        return $services;
    }



    /**
     * @Route("/", name="post_index", methods="GET")
     */
    public function index(PostRepository $postRepository,Request $request): Response
    {
        $paginator = $this->container->get('knp_paginator');

        /** @var PostRepository $repo */
        $repo = $this->getDoctrine()->getRepository(Post::class);

        $posts = $repo->findAllPostsOrderByCreatedDesc();

        $result = $paginator->paginate(
            $posts,
            $request->query->getInt('page',1),
            10
        );

        return $this->render('post/index.html.twig', ['posts' => $result]);

    }




    /**
     * @Route("/new", name="post_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        $now = new \DateTime();

        $user = $this->getDoctrine()->getRepository(User::class)->find(1);
        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted()) {
            $file = $request->files->get('post')['image'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $uploads_directory,
                $filename
            );
            $post->setCreated($now);
            $post->setUser($user);
            $post->setImage($filename);
            $em->persist($post);
            $em->flush();
            $smsg="The post has been published successfully!";
            echo $post->getUser()->getUsername();
            return $this->redirectToRoute('post_index');
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_show", methods="GET")
     */
    public function show(Post $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        $loiceForm = $this->createForm(LoiceType::class);
        $loiceForm->handleRequest($request);
        $commentForm = $this->createForm(CommentType::class);
        $commentForm->handleRequest($request);
        $em=$this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find(10 );
        $now = new \DateTime();
        $em = $this->getDoctrine()->getManager();
        $loice = $em->getRepository(Loice::class)->findOneBy(array('user' => $user, 'post' => $post));

        if($loiceForm->isSubmitted())
        {
            if($loice==null)
            {
                $loice = new Loice($user,$post);
                $em->persist($loice);
            }
            else{
                $em->remove($loice);
            }
            $em->flush();
            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }

        if($commentForm->isSubmitted())
        {
            /** @var Comment $comment */
            $comment=$commentForm->getData();
            $comment->setPost($post);
            $comment->setCreated($now);
            $comment->setUser($user);
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }

        $loiceCount = count($em->getRepository(Loice::class)->findBy(array('post'=>$post)));
        $commentRepo = $em->getRepository(Comment::class);
        $comments = $commentRepo->findBy(array('post'=>$post));

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'loice_count' => $loiceCount,
            'like_form' => $loiceForm->createView(),
            'comment_form' => $commentForm->createView(),
            'post_comments' => $comments
        ]);
    }

    /**
     * @Route("/{id}/edit", name="post_edit", methods="GET|POST")
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            $file = $request->files->get('post')['image'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $uploads_directory,
                $filename
            );
            $post->setImage($filename);
            $em=$this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();
            return $this->redirectToRoute('post_index', ['posts' => $posts]);
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="post_delete", methods="DELETE")
     */
    public function delete(Request $request, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();
        }

        return $this->redirectToRoute('post_index');
    }

    /**
     * @Route("/{user")
     * @param Request $request
     * @return Response
     */
    public function postAction(Request $request): Response
    {

    }




}
