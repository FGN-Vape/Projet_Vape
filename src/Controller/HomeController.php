<?php

namespace App\Controller;

use App\Entity\Date;
use App\Entity\User;
use App\Entity\Order;
use DateTimeImmutable;
use App\Entity\Product;
use App\Entity\Type;
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
        $user = $this->getUser();
        $orders = $manager->getRepository(Order::class)->findBy(['user' => $user]);

        $quantityItems = 0;
        $total = 0;

        foreach ($orders as $order) {
            if ($order->getIsValidated() == 0) {
                $quantityItems += $order->getQuantity();
                $total += $order->getProduct()->getPrice() * $order->getQuantity();
            }
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
        $user = $this->getUser();
        $orders = $manager->getRepository(Order::class)->findBy(['user' => $user]);

        $quantityItems = 0;
        $total = 0;

        foreach ($orders as $order) {
            if ($order->getIsValidated() == 0) {
                $quantityItems += $order->getQuantity();
                $total += $order->getProduct()->getPrice() * $order->getQuantity();
            }
        }
        return $this->render('pages/list_products.html.twig', [
            'produits' => $lesProduits,
            'total' => $total,

        ]);
    }
    #[Route('/produits/{type}', name: 'product_list_by_type')]
    public function listByType(string $type, EntityManagerInterface $manager): Response
    {
        $typeEntity = $manager->getRepository(Type::class)->findOneBy(['NameType' => $type]);

        if (!$typeEntity) {
            throw $this->createNotFoundException('Type not found');
        }

        $lesProduits = $manager->getRepository(Product::class)->findBy(['Type' => $typeEntity]);
        $user = $this->getUser();
        $orders = $manager->getRepository(Order::class)->findBy(['user' => $user]);

        $quantityItems = 0;
        $total = 0;

        foreach ($orders as $order) {
            if ($order->getIsValidated() == 0) {
                $quantityItems += $order->getQuantity();
                $total += $order->getProduct()->getPrice() * $order->getQuantity();
            }
        }

        return $this->render('pages/list_products.html.twig', [
            'produits' => $lesProduits,
            'total' => $total,
            'type' => $typeEntity,
        ]);
    }
    #[Route('/produits/marque/{brand}', name: 'product_list_by_brand')]
public function listByBrand(string $brand, EntityManagerInterface $manager): Response
{
    $brandEntity = $manager->getRepository(Product::class)->findOneBy(['Brand' => $brand]);

    if (!$brandEntity) {
        throw $this->createNotFoundException('Brand not found');
    }

    $lesProduits = $manager->getRepository(Product::class)->findBy(['Brand' => $brandEntity]);
    $user = $this->getUser();
    $orders = $manager->getRepository(Order::class)->findBy(['user' => $user]);

    $quantityItems = 0;
    $total = 0;

    foreach ($orders as $order) {
        if ($order->getIsValidated() == 0) {
            $quantityItems += $order->getQuantity();
            $total += $order->getProduct()->getPrice() * $order->getQuantity();
        }
    }

    return $this->render('pages/list_products.html.twig', [
        'produits' => $lesProduits,
        'total' => $total,
        'brand' => $brandEntity,
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
            'isValidated' => 0,
        ]);

        if ($existingOrder) {
            // L'utilisateur a déjà commandé ce produit
            $order = $existingOrder->setQuantity($existingOrder->getQuantity() + 1);
        } else {
            // Créer une nouvelle instance de Date
            $date = new Date();
            $dateactuelle = new DateTimeImmutable();
            $date->setDatetime($dateactuelle);
            $manager->persist($date);

            $order->setUser($this->getUser())
                ->setProduct($produit)
                ->setIsValidated(0)
                ->setQuantity(1)
                // Lier la nouvelle date à l'objet Order
                ->setDate($date);
        }
        $this->addFlash(
            'success',
            'Votre article a été ajouté à votre panier'
        );
        $manager->persist($order);
        $manager->flush();
        return $this->redirectToRoute('shop.index');
    }

    #[Route('/panier', 'cart.index', methods: ['GET', 'POST'])]
    public function panier(EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $orders = $manager->getRepository(Order::class)->findBy(['user' => $user]);

        $quantityItems = 0;
        $total = 0;

        foreach ($orders as $order) {
            if ($order->getIsValidated() == 0) {
                $quantityItems += $order->getQuantity();
                $total += $order->getProduct()->getPrice() * $order->getQuantity();
            }
        }

        return $this->render('pages/cart.html.twig', [
            'orders' => $orders,
            'quantityItems' => $quantityItems,
            'total' => $total,
        ]);
    }
    #[Route('/toCommand', 'tocommand.index', methods: ['GET', 'POST'])]
    public function tocommand(EntityManagerInterface $manager): Response
    {
        $panier = $manager->getRepository(Order::class)->findBy(['user' => $this->getUser()]);
        $this->denyAccessUnlessGranted("ROLE_USER");
        $order = new Order();
        $date = new Date();
        $dateactuelle = new DateTimeImmutable();
        $date->setDatetime($dateactuelle);
        $manager->persist($date);

        $user = $this->getUser();
        foreach ($panier as $order) {
            $order->setUser($user)
                ->setProduct($order->getProduct())
                ->setIsValidated(1)
                ->setDate($date);
        }

        $this->addFlash(
            'success',
            'Votre commande est passée, merci pour votre achat'
        );
        $total = 0;
        $manager->persist($order);
        $manager->flush();
        return $this->redirectToRoute('home.index', [
            'total' => $total,
        ]);
    }
    #[Route('/commands', 'commands.index', methods: ['GET'])]
    public function commands(EntityManagerInterface $manager): Response
    {
        $panier = $manager->getRepository(Order::class)->findBy(['user' => $this->getUser()]);
        $this->denyAccessUnlessGranted("ROLE_USER");
        $produits = [];
        $quantityItems = 0;
        $total = 0;

        foreach ($panier as $order) {
            if ($order->getIsValidated() == 1) {
                $produits[] = $order->getProduct();
                $quantityItems += $order->getQuantity(); // Ajouter la quantité de chaque produit à la quantité totale
                $total += $order->getProduct()->getPrice() * $order->getQuantity();
            }
        }

        return $this->render('pages/commands.html.twig', [
            'produits' => $produits,
            'orders' => $panier,
            'total' => $total,
            'quantityItems' => $quantityItems,
        ]);
    }
    #[Route('/profil', 'profil.index', methods: ['GET'])]
    public function profile(EntityManagerInterface $manager): Response
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        $user = $manager->getRepository(User::class)->findBy(['id' => $this->getUser()]);
        return $this->render('pages/profile.html.twig', [
            'User' => $user,
        ]);
    }
}
