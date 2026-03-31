<?php
namespace App\Service;

class PdoService
{
    private \PDO $pdo;

    public function __construct()
    {
        $url = $_ENV['DATABASE_URL'];
        $parts = parse_url($url);

        $host = $parts['host'];
        $db   = ltrim($parts['path'], '/');
        $user = $parts['user'];
        $pass = $parts['pass'];

        $this->pdo = new \PDO(
            "mysql:host=$host;dbname=$db;charset=utf8mb4",
            $user,
            $pass,
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );
    }

    public function getPdo(): \PDO
    {
        return $this->pdo;
    }
}