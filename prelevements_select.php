<html>
	  <head>
		    <title>Prélèvements</title>
		    <meta http-equiv="content-type" content="text/html, charset=utf-8" />
		    <link rel="stylesheet" href="style.css" />
	  </head>
    <!-- Affichage des tables prelevements et resultats de la bdd bacterio_upnp avec les filtres sélectionnés par la page prelevements_select.html --> 
    <body>
        <a href="accueil.html">Retour à l'accueil</a>
        <h2>Prélèvements réalisés et résultats</h2>

        <!-- connection à la bdd -->
        <?php
        $a=pg_connect("dbname=bacterio_upnp user=pharmacien host=127.0.0.1 password=zac");
        if ($a==false)
        {
	          echo "problème de connexion<br>";
	          exit;
        }

        $date_debut=$_POST['date_debut'];
        $date_fin=$_POST['date_fin'];
        $classe=$_POST['classe'];
        $type=$_POST['type'];
        $conf=$_POST['conf'];
        $rendu=$_POST['rendu'];

        echo "<h3>Filtres</h3>";

        echo "Plage des dates : ".$date_debut." => ".$date_fin."<br>";
        echo "classe : ".$classe."<br>";
        echo "type : ".$type."<br>";
        echo "Conformité : ".$conf."<br>";
        echo "Type de rendu : ".$rendu."<br>";
        echo "<p>";

        echo "<a href=prelevements_select.html>Modifier les filtres</a><br>";
        //condition if else pour pouvoir sélectionner les filtres % qui ne sont pas reconnus par posgresql.
        //Utilisation d'un LEFT OUTER JOIN pour séletionner tous les prélèvements et les résultats quand ils existent.
        if ($conf<>'%' || $rendu<>'%')
        {
            $question="SELECT * FROM (SELECT prelevements.id AS \"No\", prelevements.date_prelev AS date, point, type, description, classe, ufc, limite, CASE WHEN ufc<limite THEN 'oui' WHEN ufc>=limite THEN 'non' ELSE NULL END AS conformité, germe, type_rendu FROM prelevements LEFT OUTER JOIN resultats ON (resultats.id_prelev=prelevements.id), points_prelev, limites_classes WHERE prelevements.id_point=points_prelev.id AND points_prelev.id_class=limites_classes.id) AS x WHERE x.date BETWEEN '$date_debut' AND '$date_fin' AND x.classe LIKE '$classe' AND x.type LIKE '$type' AND x.conformité LIKE '$conf' AND x.type_rendu LIKE '$rendu' AND ufc IS NOT NULL ORDER BY \"No\" DESC;";
        }
        else
        {
            $question="SELECT * FROM (SELECT prelevements.id AS \"No\", prelevements.date_prelev AS date, point, type, description, classe, ufc, limite, CASE WHEN ufc<limite THEN 'oui' WHEN ufc>=limite THEN 'non' ELSE NULL END AS conformité, germe, type_rendu FROM prelevements LEFT OUTER JOIN resultats ON (resultats.id_prelev=prelevements.id), points_prelev, limites_classes WHERE prelevements.id_point=points_prelev.id AND points_prelev.id_class=limites_classes.id) AS x WHERE x.date BETWEEN '$date_debut' AND '$date_fin' AND x.classe LIKE '$classe' AND x.type LIKE '$type' ORDER BY \"No\" DESC;";
        }

        $reponse=pg_query($a, $question);
        if ($reponse==false)
        {
	          echo "problème<br>";
        }

        echo "<p>";

        $colonnes=pg_num_fields($reponse);
        $lignes=pg_numrows($reponse);

        echo "<table border id=#prelevements><caption>Table des prélèvements réalisés et des résultats</caption>";
        echo "<tr>";
        for ($i=0; $i<$colonnes;$i++)
        {
	          echo "<th>".pg_field_name($reponse, $i)."</th>";
        }
        echo"</tr>";

        for ($j=0; $j<$lignes; $j++)
        {
	          echo "<tr>";
	          $uneligne=pg_fetch_array($reponse,$j);
	          echo "<td>".$uneligne['No']."</td>
                  <td>".$uneligne['date']."</td>
                  <td>".$uneligne['point']."</td>
                  <td>".$uneligne['type']."</td>
                  <td>".$uneligne['description']."</td>
                  <td>".$uneligne['classe']."</td>
                  <td>".$uneligne['ufc']."</td>
                  <td>".$uneligne['limite']."</td>
                  <td>".$uneligne['conformité']."</td>
                  <td>".$uneligne['germe']."</td>
                  <td>".$uneligne['type_rendu']."</td>";
	          echo "</tr>";
        }

        echo "</table>";

        ?>


    </body>
</html>
