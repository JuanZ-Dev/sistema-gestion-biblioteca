<?php

namespace App\Controller;

use App\Entity\Multa;
use App\Form\MultaType;
use App\Repository\MultaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/multa')]
final class MultaController extends AbstractController
{
    #[Route(name: 'app_multa_index', methods: ['GET'])]
    public function index(MultaRepository $multaRepository): Response
    {
        return $this->render('multa/index.html.twig', [
            'multas' => $multaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_multa_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $multa = new Multa();
        $form = $this->createForm(MultaType::class, $multa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $multa->setEstado('pendiente');
            if ($multa->getPrestamo()->getFechaDevolucionReal() !== null) {
                $multa->setEstado('pagada');
            }
            $entityManager->persist($multa);
            $entityManager->flush();

            return $this->redirectToRoute('app_multa_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('multa/new.html.twig', [
            'multa' => $multa,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_multa_show', methods: ['GET'])]
    public function show(Multa $multa): Response
    {
        return $this->render('multa/show.html.twig', [
            'multa' => $multa,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_multa_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Multa $multa, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MultaType::class, $multa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($multa->getPrestamo()->getFechaDevolucionReal() !== null) {
                $multa->setEstado('pagada');

                $multa->getPrestamo()->getUsuario()->setEstado('activo');
//                $usuario = $multa->getPrestamo()->getUsuario();
//                $usuario?->setEstado('activo');
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_multa_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('multa/edit.html.twig', [
            'multa' => $multa,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_multa_delete', methods: ['POST'])]
    public function delete(Request $request, Multa $multa, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$multa->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($multa);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_multa_index', [], Response::HTTP_SEE_OTHER);
    }
}
