<?php

namespace App\Controller;

use App\Entity\Article;
use DateTime;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("/articles", name="app_articles")
     */
    public function allArticles(): Response
    {
        // on recupere tous les articles en passant par la methode findAll() du repository lié à l'entité Article (ArticleRepository)
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        
        // dd() est une fonction qui me permet de faire du debug (voir le contenu d'une variable) puis arrête l'exécution
        //dd($articles); 

        return $this->render('article/allArticles.html.twig', [
            // la clé "articles" sera la variable twig a utiliser dans le template allArticles.html.twig pour accéder au contenu de la valeur $articles
            "articles" => $articles
        ] );
    }


    /**
     * {id} est le parametre qui receptionnera l'id de l'article sur lequel on clic
     * @Route("/article-{id}", name="app_article")
     */
    public function unArticle($id) // $id recupere automatiquement la valeur de {id} qui est dans la route
    {
        // on récupére l'article dont l'id est celui recuperé dans l'url (route) 
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        return $this->render('article/unArticle.html.twig', [
            "article" => $article
        ]);
    }



    /**
     * @Route("/ajout-article", name="ajout_article")
     */
    // on injecte en parametres de la fonction le manager et le request qu'on utilisera pendant le traitement
    public function ajout( EntityManagerInterface $manager, Request $request )
    {
        // on instancie un nouvel objet Article
        $article = new Article();

        // on crée un formulaire de type articleType en le liant à notre objet $article
        $form = $this->createForm(ArticleType::class, $article);

        // on donne accée aux données POST du formulaire et verifie si les saisies sont correctes
        $form->handleRequest($request);

        // si le formulaire est envoyé et que ses données sont valides 
        if( $form->isSubmitted() && $form->isValid() )
        {
            // on affecte la date de creation automatiquement à notre objet $article on utilisant le setDateCreation car $dateCreation est en private dans l'entité Article.php  
            $article->setDateDeCreation(new DateTime("now"));

            $manager->persist($article);

            $manager->flush();

            // une fois le formulaire envoyé on redirige vers la page des articles
            return $this->redirectToRoute('app_articles');

        }

        return $this->render('article/formulaire.html.twig', [
            'formArticle'=> $form->createView()
        ]);

    }


    /**
     * @Route("/modification-article_{id}", name="modification_article")
     */
        // on injecte en parametres de la fonction le manager et le request qu'on utilisera pendant le traitement
    public function modif( EntityManagerInterface $manager, Request $request, $id )
    {
        // on recupere l'article à modifier dont l'id est celui passé en parametre de la fonction qui vient de la route
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        // on crée un formulaire de type articleType en le liant à notre objet $article dans ce cas le formulaire sera pré-remplie avec les données de l'article recupéré
        $form = $this->createForm(ArticleType::class, $article);

        // on donne accée aux données POST du formulaire et verifie si les saisies sont correctes
        $form->handleRequest($request);

        // si le formulaire est envoyé et que ses données sont valides 
        if( $form->isSubmitted() && $form->isValid() )
        {
            // on affecte la date de creation automatiquement à notre objet $article on utilisant le setDateCreation car $dateCreation est en private dans l'entité Article.php  
            $article->setDateUpdate(new DateTime("now"));

            $manager->persist($article);

            $manager->flush();

            // une fois le formulaire envoyé on redirige vers la page des articles
            return $this->redirectToRoute('app_articles');

        }

        return $this->render('article/formulaire.html.twig', [
            'formArticle'=> $form->createView()
        ]);

    }





}
