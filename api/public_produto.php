<?php
// api/public_produto.php  (COLE exatamente este)
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Produto.php';

header('Content-Type: application/json');

try {
    $prod = new Produto();
    $data = $prod->all();
    echo json_encode(['success' => true, 'data' => $data]);
} catch (Throwable $e) {
    // Em dev mostre a mensagem; em produção esconda
    echo json_encode(['success' => false, 'msg' => 'Erro ao obter produtos', 'error' => $e->getMessage()]);
}
