<html>
	<head>
		<title>confirmation</title>
		<meta http-equiv="content-type" content="text/html, charset=utf-8" />
		<link rel="stylesheet" href="style.css" />
	</head>

<body>
<!-- connection à la bdd -->
<h2>Confirmation des enregistrements</h2>
<?php
$a=pg_connect("dbname=bacterio_upnp user=pharmacien host=127.0.0.1 password=zac");
if ($a==false)
{
	echo "problème de connexion<br>";
	exit;
}

$date_prelev=$_GET['date_prelev'];
echo "Date de prélèvement : <font color=blue>".$date_prelev."</font><br>";

$lignes=$_GET['lignes'];
echo "<p>";

for ($j=0; $j<$lignes; $j++)
{
$id_point=$_GET['id_point'.$j.''];
	if ($id_point<>NULL)
	{
	$quel_nom = "SELECT point, id FROM points_prelev WHERE id=$id_point;";
	$nom = pg_query($a, $quel_nom);
		if ($nom==false)
		{
		echo "problème de requête du nom<br>";
		}
	$point = pg_fetch_result($nom, 0);
	$question="INSERT INTO prelevements (date_prelev, id_point) VALUES ('$date_prelev', $id_point);";

	$reponse=pg_query($a, $question);
		if ($reponse==false)
		{
			echo "Problème : le prélèvement du <font color=blue>".$point."</font> (id ".$id_point.") n'a pas été enregistré !<br>";
		}
		else
		{
			echo "Le prélèvement du <font color=blue>".$point."</font> (id ".$id_point.") est enregistré.<br>";
		}
		
	}
}




?>
<p>
<a href="prelevements_saisie.html">Saisir de nouveaux prélèvements</a><br>
<a href="accueil.html">Retour à l'accueil</a>


</body>
</html>
