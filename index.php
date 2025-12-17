<?php
// index.php
require 'config/config.php';

// obrigar login
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit;
}

// permitir somente funcionario e admin
if(!in_array($_SESSION['tipo'], ['funcionario','admin'])){
    // redireciona clientes para a vitrine pública
    header('Location: loja.php');
    exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Painel de estoque - Techmontes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-3">
  <a class="navbar-brand" href="#">Painel de controle - Techmontes</a>
  <div class="ms-auto">
    <span class="me-3">Olá, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
    <a href="logout.php" class="btn btn-outline-secondary btn-sm">Sair</a>
  </div>
</nav>

<div class="container py-4">
  <div class="d-flex justify-content-between mb-3">
    <h3>Produtos</h3>
    <!-- botão visível apenas para funcionario/admin (checado no servidor) -->
    <?php if (in_array($_SESSION['tipo'], ['funcionario','admin'])): ?>
      <button class="btn btn-primary" id="btnNovo">Novo produto</button>
    <?php endif; ?>
  </div>

  <div id="alerta"></div>

  <div class="row" id="cards"></div>

  <hr>
  <h5>Visão administrativa</h5>
  <table class="table table-striped" id="tabela">
    <thead>
      <tr><th>ID</th><th>Nome</th><th>Preço</th><th>Estoque</th><th>Categoria</th><th>Ações</th></tr>
    </thead>
    <tbody></tbody>
  </table>
</div>

<!-- Modal CRUD (mantém como antes) -->
<div class="modal fade" id="modalProd" tabindex="-1">
  <div class="modal-dialog">
    <form id="formProd" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Produto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="prod_id">
        <div class="mb-2">
          <label>Nome</label>
          <input class="form-control" name="nome" id="prod_nome" required>
        </div>
        <div class="mb-2">
          <label>Descrição</label>
          <textarea class="form-control" name="descricao" id="prod_desc"></textarea>
        </div>
        <div class="mb-2">
          <label>Preço</label>
          <input class="form-control" name="preco" id="prod_preco" required>
        </div>
        <div class="mb-2">
          <label>Estoque</label>
          <input class="form-control" name="estoque" id="prod_estoque" required>
        </div>
        <div class="mb-2">
          <label>Categoria (ID)</label>
          <input class="form-control" name="categoria_id" id="prod_categoria">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-primary" type="submit">Salvar</button>
      </div>
    </form>
  </div>
</div>

<script>
  const USER_TIPO = "<?php echo addslashes($_SESSION['tipo']); ?>";
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
