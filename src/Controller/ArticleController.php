<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Validator\ConstraintViolationList;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;


class ArticleController extends FOSRestController
{
    /**
     * @Rest\Get(
     *      path = "/articles/{id}",
     *      name = "app_article_show",
     *      requirements = {"id" = "\d+"}
     * )
     * 
     * @Rest\View()
     * 
     * @SWG\Response(
     *      response=200,
     *      description="Get one artcile",
     *      @Model(type=Article::class)
     * )
     * @SWG\Parameter(
     *      name="id",
     *      in="query",
     *      type="integer",
     *      description="The article unique identifier"
     * )
     */
    public function show(Article $article)
    {
        return $article;
    }

    /**
     * @Rest\Get(
     *      path = "/articles",
     *      name = "app_articles_list"
     * )
     * @Rest\View()
     */
    public function list(ArticleRepository $articleRepo)
    {
        $articles = $articleRepo->findAll();

        return $articles;
    }
    
    /**
     * @Rest\Post(
     *      path = "/articles/create",
     *      name = "app_article_create"
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("article", converter="fos_rest.request_body")
     */
    public function create(Article $article, ObjectManager $manager, ConstraintViolationList $violations)
    {
        if(count($violations) > 0)
        {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }

        $manager->persist($article);
        $manager->flush();

        return $article;
    }

    /**
     * @Rest\Put(
     *      path = "/articles/{id}/update",
     *      name = "app_article_update",
     *      requirements = {"id" = "\d+"}
     * )
     * @Rest\View(StatusCode = 201)
     * @ParamConverter("articleupdated", converter="fos_rest.request_body")
     */
    public function update(Article $articleupdated, Article $article, ObjectManager $manager, ArticleRepository $articleRepo, ConstraintViolationList $violations)
    {
        if(count($violations) > 0)
        {
            return $this->view($violations, Response::HTTP_BAD_REQUEST);
        }
        
        $article->setTitle($articleupdated->getTitle());
        $article->setContent($articleupdated->getContent());

        $manager->flush();

        return $article;
    }

    /**
     * @Rest\Delete(
     *      path = "/articles/{id}/delete",
     *      name = "app_article_delete",
     *      requirements = {"id" = "\d+"}
     * )
     */
    public function delelte(Article $article,ObjectManager $manager)
    {
        $manager->remove($article);
        $manager->flush();
        
        return $this->view("Article supprimé", 200);
    }
}
