<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', 'home.index', methods: ['GET'])]
        public function index(): Response
    {
        return $this->render('pages/home.html.twig');
    }
    #[Route('/shop', 'shop.index', methods: ['GET', 'POST'])]
        public function shop(): Response
    {
        return $this->render('pages/shop.html.twig');
    }
    #[Route('/produit', 'product.index', methods: ['GET', 'POST'])]
        public function product(EntityManagerInterface $manager): Response
    {
        // Récupérer l'identifiant du produit à partir de la base de données
        $produitId = 2;
        // Récupérer le produit à partir de l'identifiant
        $produit = $manager->getRepository(Product::class)->find($produitId);

        return $this->render('pages/product.html.twig', [
            'produit' => $produit,
        ]);
    }
    #[Route('/liste', 'list_products.index', methods: ['GET'])]
        public function liste(EntityManagerInterface $manager): Response
    {
        $lesProduits = $manager->getRepository(Product::class)->findAll();
        return $this->render('pages/list_products.html.twig', [
            'produits' => $lesProduits,
        ]);
    }
}
