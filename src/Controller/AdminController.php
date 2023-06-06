<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\AddProductType;
use App\Form\UpdateProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminController extends AbstractController
{
    private $slugger;
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(EntityManagerInterface $manager): Response
    {
        $lesProduits = $manager->getRepository(Product::class)->findAll();
        return $this->render('pages/admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'produits' => $lesProduits
        ]);
    }

    #[Route('/admin/ajoutProduit', name: 'ajoutProduit')]
    public function ajoutProduit(EntityManagerInterface $manager, Request $request): Response
    {
        $product = new Product();
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        $form = $this->createForm(AddProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $product = $form->getData();
            $uploadedFile = $form['img']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/img';
            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename =  $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();

            $uploadedFile->move(
                $destination,
                $newFilename
            );
            $product->setImg('/img/'.$newFilename);
            $this->addFlash(
                'success',
                'Votre Produit a bien été créé'
            );

            $manager->persist($product);
            $manager->flush();

            return $this->redirectToRoute('list_products.index');
        }

        return $this->render('pages/admin/ajoutProduit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/remove/{id}', name: 'removeProduct')]
    public function removeProduct(EntityManagerInterface $manager, int $id): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        $produit = $manager->getRepository(Product::class)->findOneBy(["id" => $id]);
        $manager->remove($produit);
        $manager->flush();
        $this->addFlash(
            'success',
            'Votre Produit a bien été supprimé'
        );
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/admin/update/{id}', name: 'updateProduct')]
    public function updateProduct(EntityManagerInterface $manager, int $id, Request $request): Response
    {
        $produit = $manager->getRepository(Product::class)->findOneBy(["id" => $id]);
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        $form = $this->createForm(UpdateProductType::class, $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form-> isValid()) {
            $produit = $form->getData();
            $manager->persist($produit);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre Produit a été modifié avec succès'
            );

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('admin/updateProduct.html.twig', [
            'form' =>$form->createView()
        ]);
    }
}
