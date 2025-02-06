<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\UserGroup;
use App\Form\GroupType;
use App\Repository\GroupRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/group')]
final class GroupController extends AbstractController
{
    #[Route(name: 'app_group_index', methods: ['GET'])]
    public function index(GroupRepository $groupRepository): Response
    {
        return $this->render('group/index.html.twig', [
            'groups' => $groupRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_group_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $group->setCreator($this->getUser());
            $entityManager->persist($group);
            $entityManager->flush();

            return $this->redirectToRoute('app_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('group/new.html.twig', [
            'group' => $group,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/invite', name: 'app_group_invite', methods: ['POST'])]
    public function invite(Request $request, Group $group, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {

        $userId = $request->request->get('user_id');
        $user = $userRepository->find($userId);

        $groupUser = new UserGroup();
        $groupUser->setGrupo($group);
        $groupUser->setUser($user);
        $groupUser->setStatus("pendiente");

        $entityManager->persist($groupUser);
        $entityManager->flush();

        return $this->redirectToRoute('app_group_show', ['id' => $group->getId()]);
    }

    #[Route('/{id}/accept', name: 'app_group_accept', methods: ['GET'])]
    public function accept(Group $group, EntityManagerInterface $entityManager): Response
    {
        $groupUser = $entityManager->getRepository(UserGroup::class)
            ->findOneBy(['grupo' => $group, 'user' => $this->getUser()]);

        $groupUser->setStatus("aceptado");
        $entityManager->flush();

        return $this->redirectToRoute('app_group_show', ['id' => $group->getId()]);
    }

    #[Route('/{id}/reject', name: 'app_group_reject', methods: ['GET'])]
    public function reject(Group $group, EntityManagerInterface $entityManager): Response
    {
        $groupUser = $entityManager->getRepository(UserGroup::class)
            ->findOneBy(['grupo' => $group, 'user' => $this->getUser()]);

        $groupUser->setStatus("rechazado");
        $entityManager->flush();

        return $this->redirectToRoute('app_group_show', ['id' => $group->getId()]);
    }

    #[Route('/{id}', name: 'app_group_show', methods: ['GET'])]
    public function show(Group $group, UserRepository $userRepository): Response
    {
        return $this->render('group/show.html.twig', [
            'group' => $group,
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_group_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Group $group, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('group/edit.html.twig', [
            'group' => $group,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_group_delete', methods: ['POST'])]
    public function delete(Request $request, Group $group, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $group->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($group);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_group_index', [], Response::HTTP_SEE_OTHER);
    }
}
