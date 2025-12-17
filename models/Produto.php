<?php
// models/Produto.php
require_once __DIR__ . '/../config/Database.php';

class Produto {
    private $pdo;
    public function __construct(){
        $this->pdo = Database::getInstance();
    }

    public function all(){
        $stmt = $this->pdo->query(
            "SELECT p.*, c.nome AS categoria 
             FROM produtos p 
             LEFT JOIN categorias c ON p.categoria_id = c.id 
             ORDER BY p.id DESC"
        );
        return $stmt->fetchAll();
    }

    public function find($id){
        $stmt = $this->pdo->prepare(
            "SELECT p.*, c.nome AS categoria 
             FROM produtos p 
             LEFT JOIN categorias c ON p.categoria_id = c.id 
             WHERE p.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data){
        $stmt = $this->pdo->prepare(
            "INSERT INTO produtos (nome, descricao, preco, estoque, categoria_id, imagem) 
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $data['nome'], 
            $data['descricao'], 
            $data['preco'], 
            $data['estoque'], 
            $data['categoria_id'] ?: null, 
            $data['imagem'] ?? null
        ]);
    }

    public function update($id, $data){
        $stmt = $this->pdo->prepare(
            "UPDATE produtos SET nome=?, descricao=?, preco=?, estoque=?, categoria_id=?, imagem=? WHERE id=?"
        );
        return $stmt->execute([
            $data['nome'], 
            $data['descricao'], 
            $data['preco'], 
            $data['estoque'], 
            $data['categoria_id'] ?: null, 
            $data['imagem'] ?? null, 
            $id
        ]);
    }

    public function delete($id){
        $stmt = $this->pdo->prepare("DELETE FROM produtos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
