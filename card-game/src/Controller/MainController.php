<?php

namespace App\Controller;

use App\Repository\CardRepository;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(GameRepository $gameRepository): Response
    {

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'games' => $gameRepository->findAll(),
        ]);
    }
}
