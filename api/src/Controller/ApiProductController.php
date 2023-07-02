<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ApiProductController extends AbstractController
{
    /**
     * @param ProductRepository $productRepository
     * @return JsonResponse
     * @Route("/api/product", name="api_products", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        $data = $productRepository->findAll();
        return $this->json($data);
    }

    /**
     * @param ProductRepository $productRepository
     * @param $id
     * @return JsonResponse
     * @Route("/api/product/{id}", name="api_product", methods={"GET"})
     */
    public function getProduct(ProductRepository $productRepository, $id){
        $product = $productRepository->find($id);

        if (!$product){
            $data = [
                'status' => 404,
                'errors' => "Product not found",
            ];
            return $this->json($data, 404);
        }
        return $this->json($product);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ProductRepository $productRepository
     * @return JsonResponse
     * @throws \Exception
     * @Route("/api/product", name="api_products_add", methods={"POST"})
     */
    public function addProduct(Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository)
    {
        try {
            $requestData = json_decode($request->getContent(), true);

            if (!isset($requestData['title']) || !isset($requestData['price'])) {
                throw new \Exception('Invalid data');
            }

            $title = $requestData['title'];
            $price = $requestData['price'];

            if (strlen($title) < 2) {
                throw new \Exception('Title should be at least 2 characters long');
            }

            if ($price <= 0) {
                throw new \Exception('Price should be greater than zero');
            }

            $product = new Product();
            $product->setTitle($title);
            $product->setPrice($price);

            $entityManager->persist($product);
            $entityManager->flush();

            $data = [
                'status' => 200,
                'success' => 'Product added successfully',
            ];
            return $this->json($data);

        } catch (\Exception $e) {
            $data = [
                'status' => 422,
                'errors' => $e->getMessage(),
            ];
            return $this->json($data, 422);
        }
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ProductRepository $productRepository
     * @param OrderRepository $orderRepository
     * @param int $id
     * @return JsonResponse
     * @throws \Exception
     * @Route("/api/product/{id}", name="api_products_update", methods={"PUT", "PUTCH"})
     */
    public function updateProduct(Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository, OrderRepository $orderRepository, int $id)
    {
        try {
            $requestData = json_decode($request->getContent(), true);

            if (!isset($requestData['title']) || !isset($requestData['price'])) {
                throw new \Exception('Invalid data');
            }

            $title = $requestData['title'];
            $price = $requestData['price'];

            if (strlen($title) < 2) {
                throw new \Exception('Title should be at least 2 characters long');
            }

            if ($price <= 0) {
                throw new \Exception('Price should be greater than zero');
            }

            $product = $productRepository->find($id);

            if (!$product) {
                throw new \Exception('Product not found');
            }

            $product->setTitle($title);
            $product->setPrice($price);

            $entityManager->flush();

            $data = [
                'status' => 200,
                'success' => 'Product updated successfully',
            ];
            return $this->json($data);

        } catch (\Exception $e) {
            $data = [
                'status' => 422,
                'errors' => $e->getMessage(),
            ];
            return $this->json($data, 422);
        }
    }

    /**
     * @param Product $product
     * @param EntityManagerInterface $entityManager
     * @param OrderRepository $orderRepository
     * @return JsonResponse
     * @throws \Exception
     * @Route("/api/product/{id}", name="api_product_delete", methods={"DELETE"})
     */
    public function deleteProduct(Product $product, EntityManagerInterface $entityManager, OrderRepository $orderRepository)
    {
        try {
            $order = $orderRepository->findOneBy(['product' => $product]);

            if ($order) {
                throw new \Exception('Product cannot be deleted because it is associated with an order');
            }

            $entityManager->remove($product);
            $entityManager->flush();

            $data = [
                'status' => 200,
                'success' => 'Product deleted successfully',
            ];
            return $this->json($data);

        } catch (\Exception $e) {
            $data = [
                'status' => 422,
                'errors' => $e->getMessage(),
            ];
            return $this->json($data, 422);
        }
    }

}
