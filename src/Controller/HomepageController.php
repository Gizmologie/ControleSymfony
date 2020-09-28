<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{

    /*
     *  annotation
     *  utiliser uniquement des doubles guillemets
     *  commentaire lu par Symfony
     *  @Route : paramètres
     *      schéma de la route : url saisie
     *      name : nom unique de la route
     */

    private RequestStack $requestStack;
    private Request $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->request = $this->requestStack->getCurrentRequest();
    }

    /**
     * @Route("/", name="homepage.index")
     */
    public function index():Response
    {
        /*
         * Request :
         *      propriété request : $_POST
         *      propriété : $_GET
         */
        /*dd($this->request);
        $response = new Response("Coucou");
        return $response;
        */
        return $this->render('homepage/index.html.twig');
    }
}