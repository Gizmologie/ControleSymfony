<?php


namespace App\Controller\Profile;


use App\Entity\Gif;
use App\Form\GifType;
use App\Repository\GifRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\ByteString;

class HomepageController extends AbstractController
{

    private GifRepository $gifRepository;
    private RequestStack $requestStack;
    private Request $request;
    private EntityManagerInterface $entityManager;



    public function __construct(GifRepository $gifRepository, RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->gifRepository = $gifRepository;
        $this->requestStack = $requestStack;
        $this->request = $this->requestStack->getCurrentRequest();
    }

    /**
     * @Route("/profile", name ="profile.homepage.index")
     */
    public function index ():Response
    {
        $user = $this->getUser();


        $gifs = $this->gifRepository->getByUserId( $user->getId())->getResult();
        //dd($gifs);

        return $this->render('profile/homepage/index.html.twig', ['gifs' => $gifs]);
    }

    /**
     * @Route("/profile/form", name ="profile.homepage.form")
     */
    public function form ():Response
    {
        /*
         * Affichage d'un formulaire
         *      créer une classe de formulaire reliée à un modèle(entité ou classe): make:form
         *      dans la classe de formulaire, définir les types de champs
         *      dans les champs, définir les contraintes de validation
         *
         *  il est recommandé de supprimer les typages sur les propriétés liées aux images pour utiliser la classe UploadFile de Symfony
         */
        $model = new Gif();
        $type = GifType::class;

        // Création du formulaire
        $form = $this->createForm($type, $model);

        // Récupération de la saisie de la requête HTTP
        $form->handleRequest($this->request);

        // formulaire valide
        if($form->isSubmitted() && $form->isValid())
        {
            // associer un utilisateur au GIF
            $model->setUser($this->getUser());

            //gestion de l'image
            $imageName = ByteString::fromRandom(32)->lower();

            // guessExtension : méthode de UploadFile qui permet de récupérer l'extension du fichier
            $imageExtension = $model->getSource()->guessExtension();

            // move : méthode de UploadFile qui permet de transférer l'image
            $model->getSource()->move('gif', "$imageName.$imageExtension");

            $model
                ->setSlug("$imageName.$imageExtension")
                ->setSource("$imageName.$imageExtension")
                ;

            /*
             * Enregistrement en base de données:
             *      EntityManagerInterface qui permet les UPDATE, les INSERT, et les DELETE
             *      méthode persist : équivalent à INSERT, mise en file d'attente
             *      flush : execution des requêtes
             */
            $this->entityManager->persist($model);
            $this->entityManager->flush();


            // récupération du modèle de la classe
            //dd($model);
        }

        // méthode createView : permet de transcrire les propriétés du modèle en champs HTML
        return $this->render('profile/homepage/form.html.twig', [
            'form' => $form->createView()
        ]);
    }
}