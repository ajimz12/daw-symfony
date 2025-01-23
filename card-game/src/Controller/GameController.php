<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\CardRepository;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;


#[Route('/game')]
final class GameController extends AbstractController
{
    #[Route(name: 'app_game_index', methods: ['GET'])]
    public function index(GameRepository $gameRepository): Response
    {
        return $this->render('game/index.html.twig', [
            'games' => $gameRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_game_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CardRepository $cardRepository): Response
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($game);
            $entityManager->flush();

            return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
        }

        $allCards = $cardRepository->findAll();
        shuffle(array: $allCards);
        $cards = array_slice($allCards, 0, 4);

        return $this->render('game/new.html.twig', [
            'game' => $game,
            'form' => $form,
            'cards' => $cards,
        ]);
    }

    #[Route('/select', name: 'app_game_select', methods: ['POST'])]
    public function selectCard(
        Request $request,
        CardRepository $cardRepository,
        EntityManagerInterface $entityManager,
        Security $security
    ): Response {
        $currentUser = $security->getUser();

        $selectedCardIds = $request->get('cards', []);
        $selectedCards = $cardRepository->findBy(['id' => $selectedCardIds]);

        $game = $entityManager->getRepository(Game::class)->findOneBy(['status' => 'open']);

        if ($game) {
            $game->setPlayerTwo($currentUser);
            $game->setPlayerTwoCard($selectedCards[0]);
            $game->setPlayerTwoSecondCard($selectedCards[1]);
            $game->setStatus('closed');

            $this->checkResult($game);
        } else {
            $game = new Game();
            $game->setPlayerOne($currentUser);
            $game->setPlayerOneCard($selectedCards[0]);
            $game->setPlayerOneSecondCard($selectedCards[1]);
            $game->setStatus('open');
            $entityManager->persist($game);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_game_show', ['id' => $game->getId()]);
    }


    public function checkResult($game)
    {
        if ($game->getPlayerOneCard()->getNumber() > $game->getPlayerTwoCard()->getNumber()) {
            $game->setResult($game->getPlayerOne()->getUsername() . ' Ganador!');
            $game->setWinner($game->getPlayerOne());
        } elseif ($game->getPlayerOneCard()->getNumber() < $game->getPlayerTwoCard()->getNumber()) {
            $game->setResult($game->getPlayerTwo()->getUsername() . ' Ganador!');
            $game->setWinner($game->getPlayerTwo());
        } else {
            $game->setResult('Empate');
        }
    }


    #[Route('/{id}', name: 'app_game_show', methods: ['GET'])]
    public function show(Game $game, CardRepository $cardRepository): Response
    {
        $cards = $cardRepository->findAll();

        $playerOneCard = $game->getPlayerOneCard();
        $availableCards = [];
        foreach ($cards as $card) {
            if (!$playerOneCard || $card->getId() !== $playerOneCard->getId()) {
                $availableCards[] = $card;
            }
        }

        shuffle($availableCards);

        $availableCards = array_slice($availableCards, 0, 4);

        return $this->render('game/show.html.twig', [
            'game' => $game,
            'cards' => $availableCards,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_game_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Game $game, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('game/edit.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_game_delete', methods: ['POST'])]
    public function delete(Request $request, Game $game, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $game->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($game);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
    }
}
