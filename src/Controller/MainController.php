<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Room;
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
        $rooms = $em->getRepository(Room::class)->findAll();

        return $this->render('main/index.html.twig', [
            'rooms' => $rooms,
        ]);
    }

    #[Route('/room/{id}', name: 'app_room', methods: ['GET', 'POST'])]
    public function room(Room $room, EntityManagerInterface $em, Request $request): Response
    {
        $message = new Message();
      
        $user = $this->getUser();

        $contenido = $request->get('mensaje');
        
        if (empty(trim($contenido))) {
            $this->addFlash('error', 'El mensaje no puede estar vacío');
            // return $this->redirectToRoute('app_main');

        }else {
            $message->setDate(new \DateTime());
            $message->setUser($user); // Usa el objeto User completo
            $message->setContent($contenido);
            $message->setRoom($room);

            $em->persist($message);
            $em->flush();

        }

        return $this->render('main/room.html.twig', [
            'room' => $room,
        ]);
    }
}
