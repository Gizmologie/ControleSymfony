<?php

namespace App\Controller;

use App\Repository\GifRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GifController extends AbstractController
{
    private GifRepository $gifRepository;

    public function __construct(GifRepository $gifRepository)
    {
        $this->gifRepository = $gifRepository;
    }

    /**
     * @Route("gif/{slug}", name="gif.index")
     */
    public function index(string $slug):Response
    {
        $gif = $this->gifRepository->findOneBy(
            ['slug' => $slug]
        );

        return $this->render('gif/index.html.twig', [
           'gif' => $gif,
        ]);
    }
}
