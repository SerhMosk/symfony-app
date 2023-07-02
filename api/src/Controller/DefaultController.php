<?php

namespace App\Controller;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class DefaultController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/', name: 'app_default')]
    public function index(): Response
    {
        $orders = $this->entityManager->getRepository(Order::class)->findAll();
//        dump($orders[0]->getProduct()->getTitle());die;
        return $this->render('default/index.html.twig', [
            'orders' => $orders,
        ]);
    }
}
