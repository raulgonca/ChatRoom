<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        // Obtener mensajes ordenados por fecha
        $messages = $em->getRepository(Message::class)
            ->findBy([], ['date' => 'DESC'], 50);
        
        $user = $this->getUser();

        // Procesar el formulario
        if ($form->isSubmitted() && $form->isValid()) {
            // Asignar fecha y usuario automáticamente
            $message->setDate(new \DateTime());
            $message->setUser($user); // Usa el objeto User completo

            $em->persist($message);
            $em->flush();

            // Redirigir para evitar reenvíos del formulario
            return $this->redirectToRoute('app_main');
        }

        return $this->render('main/index.html.twig', [
            'form' => $form->createView(),
            'messages' => $messages,
        ]);
    }
}
