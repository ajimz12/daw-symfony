<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Raffle;
use App\Entity\User;
use App\Form\RaffleType;
use App\Repository\ParticipantRepository;
use App\Repository\RaffleRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/raffle')]
final class RaffleController extends AbstractController
{
    #[Route(name: 'app_raffle_index', methods: ['GET'])]
    public function index(RaffleRepository $raffleRepository): Response
    {
        return $this->render('raffle/index.html.twig', [
            'raffles' => $raffleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_raffle_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $raffle = new Raffle();
        $users = $userRepository->findAll();
        $form = $this->createForm(RaffleType::class, $raffle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $raffle->setDate(new DateTime());
            $raffle->setCreator($this->getUser());

            $entityManager->persist($raffle);
            $entityManager->flush();

            $asignaciones = $this->assignAddressee($users);

            foreach ($asignaciones as $userId => $addressee) {
                $user = $userRepository->find($userId);

                if ($user) {
                    $participante = new Participant();
                    $participante->setUser($user);
                    $participante->setRaffle($raffle);
                    $participante->setAddressee($addressee);
                    $participante->setNotified(true);

                    $entityManager->persist($participante);
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_raffle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('raffle/new.html.twig', [
            'raffle' => $raffle,
            'form' => $form,
        ]);
    }

    #[Route('/new/group', name: 'app_raffle_group_new', methods: ['GET', 'POST'])]
    public function newRaffleGroup(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $raffle = new Raffle();
        $users = $userRepository->findByGroup();
        $form = $this->createForm(RaffleType::class, $raffle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $raffle->setDate(new DateTime());
            $raffle->setCreator($this->getUser());

            $entityManager->persist($raffle);
            $entityManager->flush();

            $asignaciones = $this->assignAddressee($users);

            foreach ($asignaciones as $userId => $addressee) {
                $user = $userRepository->find($userId);

                if ($user) {
                    $participante = new Participant();
                    $participante->setUser($user);
                    $participante->setRaffle($raffle);
                    $participante->setAddressee($addressee);
                    $participante->setNotified(true);

                    $entityManager->persist($participante);
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_raffle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('raffle/new.html.twig', [
            'raffle' => $raffle,
            'form' => $form,
        ]);
    }



    // // Asignar destinatarios
    public function assignAddressee(array $users): array
    {
        shuffle($users);

        $assignments = [];

        foreach ($users as $index => $user) {
            $nextIndex = ($index + 1) % count($users);
            $assignments[$user->getId()] = $users[$nextIndex];
        }

        return $assignments;
    }


    #[Route('/{id}', name: 'app_raffle_show', methods: ['GET'])]
    public function show(Raffle $raffle, ParticipantRepository $participantRepository): Response
    {
        return $this->render('raffle/show.html.twig', [
            'raffle' => $raffle,
            'addressee' => $participantRepository->findByAddresseeIdByUser($this->getUser()),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_raffle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Raffle $raffle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RaffleType::class, $raffle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_raffle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('raffle/edit.html.twig', [
            'raffle' => $raffle,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_raffle_delete', methods: ['POST'])]
    public function delete(Request $request, Raffle $raffle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $raffle->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($raffle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_raffle_index', [], Response::HTTP_SEE_OTHER);
    }
}
