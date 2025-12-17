<?php
// loja.php (server-side render) - com botão wishlist e logo
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/models/Produto.php';

$prodModel = new Produto();
$produtos = $prodModel->all();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Loja - Techmontes Periféricos</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-3">
  <a class="navbar-brand d-flex align-items-center" href="loja.php">
    <img src="assets/images/logo.png" alt="Logo" style="height:22px; width:27px; object-fit:container; margin-right:2px;">
    <strong>Techmontes</strong>
  </a>
  <div class="ms-auto">
    <?php if(isset($_SESSION['user_id'])): ?>
      <span class="me-2">Olá, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
      <a href="minha_conta.php" class="btn btn-outline-primary btn-sm me-2">Minha Conta</a>
      <a href="logout.php" class="btn btn-outline-secondary btn-sm">Sair</a>
    <?php else: ?>
      <a href="login.php" class="btn btn-outline-primary btn-sm me-2">Entrar</a>
      <a href="register.php" class="btn btn-primary btn-sm">Registrar</a>
    <?php endif; ?>
  </div>
</nav>

<div class="container py-4">
  <h3>Produtos</h3>

  <?php if(empty($produtos)): ?>
    <div class="alert alert-warning">Nenhum produto disponível no momento.</div>
  <?php else: ?>
    <div class="row">
      <?php foreach($produtos as $p): ?>
        <div class="col-md-4 mb-3">
          <div class="card h-100 shadow-sm">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?php echo htmlspecialchars($p['nome']); ?></h5>
              <p class="card-text"><?php echo htmlspecialchars(mb_strimwidth($p['descricao'] ?? '', 0, 120, '...')); ?></p>
              <p class="card-text"><strong>R$ <?php echo htmlspecialchars($p['preco']); ?></strong></p>
              <p class="card-text"><small class="text-muted"><?php echo htmlspecialchars($p['categoria'] ?? ''); ?></small></p>

              <div class="mt-auto d-flex gap-2">
                <?php if(isset($_SESSION['user_id']) && $_SESSION['tipo'] === 'cliente'): ?>
                  <button class="btn btn-outline-danger btn-sm btn-wishlist-add" data-product-id="<?php echo $p['id']; ?>">
                    ❤️ Adicionar à lista
                  </button>
                <?php else: ?>
                  <a class="btn btn-outline-danger btn-sm" href="login.php">❤️ Entrar para salvar</a>
                <?php endif; ?>
                <button class="btn btn-success btn-sm">Comprar</button>
              </div>

            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/js/wishlist.js"></script>
</body>
</html>
