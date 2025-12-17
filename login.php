<?php
// login.php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/models/Usuario.php';

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    $u = new Usuario();
    $user = $u->findByEmail($email);
    if($user && password_verify($senha, $user['senha_hash'])){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nome'];
        $_SESSION['tipo'] = $user['tipo'];
        
        if(in_array($user['tipo'], ['funcionario','admin'])) {
            header('Location: index.php');
        } else {
            header('Location: loja.php');
        }
        exit;
    } else {
        $error = "Credenciais inválidas.";
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login - Techmontes Periféricos</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>body{background:#f1f5f9}</style>
</head>
<body>

   <header class="py-3">
  <div class="d-flex justify-content-center align-items-center gap-3"
       style="transform: translateY(120px);">
    <img src="assets/images/logo.png" 
         alt="Logo"
         style="height:40px; width:auto;">
    <h1 class="m-0" style=" color: #4775f1ff; font-weight:700;">
      Techmontes Periféricos
    </h1>
  </div>
</header>

<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="col-md-4">
    <div class="card p-4 shadow">
      <h4 class="card-title mb-3">Entrar</h4>
      <?php if($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>
      <form method="post" novalidate>
        <div class="mb-2">
          <input class="form-control" name="email" placeholder="Email" required type="email">
        </div>
        <div class="mb-2">
          <input type="password" class="form-control" name="senha" placeholder="Senha" required>
        </div>
        <button class="btn btn-primary w-100">Entrar</button>
      </form>
      <hr>
      <div class="d-flex justify-content-between">
        <a class="btn btn-outline-secondary" href="loja.php">Ver loja</a>
        <a class="btn btn-warning" href="register.php">Novo na loja? Registre-se</a>
      </div>
    </div>
  </div>
</div>
</body>
</html>
