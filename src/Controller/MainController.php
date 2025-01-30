<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]

    public function index(EntityManagerInterface $em): Response
    {
        
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);

        $messages = $em->getRepository(Message::class)
        ->findBy([], ['date' => 'DESC'], 50);

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',   
            'form' => $form->createView(),
            'messages' => $messages,
        ]);
    }
}
