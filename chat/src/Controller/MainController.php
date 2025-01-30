<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Repository\ChatGroupRepository;

final class MainController extends AbstractController{
    #[Route('/', name: 'app_main')]
    public function index(ChatGroupRepository $chatGroupRepository): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'chat_groups' => $chatGroupRepository->findAll(),
        ]);
    }
}