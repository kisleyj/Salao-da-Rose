<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Salão Rose</title>
  <link rel ="stylesheet" href="css/style.css">
</head>
<body>
  
<div class="wrapper">
  <!-- FORMULÁRIO DE LOGIN -->
     <div class="form-wrapper sign-in">
      <form action="pages/register.php" method="POST">
        <h2>Login</h2>
        <div class="input-group">
          <span class="icon"><ion-icon name="people"></ion-icon></span>
          <input type="text" name="nome" required>
          <label>Nome</label>
        </div>
        <div class="input-group">
          <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
          <input type="password" name="senha" required>
          <label>Senha</label>
        </div>
        <div class="remember">
          <label><input type="checkbox">Lembre-me</label>
        </div>
        <button type="submit" name="signIn">Entrar</button>
        <div class="signUp-link">
           <p>Não possui conta? <a href="#" class="signUpBtn-link">Cadastrar</a></p>
        </div>
      </form>
     </div>

<div class="form-wrapper sign-up">
  <!-- FORMULÁRIO DE CADASTRO -->
        <form id="cadastroForm" action="pages/register.php" method="post">
            <h2>Cadastro</h2>
            <div class="input-group">
                <input type="text" name="nome" required>
                <label>Nome</label>
            </div>
            <div class="input-group">
                <input type="text" name="cpf" required>
                <label>CPF</label>
            </div>
            <div class="input-group">
                <input type="text" name="telefone" required>
                <label>Telefone</label>
            </div>
            <div class="input-group">
                <input type="password" name="senha" required>
                <label>Senha</label>
            </div>
            <input type="hidden" name="signUp" value="1">
            <button type="submit" name="signUp">Cadastrar</button>
            <div class="signUp-link">
                <p>Já possui conta? <a href="#" class="signInBtn-link">Entrar</a></p>
            </div>
        </form>
    </div>
</div>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="js/script.js"></script>
</body>
</html>