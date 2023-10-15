<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductControllerTest extends WebTestCase
{

    public function test_get_all_products(): void
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        $product = new Product();

        // Set the properties of the product
        $product->setCode('f230fh0g3');
        $product->setName('Bamboo Watch');
        $product->setDescription('Product Description');
        $product->setPrice(65);
        $product->setQuantity(24);
        $product->setInventoryStatus('INSTOCK');
        $product->setCategory('Accessories');
        $product->setImage('bamboo-watch.jpg');
        $product->setRating(5);

        // Persist the product in the database
        $entityManager->persist($product);
        $entityManager->flush();

        $product2 = new Product();

        // Set the properties of the product
        $product2->setCode('nvklal433');
        $product2->setName('Black Watch');
        $product2->setDescription('Product Description');
        $product2->setPrice(72);
        $product2->setQuantity(61);
        $product2->setInventoryStatus('INSTOCK');
        $product2->setCategory('Accessories');
        $product2->setImage('black-watch.jpg');
        $product2->setRating(4);

        // Persist the product in the database
        $entityManager->persist($product2);
        $entityManager->flush();

        $crawler = $client->request('GET', '/products');
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);
        $expectedArray = 
        [
            [
            'id' => $product->getId(),
            'code' => "f230fh0g3",
            'name' => 'Bamboo Watch',
            'description' => 'Product Description',
            'price' => 65,
            'quantity' => 24,
            'inventoryStatus' => 'INSTOCK',
            'category' => 'Accessories',
            'image' => 'bamboo-watch.jpg',
            'rating' => 5
            ],
            [
            'id' => $product2->getId(),
            'code' => 'nvklal433',
            'name' => 'Black Watch',
            'description' => 'Product Description',
            'price' => 72,
            'quantity' => 61,
            'category' => 'Accessories',
            'image' => 'black-watch.jpg',
            'rating' => 4
            ]
        ];
        $this->assertEquals($responseData[0], $expectedArray[0]);
    }

    public function test_get_product(): void
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        $product = new Product();

        // Set the properties of the product
        $product->setCode('f230fh0g3');
        $product->setName('Bamboo Watch');
        $product->setDescription('Product Description');
        $product->setPrice(65);
        $product->setQuantity(24);
        $product->setInventoryStatus('INSTOCK');
        $product->setCategory('Accessories');
        $product->setImage('bamboo-watch.jpg');
        $product->setRating(5);

        // Persist the product in the database
        $entityManager->persist($product);
        $entityManager->flush();

        $crawler = $client->request('GET', '/products/'.$product->getId());
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);
        $expectedArray = 
            [
            'id' => $product->getId(),
            'code' => "f230fh0g3",
            'name' => 'Bamboo Watch',
            'description' => 'Product Description',
            'price' => 65,
            'quantity' => 24,
            'inventoryStatus' => 'INSTOCK',
            'category' => 'Accessories',
            'image' => 'bamboo-watch.jpg',
            'rating' => 5
            ];
        $this->assertEquals($responseData, $expectedArray);
    }

    public function test_delete_product(): void
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        $product = new Product();

        // Set the properties of the product
        $product->setCode('f230fh0g3');
        $product->setName('Bamboo Watch');
        $product->setDescription('Product Description');
        $product->setPrice(65);
        $product->setQuantity(24);
        $product->setInventoryStatus('INSTOCK');
        $product->setCategory('Accessories');
        $product->setImage('bamboo-watch.jpg');
        $product->setRating(5);

        // Persist the product in the database
        $entityManager->persist($product);
        $entityManager->flush();

        $crawler = $client->request('DELETE', '/products/'.$product->getId());
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);
        $this->assertResponseIsSuccessful();
        
        $expected = ['message' => 'Product deleted successfully'];
        $this->assertEquals($responseData,$expected);
    }
}
