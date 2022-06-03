<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/test", name="app_home")
     */
    public function test()
    {
     
        $donnee = "tata";
        $tab = ["prenom" => "Hocine", "nom" => "Boussaid" ];

        // afficher la valeur de la cle prenom du tableau $tab
        //echo $tab["prenom"];

        // render() permet de faire appel à un template qui se trouve dans le dossier templates
        return $this->render('test.html.twig', [ 
            "donnee" => $donnee, 
            "identite" => $tab
            ] );
    }

   /**
    * @Route("/", name="home")
    */
    public function home()
    {
        // On récupere le dernier Article en BDD
        $dernierArticle = $this->getDoctrine()->getRepository(Article::class)->findOneBy([], ["dateDeCreation" => "DESC"] );
        
        //on verifie le contenu de la variable
        // dd($dernierArticle);

        return $this->render('home/home.html.twig', [
            "dernierArticle" => $dernierArticle
        ]);

    }

}
