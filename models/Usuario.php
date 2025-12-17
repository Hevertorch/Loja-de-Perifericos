<?php
// models/Usuario.php
require_once __DIR__ . '/../config/Database.php';

class Usuario {
    private $pdo;

    public function __construct(){
        $this->pdo = Database::getInstance();
    }

    public function findByEmail($email){
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById($id){
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($nome, $email, $senha_plain, $tipo = 'cliente'){
        if($this->findByEmail($email)){
            return ['success' => false, 'msg' => 'Email já cadastrado'];
        }

        $hash = password_hash($senha_plain, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nome, email, senha_hash, tipo) VALUES (?, ?, ?, ?)");
        $ok = $stmt->execute([$nome, $email, $hash, $tipo]);

        if($ok){
            return ['success' => true, 'id' => $this->pdo->lastInsertId()];
        } else {
            return ['success' => false, 'msg' => 'Erro ao inserir no banco'];
        }
    }

    /**
     * Atualiza nome e/ou senha do usuário.
     * Se $senha_plain for null ou vazio, a senha NÃO é alterada.
     * Retorna true se a atualização aconteceu, false caso contrário.
     */
    public function update($id, $nome, $senha_plain = null){
        if($senha_plain !== null && $senha_plain !== ''){
            $hash = password_hash($senha_plain, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("UPDATE usuarios SET nome = ?, senha_hash = ? WHERE id = ?");
            return (bool)$stmt->execute([$nome, $hash, $id]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE usuarios SET nome = ? WHERE id = ?");
            return (bool)$stmt->execute([$nome, $id]);
        }
    }
}
