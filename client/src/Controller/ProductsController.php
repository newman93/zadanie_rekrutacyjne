<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;


/**
* @Route("/products")
*/
class ProductsController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/", name="app_index")
     */
    public function index(): Response
    {

        $response = $this->client->request(
            'GET',
            'http://127.0.0.1:8000/products'
        );

        $products = json_decode($response->getContent(), true);

        return $this->render('products/index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/stock", name="app_stock")
     */
    public function stock(): Response
    {

        $response = $this->client->request(
            'GET',
            'http://127.0.0.1:8000/products/stock'
        );

        $products = json_decode($response->getContent());

        return $this->render('products/index.html.twig', [
            'products' => $products,
        ]);
    }


    /**
     * @Route("/lack", name="app_lack")
     */
    public function lack(): Response
    {

        $response = $this->client->request(
            'GET',
            'http://127.0.0.1:8000/products/lack'
        );

        $products = json_decode($response->getContent());

        return $this->render('products/index.html.twig', [
            'products' => $products,
        ]);
    }

      /**
     * @Route("/more", name="app_more")
     */
    public function more(): Response
    {

        $response = $this->client->request(
            'GET',
            'http://127.0.0.1:8000/products/more'
        );

        $products = json_decode($response->getContent());

        return $this->render('products/index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/add", name="app_add")
     */
    public function add(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $response = $this->client->request('POST', 'http://127.0.0.1:8000/products/add', [
                // defining data using a regular string
                'body' => ['name' => $request->get('name'), 'amount' => $request->get('amount')]
            ]);
            return $this->redirectToRoute('app_index');

        }

        return $this->render('products/add.html.twig', [
        ]);

    }

     /**
     * @Route("/edit/{id}", name="app_edit")
     */
    public function edit(Request $request, $id): Response
    {
        if ($request->isMethod('POST')) {
            $response = $this->client->request('POST', 'http://127.0.0.1:8000/products/edit/'.$id, [
                // defining data using a regular string
                'body' => [ 'name' => $request->get('name'), 'amount' => $request->get('amount')]
            ]);
            return $this->redirectToRoute('app_index');

        } else {
            $response = $this->client->request('GET', 'http://127.0.0.1:8000/products/edit/'.$id, [
            ]);
            $product = json_decode($response->getContent());

        }

        return $this->render('products/edit.html.twig', [
            'product' => $product
        ]);
    }

     /**
     * @Route("/delete/{id}", name="app_delete")
     */
    public function delete(Request $request,  $id): Response
    {
        $response = $this->client->request('GET', 'http://127.0.0.1:8000/products/delete/'.$id, [
        ]);

        return $this->redirectToRoute('app_index');
    }
}
