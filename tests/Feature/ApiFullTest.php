<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ApiFullTest extends TestCase
{
    private $adminToken;
    private $userToken;
    private $testProductId;
    private $testCategoryId;

    public function test_register_and_login_user()
    {
        $email = 'user' . rand(1000,9999) . '@test.com';
        $password = 'user123';
        $register = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => $email,
            'password' => $password
        ]);
        $register->assertStatus(201);
        $login = $this->postJson('/api/login', [
            'email' => $email,
            'password' => $password
        ]);
        $login->assertStatus(200);
        $this->userToken = $login['access_token'];
    }

    public function test_login_admin()
    {
        $login = $this->postJson('/api/login', [
            'email' => 'admin@paageming.com',
            'password' => 'admin123'
        ]);
        $login->assertStatus(200);
        $this->adminToken = $login['access_token'];
    }

    public function test_category_crud()
    {
        $token = $this->adminToken;
        $name = 'Kategori Test ' . rand(1000,9999);
        $create = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/categories', [
                'name' => $name,
                'description' => 'Deskripsi kategori test'
            ]);
        $create->assertStatus(201);
        $this->testCategoryId = $create['id'] ?? $create->json('id');
        $get = $this->getJson('/api/categories/' . $this->testCategoryId);
        $get->assertStatus(200);
    }

    public function test_product_crud()
    {
        $token = $this->adminToken;
        $name = 'Produk Test ' . rand(1000,9999);
        $create = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/products', [
                'name' => $name,
                'description' => 'Deskripsi produk test',
                'price' => 10000,
                'stock' => 10,
                'category_id' => $this->testCategoryId ?? 'cat-1',
                'image_url' => 'https://dummyimage.com/600x400/000/fff'
            ]);
        $create->assertStatus(201);
        $this->testProductId = $create['id'] ?? $create->json('id');
        $get = $this->getJson('/api/products/' . $this->testProductId);
        $get->assertStatus(200);
    }

    public function test_user_list()
    {
        $token = $this->adminToken;
        $get = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/users');
        $get->assertStatus(200);
    }

    public function test_cart_flow()
    {
        $token = $this->userToken;
        $add = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/cart/add', [
                'product_id' => $this->testProductId ?? 'prod-1',
                'quantity' => 1
            ]);
        $add->assertStatus(200);
        $cart = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/cart');
        $cart->assertStatus(200);
    }
}
