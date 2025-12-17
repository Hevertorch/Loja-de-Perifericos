<?php
// config/Database.php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {

        // Construção do DSN com porta para MariaDB/MySQL
        $dsn = "mysql:host=" . DB_HOST .
               ";port=" . DB_PORT .
               ";dbname=" . DB_NAME .
               ";charset=utf8mb4";

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,      // mostra erros
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // fetch padrão
                PDO::ATTR_PERSISTENT => false                     // conexão não persistente
            ]);

        } catch (PDOException $e) {
            // Erro mais amigável DURANTE DESENVOLVIMENTO
            die("❌ ERRO AO CONECTAR AO BANCO:<br>" . $e->getMessage());
        }
    }

    // Singleton — garante uma conexão única
    public static function getInstance() {
        if(self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }
}
