<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\AnimalsRepository;
use App\Repository\SpectacleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function showLast(SpectacleRepository $spectacleRepository,  AnimalsRepository $animalsRepository): Response
    {
        return $this->render('Home/home.html.twig', [
            'spectacles' => $spectacleRepository->findByThreeLast(),
            'animals' => $animalsRepository->findByThreeLast(),
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     *
     */
    public function sendMessages(Request $request): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('admin_spectacle');
        }

        return $this->render('Contact/contact.html.twig', [
            'spectacle_category' => $message,
            'form' => $form->createView(),
        ]);
    }

}