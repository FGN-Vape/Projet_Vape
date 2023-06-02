<?php

namespace App\Controller;

use App\Entity\Order;
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
    public function shop(EntityManagerInterface $manager): Response
    {
        $lesProduits = $manager->getRepository(Product::class)->findAll();
        $panier = $manager->getRepository(Order::class)->findBy(['user' => $this->getUser()]);

        // Calculer la somme des prix des produits dans le panier
        $total = 0;
        foreach ($panier as $produit) {
            $total += $produit->getPrice()* $produit->getQuantity();
        }

        return $this->render('pages/shop.html.twig', [
            'produits' => $lesProduits,
            'total' => $total,
        ]);
    }

    #[Route('/produit/{id}', 'product.index', methods: ['GET', 'POST'])]
    public function product(EntityManagerInterface $manager, int $id): Response
    {
        // Récupérer le produit à partir de l'identifiant
        $produit = $manager->getRepository(Product::class)->find($id);

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
    #[Route('/ajoutPanier/{id}', 'ajoutPanier.index', methods: ['GET', 'POST'])]
    public function AjouterauPanier(EntityManagerInterface $manager, int $id): Response
    {
        $produit = $manager->getRepository(Product::class)->find($id);
        $this->denyAccessUnlessGranted("ROLE_USER");
        $order = new Order();
        $orderRepository = $manager->getRepository(Order::class);
        $existingOrder = $orderRepository->findOneBy([
            'user' => $this->getUser(),
            'product' => $produit,
        ]);

        if ($existingOrder) {
            // L'utilisateur a déjà commandé ce produit
            $order = $existingOrder->setQuantity($existingOrder->getQuantity() + 1);
        } else {
            $order->setUser($this->getUser())
                ->setProduct($produit)
                ->setIsValidated(0)
                ->setQuantity(1);
        }
        $this->addFlash(
            'success',
            'Votre article a été ajouté à votre panier'
        );
        $manager->persist($order);
        $manager->flush();
        return $this->redirectToRoute('shop.index');
    }

}
