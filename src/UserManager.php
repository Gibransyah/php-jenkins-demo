<?php

/**
 * Kelas UserManager untuk mengelola data user sederhana
 */
class UserManager
{
    private $users = [];
    private $dataFile = 'data/users.json';

    public function __construct()
    {
        $this->loadUsers();
    }

    /**
     * Menambah user baru
     */
    public function addUser($name, $email)
    {
        if (empty($name) || empty($email)) {
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $user = [
            'id' => uniqid(),
            'name' => $name,
            'email' => $email,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->users[] = $user;
        $this->saveUsers();
        
        return true;
    }

    /**
     * Mendapatkan semua user
     */
    public function getAllUsers()
    {
        return $this->users;
    }

    /**
     * Memuat data user dari file
     */
    private function loadUsers()
    {
        if (file_exists($this->dataFile)) {
            $data = file_get_contents($this->dataFile);
            $this->users = json_decode($data, true) ?: [];
        }
    }

    /**
     * Menyimpan data user ke file
     */
    private function saveUsers()
    {
        $dir = dirname($this->dataFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        file_put_contents($this->dataFile, json_encode($this->users, JSON_PRETTY_PRINT));
    }
}
