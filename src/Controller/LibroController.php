<?php

namespace App\Controller;

use App\Entity\Libro;
use App\Form\LibroType;
use App\Repository\LibroRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/libro')]
final class LibroController extends AbstractController
{
    #[Route(name: 'app_libro_index', methods: ['GET'])]
    public function index(LibroRepository $libroRepository): Response
    {
        return $this->render('libro/index.html.twig', [
            'libros' => $libroRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_libro_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $libro = new Libro();
        $form = $this->createForm(LibroType::class, $libro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($libro);
            $entityManager->flush();

            return $this->redirectToRoute('app_libro_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('libro/new.html.twig', [
            'libro' => $libro,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_libro_show', methods: ['GET'])]
    public function show(Libro $libro): Response
    {
        return $this->render('libro/show.html.twig', [
            'libro' => $libro,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_libro_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Libro $libro, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LibroType::class, $libro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_libro_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('libro/edit.html.twig', [
            'libro' => $libro,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_libro_delete', methods: ['POST'])]
    public function delete(Request $request, Libro $libro, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$libro->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($libro);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_libro_index', [], Response::HTTP_SEE_OTHER);
    }
}
