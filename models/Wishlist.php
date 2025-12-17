<?php
// models/Wishlist.php
require_once __DIR__ . '/../config/Database.php';

class Wishlist {
    private $pdo;
    public function __construct(){
        $this->pdo = Database::getInstance();
    }

    public function add($user_id, $product_id){
        $stmt = $this->pdo->prepare("INSERT IGNORE INTO wishlists (user_id, product_id) VALUES (?, ?)");
        return (bool)$stmt->execute([$user_id, $product_id]);
    }

    public function remove($user_id, $product_id){
        $stmt = $this->pdo->prepare("DELETE FROM wishlists WHERE user_id = ? AND product_id = ?");
        return (bool)$stmt->execute([$user_id, $product_id]);
    }

    public function listByUser($user_id){
        $stmt = $this->pdo->prepare(
            "SELECT p.*, c.nome AS categoria, w.created_at
             FROM wishlists w
             JOIN produtos p ON p.id = w.product_id
             LEFT JOIN categorias c ON p.categoria_id = c.id
             WHERE w.user_id = ?
             ORDER BY w.created_at DESC"
        );
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function has($user_id, $product_id){
        $stmt = $this->pdo->prepare("SELECT 1 FROM wishlists WHERE user_id = ? AND product_id = ? LIMIT 1");
        $stmt->execute([$user_id, $product_id]);
        return (bool)$stmt->fetch();
    }
}
