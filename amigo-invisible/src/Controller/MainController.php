<?php

namespace App\Controller;

use App\Repository\GroupRepository;
use App\Repository\ParticipantRepository;
use App\Repository\RaffleRepository;
use App\Repository\UserGroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(ParticipantRepository $participantRepository, GroupRepository $groupRepository, UserGroupRepository $userGroupRepository): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'participants' => $participantRepository->findAll(),
            'groups' => $groupRepository->findAll(),
            'invitations' => $userGroupRepository->findAll(),
        ]);
    }
}
