<?php
// minha_conta.php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/models/Usuario.php';
require_once __DIR__ . '/models/Wishlist.php';

// bloqueia acesso anônimo
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit;
}

$usuarioModel = new Usuario();
$user = $usuarioModel->findById($_SESSION['user_id']);
if(!$user){
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

$wModel = new Wishlist();
$wishlist = $wModel->listByUser($_SESSION['user_id']);

$erro = '';
$sucesso = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nome = trim($_POST['nome'] ?? '');
    $senha_atual = $_POST['senha_atual'] ?? '';
    $senha_nova = $_POST['senha_nova'] ?? '';
    $senha_nova2 = $_POST['senha_nova2'] ?? '';

    if(!$nome){
        $erro = 'Nome não pode estar vazio.';
    } else {
        if($senha_nova !== ''){
            if(!password_verify($senha_atual, $user['senha_hash'])){
                $erro = 'Senha atual incorreta.';
            } elseif ($senha_nova !== $senha_nova2){
                $erro = 'A nova senha e a confirmação não conferem.';
            } elseif (strlen($senha_nova) < 6){
                $erro = 'Nova senha muito curta (mínimo 6 caracteres).';
            } else {
                $ok = $usuarioModel->update($user['id'], $nome, $senha_nova);
                if($ok){
                    $sucesso = 'Nome e senha atualizados com sucesso.';
                    $_SESSION['user_name'] = $nome;
                    $user = $usuarioModel->findById($_SESSION['user_id']);
                } else {
                    $erro = 'Erro ao atualizar os dados.';
                }
            }
        } else {
            $ok = $usuarioModel->update($user['id'], $nome, null);
            if($ok){
                $sucesso = 'Nome atualizado com sucesso.';
                $_SESSION['user_name'] = $nome;
                $user = $usuarioModel->findById($_SESSION['user_id']);
            } else {
                $erro = 'Erro ao atualizar o nome.';
            }
        }
    }
    // recarrega wishlist após possíveis mudanças
    $wishlist = $wModel->listByUser($_SESSION['user_id']);
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Minha Conta - Techmontes</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-light bg-light px-3">
  <a class="navbar-brand d-flex align-items-center" href="loja.php">
    <img src="assets/images/logo.png" alt="Logo" style="height:28px; width:38px; object-fit:container; margin-right:8px;">
    <strong>Techmontes</strong>
  </a>
  <div class="ms-auto">
    <a href="logout.php" class="btn btn-outline-secondary btn-sm">Sair</a>
  </div>
</nav>

<div class="container py-4">
  <div class="row">
    <div class="col-md-6">
      <div class="card p-4 shadow mb-4">
        <h4 class="mb-3">Minha Conta</h4>
        <?php if($erro): ?><div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div><?php endif; ?>
        <?php if($sucesso): ?><div class="alert alert-success"><?php echo htmlspecialchars($sucesso); ?></div><?php endif; ?>
        <form method="post" novalidate>
          <div class="mb-2"><label>Nome</label>
            <input class="form-control" name="nome" value="<?php echo htmlspecialchars($user['nome']); ?>" required></div>
          <div class="mb-3"><label>Email (não editável)</label>
            <input class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled></div>

          <hr>
          <h6>Alterar senha</h6>
          <small class="text-muted">Preencha apenas se quiser trocar a senha.</small>
          <div class="mb-2"><label>Senha atual</label>
            <input type="password" class="form-control" name="senha_atual" placeholder="Senha atual"></div>
          <div class="mb-2"><label>Nova senha</label>
            <input type="password" class="form-control" name="senha_nova" placeholder="Nova senha (mín 6)"></div>
          <div class="mb-2"><label>Repita nova senha</label>
            <input type="password" class="form-control" name="senha_nova2" placeholder="Repita a nova senha"></div>

          <div class="d-flex justify-content-between mt-3">
            <a href="loja.php" class="btn btn-outline-secondary">Voltar à loja</a>
            <button class="btn btn-primary" type="submit">Salvar alterações</button>
          </div>
        </form>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card p-4 shadow">
        <h4>Minha Lista de Desejos</h4>
        <?php if(empty($wishlist)): ?>
          <div class="alert alert-info">Sua lista de desejos está vazia.</div>
        <?php else: ?>
          <div class="list-group">
            <?php foreach($wishlist as $w): ?>
              <div class="list-group-item wishlist-row d-flex justify-content-between align-items-center">
                <div>
                  <strong><?php echo htmlspecialchars($w['nome']); ?></strong><br>
                  <small class="text-muted">R$ <?php echo htmlspecialchars($w['preco']); ?> — <?php echo htmlspecialchars($w['categoria'] ?? ''); ?></small>
                </div>
                <div>
                  <button class="btn btn-sm btn-outline-secondary btn-wishlist-remove" data-product-id="<?php echo $w['id']; ?>">Remover</button>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/js/wishlist.js"></script>
</body>
</html>
