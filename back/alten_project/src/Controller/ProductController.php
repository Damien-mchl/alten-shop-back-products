<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{
    //GET return all products
    #[Route('/products', methods: ['GET'], name: 'app_products')]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $products = $entityManager->getRepository(Product::class)->findAll();
        
        $productsArray = [];
        foreach ($products as $product) {
            $productsArray[] = [
                'id' => $product->getId(),
                'code' => $product->getCode(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'price' => $product->getPrice(),
                'quantity' => $product->getQuantity(),
                'inventoryStatus' => $product->getInventoryStatus(),
                'category' => $product->getCategory(),
                'image' => $product->getImage(),
                'rating' => $product->getRating(),
            ];
        }
        return $this->json($productsArray);
    }

    //POST create new product
    #[Route('/products', methods: ['POST'], name: 'app_products_create')]
    public function createProduct(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        if($request->get('code') && $request->get('name') && $request->get('description') && $request->get('price') && $request->get('quantity') && $request->get('inventoryStatus') && $request->get('category')){
            // Create a new instance of the Product entity
            $product = new Product();

            // Set the properties of the product
            $product->setCode($request->get('code'));
            $product->setName($request->get('name'));
            $product->setDescription($request->get('description'));
            $product->setPrice($request->get('price'));
            $product->setQuantity($request->get('quantity'));
            $product->setInventoryStatus($request->get('inventoryStatus'));
            $product->setCategory($request->get('category'));
            $product->setImage($request->get('image'));
            $product->setRating($request->get('rating'));

            // Verify there are no errors
            $errors = $validator->validate($product);
            if(count($errors) > 0){
                return $this->json(['message' => 'There was an error creating the Product']);
            }

            // Persist the product in the database
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->json(['message' => 'Product created successfully']);
        }
        return $this->json(['message' => 'Request is missing some data']);
    }

    //GET return product
    #[Route('/products/{id}', methods: ['GET'], name: 'app_products_id')]
    public function product(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        if($product){
            $productArray = [
                'id' => $product->getId(),
                'code' => $product->getCode(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
                'price' => $product->getPrice(),
                'quantity' => $product->getQuantity(),
                'inventoryStatus' => $product->getInventoryStatus(),
                'category' => $product->getCategory(),
                'image' => $product->getImage(),
                'rating' => $product->getRating(),
            ];
            return $this->json($productArray);
        }
        return $this->json(['message' => 'Product not found']);
    }

    //PATCH Update product if exist
    #[Route('/products/{id}', methods: ['PATCH'], name: 'app_products_id_update')]
    public function updateProduct(Request $request, EntityManagerInterface $entityManager, int $id, ValidatorInterface $validator): JsonResponse
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        if($product){
            if($request->get('code') && $request->get('name') && $request->get('description') && $request->get('price') && $request->get('quantity') && $request->get('inventoryStatus') && $request->get('category')){
                // Update the properties of the product
                $product->setCode($request->get('code'));
                $product->setName($request->get('name'));
                $product->setDescription($request->get('description'));
                $product->setPrice($request->get('price'));
                $product->setQuantity($request->get('quantity'));
                $product->setInventoryStatus($request->get('inventoryStatus'));
                $product->setCategory($request->get('category'));
                $product->setImage($request->get('image'));
                $product->setRating($request->get('rating'));

                // Verify there are no errors
                $errors = $validator->validate($product);
                if(count($errors) > 0){
                    return $this->json(['message' => 'There was an error updating the Product']);
                }

                // Persist the product in the database
                $entityManager->persist($product);
                $entityManager->flush();

                return $this->json(['message' => 'Product updated successfully']);
            }
            return $this->json(['message' => 'Request is missing some data']);
        }
        return $this->json(['message' => 'Product not found']);
    }

    //DELETE Delete product from database
    #[Route('/products/{id}', methods: ['DELETE'], name: 'app_products_id_delete')]
    public function deleteProduct(EntityManagerInterface $entityManager, int $id): JsonResponse
    {
        $product = $entityManager->getRepository(Product::class)->find($id);
        if($product){
            $entityManager->remove($product);
            $entityManager->flush();
            return $this->json(['message' => 'Product deleted successfully']);
        }
        return $this->json(['message' => 'Product not found.']);
    }
}
