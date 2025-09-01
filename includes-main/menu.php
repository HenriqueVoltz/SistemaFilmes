<?php
if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Sistema de Filmes</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="filmes.php">Filmes</a></li>
        <li class="nav-item"><a class="nav-link" href="generos.php">Gêneros</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Sair</a></li>
      </ul>
    </div>
  </div>
</nav>
