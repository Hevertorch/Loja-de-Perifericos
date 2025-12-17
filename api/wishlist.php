<?php
// api/wishlist.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Wishlist.php';

header('Content-Type: application/json');

// precisa estar logado
if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'msg' => 'Não autenticado']);
    exit;
}

// apenas clientes podem usar wishlist
if($_SESSION['tipo'] !== 'cliente'){
    echo json_encode(['success' => false, 'msg' => 'Apenas clientes podem usar a wishlist']);
    exit;
}

$w = new Wishlist();
$action = $_REQUEST['action'] ?? '';

switch($action){
    case 'add':
        $prod_id = intval($_POST['product_id'] ?? 0);
        if($prod_id <= 0) { echo json_encode(['success'=>false,'msg'=>'Produto inválido']); exit; }
        $ok = $w->add($_SESSION['user_id'], $prod_id);
        echo json_encode(['success' => $ok]);
    break;

    case 'remove':
        $prod_id = intval($_POST['product_id'] ?? 0);
        if($prod_id <= 0) { echo json_encode(['success'=>false,'msg'=>'Produto inválido']); exit; }
        $ok = $w->remove($_SESSION['user_id'], $prod_id);
        echo json_encode(['success' => $ok]);
    break;

    case 'list':
        $data = $w->listByUser($_SESSION['user_id']);
        echo json_encode(['success' => true, 'data' => $data]);
    break;

    case 'has':
        $prod_id = intval($_GET['product_id'] ?? 0);
        $has = $w->has($_SESSION['user_id'], $prod_id);
        echo json_encode(['success' => true, 'has' => $has]);
    break;

    default:
        echo json_encode(['success'=>false,'msg'=>'Ação inválida']);
    break;
}
