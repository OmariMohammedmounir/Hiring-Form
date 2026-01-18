<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion Administrateur</title>
  <!-- Set base URL so that relative URLs point to /weetel/public/ -->
  <base href="<?php echo BASE_URL; ?>">

  <link rel="stylesheet" href="css/login.css">
  <script src="js/login.js" defer></script>
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <h1>Connexion Administrateur</h1>
      <?php if(isset($error)) { echo "<p class='error'>$error</p>"; } ?>
      <form action="login" method="post">
        <div class="input-group">
          <input type="text" name="username" id="username" placeholder=" " required>
          <label for="username">Nom d'utilisateur</label>
        </div>
        <div class="input-group">
          <input type="password" name="password" id="password" placeholder=" " required>
          <label for="password">Mot de passe</label>
        </div>
        <button type="submit">Se connecter</button>
      </form>
    </div>
  </div>
</body>
</html>
