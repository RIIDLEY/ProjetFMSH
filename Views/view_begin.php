<!DOCTYPE html>
<html lang="fr">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap-theme.min.css">-->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.20/angular.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<link rel="stylesheet" href="src/css/bootstrap-tagsinput.css">
<link rel="stylesheet" href="src/css/main.css">
<script src="src/js/bootstrap-tagsinput.min.js"></script>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <!--<img src="Assets/img/logo_citu.png" width="60" height="60" alt="">-->
    <span class="navbar-brand mb-0 h1" style="padding: 10px">RIISH</span>
    <span class="navbar-brand mb-0 h1">|</span>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <a class="nav-item nav-link" href="?controller=home" id="home">Accueil</a>
            <?php
            if (isset($_SESSION['admin'])) {//Si la variable existe
                if ($_SESSION['admin'] === true) {//si c'est un admin
                    echo '<a class="nav-item nav-link" href="?controller=toolsadmin" id="admin">Espace membre</a>';//ajoute le bouton de deconnection
                }else{
                    echo '<a class="nav-item nav-link" href="?controller=toolsadmin" id="admin">Espace membre</a>';

                }
            }else{
                echo '<a class="nav-item nav-link" href="?controller=toolsadmin" id="admin">Espace membre</a>';

            }
            ?>
        </div>
    </div>
</nav>
		<main>