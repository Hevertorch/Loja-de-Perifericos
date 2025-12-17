<?php
require 'config/config.php';
require 'models/Usuario.php';

$erro = '';
$sucesso = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $senha2 = $_POST['senha2'] ?? '';

    // validações simples
    if(!$nome || !$email || !$senha){
        $erro = 'Preencha todos os campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Email inválido.';
    } elseif ($senha !== $senha2) {
        $erro = 'Senhas não conferem.';
    } elseif (strlen($senha) < 6) {
        $erro = 'Senha muito curta (mínimo 6 caracteres).';
    } else {
        $u = new Usuario();
        $res = $u->create($nome, $email, $senha);
        if($res['success']){
            $sucesso = 'Cadastro realizado. Você já pode entrar.';
            // opcional: logar automaticamente
            // $_SESSION['user_id'] = $res['id'];
            // header('Location: index.php'); exit;
        } else {
            $erro = $res['msg'] ?? 'Erro desconhecido';
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Registrar - Loja de Periféricos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="pt-5">
<div class="container">
  <div class="col-md-5 mx-auto">
    <div class="card p-4 shadow-sm">
      <h4 class="mb-3">Criar conta</h4>

      <?php if($erro): ?>
        <div class="alert alert-danger"><?php echo $erro; ?></div>
      <?php endif; ?>
      <?php if($sucesso): ?>
        <div class="alert alert-success"><?php echo $sucesso; ?></div>
      <?php endif; ?>

      <form method="post">
        <div class="mb-2"><input class="form-control" name="nome" placeholder="Nome" required></div>
        <div class="mb-2"><input class="form-control" name="email" placeholder="Email" required type="email"></div>
        <div class="mb-2"><input class="form-control" name="senha" placeholder="Senha" required type="password"></div>
        <div class="mb-2"><input class="form-control" name="senha2" placeholder="Repita a senha" required type="password"></div>
        <button class="btn btn-primary w-100">Registrar</button>
      </form>

      <hr>
      <a href="login.php">Já tem conta? Entrar</a>
    </div>
  </div>
</div>
</body>
</html>
