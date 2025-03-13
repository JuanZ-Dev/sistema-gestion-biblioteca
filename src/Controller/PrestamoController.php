<?php

namespace App\Controller;

use App\Entity\Ejemplar;
use App\Entity\Prestamo;
use App\Form\PrestamoType;
use App\Repository\EjemplarRepository;
use App\Repository\PrestamoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/prestamo')]
final class PrestamoController extends AbstractController
{
    #[Route(name: 'app_prestamo_index', methods: ['GET'])]
    public function index(PrestamoRepository $prestamoRepository, EntityManagerInterface $entityManager): Response
    {
        // Obtener todos los préstamos no devueltos
        $prestamos = $entityManager->getRepository(Prestamo::class)->findBy(['estado' => 'activo']);

        // Obtener la fecha actual
        $hoy = new \DateTime();
        $update = false;

        // Verificar y actualizar estado de retraso
        foreach ($prestamos as $prestamo) {
            $fechaDevolucionPrevista = $prestamo->getFechaDevolucionPrevista();

            if ($hoy > $fechaDevolucionPrevista) {
                $prestamo->setEstado('retrasado');
                $prestamo->getUsuario()->setEstado('suspendido');
                $entityManager->persist($prestamo);
                $update = true;
            }
        }

        if ($update) $entityManager->flush();

        // Obtener todos los préstamos actualizados
//        $prestamos = $entityManager->getRepository(Prestamo::class)->findAll();
        return $this->render('prestamo/index.html.twig', [
            'prestamos' => $prestamoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_prestamo_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, EjemplarRepository $ejemplarRepository): Response
    {
        $prestamo = new Prestamo();
        $form = $this->createForm(PrestamoType::class, $prestamo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $libro = $form->get('libro')->getData();
            $ejemplar = $ejemplarRepository->findOneBy([
                'libro' => $libro,
                'estado' => 'disponible',
            ]);

            if ($ejemplar) {
                $prestamo->setEjemplar($ejemplar);
                $ejemplar->setEstado('prestado');
                $prestamo->setEstado('activo');
//                $prestamo->setFechaDevolucionReal(new \DateTime('0000-00-00'));
                $entityManager->persist($prestamo);
                $entityManager->flush();

                return $this->redirectToRoute('app_prestamo_index', [], Response::HTTP_SEE_OTHER);
            } else {
                $this->addFlash('error', 'No hay ejemplares disponibles para este libro.');
            }
        }

        return $this->render('prestamo/new.html.twig', [
            'prestamo' => $prestamo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_prestamo_show', methods: ['GET'])]
    public function show(Prestamo $prestamo): Response
    {
        return $this->render('prestamo/show.html.twig', [
            'prestamo' => $prestamo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_prestamo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Prestamo $prestamo, EntityManagerInterface $entityManager): Response
    {
        $ejemplar = new Ejemplar();
        $form = $this->createForm(PrestamoType::class, $prestamo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($prestamo->getFechaDevolucionReal() !== null) {
                $prestamo->setEstado('devuelto');
                $ejemplar = $prestamo->getEjemplar();
                $ejemplar?->setEstado('disponible');

//                $usuario = $prestamo->getUsuario();
//                $usuario?->setEstado('activo');
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_prestamo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prestamo/edit.html.twig', [
            'prestamo' => $prestamo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_prestamo_delete', methods: ['POST'])]
    public function delete(Request $request, Prestamo $prestamo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prestamo->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($prestamo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_prestamo_index', [], Response::HTTP_SEE_OTHER);
    }
}
