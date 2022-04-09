<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Products;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/products")
*/
class ProductsController extends AbstractController
{

     /**
     * @Route("/", name="app_index")
     */
    public function index(ProductsRepository $repository, ManagerRegistry $doctrine, SerializerInterface $serializer): Response
    {
        $products = $repository->findAll();

        $jsonContent = $serializer->serialize($products , 'json');
        
        return new Response(
            $jsonContent
        );
    }

    /**
     * @Route("/stock", name="app_stock")
     */
    public function stock(ProductsRepository $repository, ManagerRegistry $doctrine, SerializerInterface $serializer): Response
    {
        $products = $repository->findHigherThan(0);    
        
        $jsonContent = $serializer->serialize($products , 'json');

        return new Response(
            $jsonContent
        );
    }

    /**
     * @Route("/lack", name="app_lack")
     */
    public function lack(ProductsRepository $repository, ManagerRegistry $doctrine, SerializerInterface $serializer): Response
    {
        $products = $repository->findEqual(0);    
        
        $jsonContent = $serializer->serialize($products , 'json');

        return new Response(
            $jsonContent
        );
    }

     /**
     * @Route("/more", name="app_more")
     */
    public function more(ProductsRepository $repository, ManagerRegistry $doctrine, SerializerInterface $serializer): Response
    {
        $products = $repository->findHigherThan(5);    
        
        $jsonContent = $serializer->serialize($products , 'json');

        return new Response(
            $jsonContent
        );
    }


    /**
     * @Route("/add", name="app_add")
     */
    public function add(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $product = new Products();

        $product->setName($request->get('name'));
        $product->setAmount($request->get('amount'));


        $entityManager->persist($product);
        $entityManager->flush();
        
        return new JsonResponse(
            ['status' => 1]
        );
    }

     /**
     * @Route("/edit/{id}", name="app_edit")
     */
    public function edit(Request $request,ManagerRegistry $doctrine, ProductsRepository $repository, $id, SerializerInterface $serializer)
    {
        $entityManager = $doctrine->getManager();

        $product = $repository->findById($id);    

        if ($request->isMethod('POST')) {

            $product->setName($request->get('name'));
            $product->setAmount($request->get('amount'));


            $entityManager->persist($product);
            $entityManager->flush();

            return new JsonResponse(
                ['status' => 1]
            );
        } else {
            $jsonContent = $serializer->serialize($product , 'json');

            return new Response(
                $jsonContent
            );
        }
    }

     /**
     * @Route("/delete/{id}", name="app_delete")
     */
    public function delete(Request $request, ProductsRepository $repository, $id): JsonResponse
    {
        $product = $repository->findById($id); 
        
        $repository->remove($product); 

        return new JsonResponse(
            ['status' => 1]
        );
    }
}
