<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiOrderController extends AbstractController
{
    /**
     * @param OrderRepository $orderRepository
     * @return JsonResponse
     * @Route("/api/order", name="api_orders", methods={"GET"})
     */
    public function index(OrderRepository $orderRepository): Response
    {
        $data = $orderRepository->findAll();
        return $this->json($data);
    }

    /**
     * @param OrderRepository $orderRepository
     * @param $id
     * @return JsonResponse
     * @Route("/api/order/{id}", name="api_order", methods={"GET"})
     */
    public function getProduct(OrderRepository $orderRepository, $id){
        $order = $orderRepository->find($id);

        if (!$order){
            $data = [
                'status' => 404,
                'errors' => "Order not found",
            ];
            return $this->json($data, 404);
        }
        return $this->json($order);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param OrderRepository $orderRepository
     * @return JsonResponse
     * @throws \Exception
     * @Route("/api/order", name="api_order_add", methods={"POST"})
     */
    public function addProduct(Request $request, EntityManagerInterface $entityManager, OrderRepository $orderRepository, ProductRepository $productRepository)
    {
        try {
            $requestData = json_decode($request->getContent(), true);

            if (!isset($requestData['amount']) || !isset($requestData['product_id'])) {
                throw new \Exception('Invalid data');
            }

            $product_id = $requestData['product_id'];
            $amount = $requestData['amount'];

            if ($amount <= 0) {
                throw new \Exception('Amount should be greater than zero');
            }

            $product = $productRepository->find($product_id);

            if (!$product) {
                throw new \Exception('Product not found in database');
            }

            $order = new Order();
            $order->setAmount($amount);
            $order->setProduct($product);

            $entityManager->persist($order);
            $entityManager->flush();

            $data = [
                'status' => 200,
                'success' => 'Order added successfully',
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
     * @param OrderRepository $orderRepository
     * @param OrderRepository $orderRepository
     * @param int $id
     * @return JsonResponse
     * @throws \Exception
     * @Route("/api/order/{id}", name="api_order_update", methods={"PUT", "PUTCH"})
     */
    public function updateProduct(Request $request, EntityManagerInterface $entityManager,ProductRepository $productRepository, OrderRepository $orderRepository, int $id)
    {
        try {
            $requestData = json_decode($request->getContent(), true);

            if (!isset($requestData['amount']) || !isset($requestData['product_id'])) {
                throw new \Exception('Invalid data');
            }

            $product_id = $requestData['product_id'];
            $amount = $requestData['amount'];

            if ($amount <= 0) {
                throw new \Exception('Amount should be greater than zero');
            }

            $product = $productRepository->find($product_id);

            if (!$product) {
                throw new \Exception('Product not found in database');
            }

            $order = $orderRepository->find($id);

            if (!$order) {
                throw new \Exception('Order not found');
            }

            $order->setAmount($amount);
            $order->setProduct($product);

            $entityManager->flush();

            $data = [
                'status' => 200,
                'success' => 'Order updated successfully',
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
     * @Route("/api/order/{id}", name="api_order_delete", methods={"DELETE"})
     */
    public function deleteProduct(int $id, EntityManagerInterface $entityManager, OrderRepository $orderRepository)
    {
        try {
            $order = $orderRepository->findOneBy(['id' => $id]);

            $entityManager->remove($order);
            $entityManager->flush();

            $data = [
                'status' => 200,
                'success' => 'Order deleted successfully',
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
