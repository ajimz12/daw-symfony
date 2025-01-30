<?php

namespace App\Controller;

use App\Entity\ChatGroup;
use App\Form\ChatGroupType;
use App\Repository\ChatGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/chat/group')]
final class ChatGroupController extends AbstractController
{
    #[Route(name: 'app_chat_group_index', methods: ['GET'])]
    public function index(ChatGroupRepository $chatGroupRepository): Response
    {
        return $this->render('chat_group/index.html.twig', [
            'chat_groups' => $chatGroupRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_chat_group_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $chatGroup = new ChatGroup();
        $form = $this->createForm(ChatGroupType::class, $chatGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($chatGroup);
            $chatGroup->setCreador($this->getUser());
            $entityManager->flush();

            return $this->redirectToRoute('app_chat_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chat_group/new.html.twig', [
            'chat_group' => $chatGroup,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/join', name: 'app_chat_group_join', methods: ['POST'])]
    public function joinUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        $id = $request->get('id');
        $chatGroup = $entityManager->getRepository(ChatGroup::class)->find($id);
        $chatGroup->addUsuario($this->getUser());
        $entityManager->flush();
        return $this->redirectToRoute('app_chat_group_index', [], Response::HTTP_SEE_OTHER);
    }

    // #[Route('/{id}/close', name: 'app_chat_group_close', methods: ['POST'])]
    // public function closeGroup(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $id = $request->get('id');
    //     $chatGroup = $entityManager->getRepository(ChatGroup::class)->find($id);
    //     $chatGroup->setAbierto(false);
    //     $entityManager->flush();
    //     return $this->redirectToRoute('app_chat_group_index', [], Response::HTTP_SEE_OTHER);
    // }

    #[Route('/{id}', name: 'app_chat_group_show', methods: ['GET'])]
    public function show(ChatGroup $chatGroup): Response
    {
        return $this->render('chat_group/show.html.twig', [
            'chat_group' => $chatGroup,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chat_group_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ChatGroup $chatGroup, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ChatGroupType::class, $chatGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_chat_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('chat_group/edit.html.twig', [
            'chat_group' => $chatGroup,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_chat_group_delete', methods: ['POST'])]
    public function delete(Request $request, ChatGroup $chatGroup, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $chatGroup->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($chatGroup);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_chat_group_index', [], Response::HTTP_SEE_OTHER);
    }
}
