<?php
// api/produto.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/Produto.php';

header('Content-Type: application/json');

// autenticação: só responde se existir sessão
if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'msg' => 'Não autenticado']);
    exit;
}

// autorização: somente funcionario/admin podem usar CRUD
if(!in_array($_SESSION['tipo'], ['funcionario','admin'])){
    echo json_encode(['success' => false, 'msg' => 'Acesso restrito']);
    exit;
}

$prod = new Produto();
$action = $_REQUEST['action'] ?? '';

switch($action){
    case 'list':
        echo json_encode(['success' => true, 'data' => $prod->all()]);
    break;

    case 'get':
        $id = intval($_GET['id'] ?? 0);
        echo json_encode(['success' => true, 'data' => $prod->find($id)]);
    break;

    case 'create':
        $data = [
            'nome' => $_POST['nome'] ?? '',
            'descricao' => $_POST['descricao'] ?? '',
            'preco' => $_POST['preco'] ?? '0.00',
            'estoque' => $_POST['estoque'] ?? 0,
            'categoria_id' => $_POST['categoria_id'] ?? null,
            'imagem' => null
        ];
        $ok = $prod->create($data);
        echo json_encode(['success' => (bool)$ok]);
    break;

    case 'update':
        $id = intval($_POST['id'] ?? 0);
        $data = [
            'nome' => $_POST['nome'] ?? '',
            'descricao' => $_POST['descricao'] ?? '',
            'preco' => $_POST['preco'] ?? '0.00',
            'estoque' => $_POST['estoque'] ?? 0,
            'categoria_id' => $_POST['categoria_id'] ?? null,
            'imagem' => null
        ];
        $ok = $prod->update($id, $data);
        echo json_encode(['success' => (bool)$ok]);
    break;

    case 'delete':
        $id = intval($_POST['id'] ?? 0);
        $ok = $prod->delete($id);
        echo json_encode(['success' => (bool)$ok]);
    break;

    default:
        echo json_encode(['success' => false, 'msg' => 'Ação inválida']);
    break;
}
