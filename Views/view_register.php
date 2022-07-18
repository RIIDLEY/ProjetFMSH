<?php
require('view_begin.php');
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script>
    var check = function() {
        if (document.getElementById('password').value ==
            document.getElementById('confirm_password').value) {
            document.getElementById('message').style.color = 'green';
            document.getElementById('message').innerHTML = 'matching';
            document.getElementById('submit').disabled = false;
        } else {
            document.getElementById('message').style.color = 'red';
            document.getElementById('message').innerHTML = 'not matching';
            document.getElementById('submit').disabled = true;

        }
    }
</script>
		<main>
			<center>
			<h1>Nouvelle utilisateur</h1>

			<form action = "?controller=register&action=add" method="post">
				<p> <label> Identifiant: <input type="text" name="login" required/> </label> </p>
				<p> <label> Mot de passe: <input type="password" name="mdp" id="password" onkeyup='check();' required/> </label></p>
				<p> <label> Confirmation mot de passe: <input type="password" id="confirm_password" name="mdpconf" onkeyup='check();' required/></label> </p>
                <span id='message'></span>
				<p>  <input type="submit" value="Creation" id="submit"/> </p>
			</form>
			<button onclick="location.href='?controller=login&action=home'" type="button">Page de connexion</button>
			<button onclick="location.href='?controller=home&action=home'" type="button">Page d'accueil</button>
			</center>
<?php
require('view_end.php');
?>
