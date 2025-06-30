<?php

use PHPUnit\Framework\TestCase;

require_once 'src/UserManager.php';

class UserManagerTest extends TestCase
{
    private $userManager;
    private $testDataFile = 'data/test_users.json';

    protected function setUp(): void
    {
        // Gunakan file test terpisah
        $this->userManager = new UserManager();
        
        // Bersihkan data test
        if (file_exists($this->testDataFile)) {
            unlink($this->testDataFile);
        }
    }

    protected function tearDown(): void
    {
        // Bersihkan setelah test
        if (file_exists($this->testDataFile)) {
            unlink($this->testDataFile);
        }
    }

    public function testAddValidUser()
    {
        $result = $this->userManager->addUser('John Doe', 'john@example.com');
        $this->assertTrue($result);
        
        $users = $this->userManager->getAllUsers();
        $this->assertCount(1, $users);
        $this->assertEquals('John Doe', $users[0]['name']);
        $this->assertEquals('john@example.com', $users[0]['email']);
    }

    public function testAddUserWithEmptyName()
    {
        $result = $this->userManager->addUser('', 'john@example.com');
        $this->assertFalse($result);
        
        $users = $this->userManager->getAllUsers();
        $this->assertCount(0, $users);
    }

    public function testAddUserWithInvalidEmail()
    {
        $result = $this->userManager->addUser('John Doe', 'invalid-email');
        $this->assertFalse($result);
        
        $users = $this->userManager->getAllUsers();
        $this->assertCount(0, $users);
    }

    public function testAddDuplicateEmail()
    {
        // Tambah user pertama
        $this->userManager->addUser('John Doe', 'john@example.com');
        
        // Coba tambah user dengan email yang sama
        $result = $this->userManager->addUser('Jane Doe', 'john@example.com');
        $this->assertFalse($result);
        
        $users = $this->userManager->getAllUsers();
        $this->assertCount(1, $users);
    }

    public function testGetUserById()
    {
        $this->userManager->addUser('John Doe', 'john@example.com');
        $users = $this->userManager->getAllUsers();
        $userId = $users[0]['id'];
        
        $user = $this->userManager->getUserById($userId);
        $this->assertNotNull($user);
        $this->assertEquals('John Doe', $user['name']);
    }

    public function testGetNonExistentUser()
    {
        $user = $this->userManager->getUserById('non-existent-id');
        $this->assertNull($user);
    }

    public function testDeleteUser()
    {
        $this->userManager->addUser('John Doe', 'john@example.com');
        $users = $this->userManager->getAllUsers();
        $userId = $users[0]['id'];
        
        $result = $this->userManager->deleteUser($userId);
        $this->assertTrue($result);
        
        $users = $this->userManager->getAllUsers();
        $this->assertCount(0, $users);
    }
}
