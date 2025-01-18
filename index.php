<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8"> 
    <title>Accueil - Vie d'Enseirb</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
            html {
            height: 100%;
            }
            body {
            margin:0;
            padding:0;
            font-family: sans-serif;
            background: linear-gradient(#141e30, #243b55);
            }

            .Title  {
            color: white;
            text-align:center;            
            padding: 40px;
            margin: 10px auto;
            }

            #Liste_vde {
            color: white;
            text-align:left;            
            padding: 20px;
            font-size: 30px;             
          }
          .blabla {
            text-align: center;
          }

          .blabla p {
            color: white;
            padding: 20px;
          }

          .blabla h2 {
            color: white;
            padding: 20px;
          }
            .container {
              text-align: center;
            }

          h2 {
            font-size: 25px;
          }

          p {
            font-size: 15px;
          }
           
           

            #ajouter-button {
            padding: 15px 20px;
            background: rgba(0,0,0,.5);
            font-size: 14px;
            text-decoration: none;
            transition: .5s;
            letter-spacing: 4px;
            }

    </style>
  
  </head>
  <body>
  
  <div class="container">
    <h1 class="Title">Accueil - Vie d'Enseirb</h1>
    <hr>
    <h2 id="Liste_vde" >Liste des Vies d'Enseirb</h2>    
    <a href="add_vde.php" id="ajouter-button" class="btn btn-primary mb-4" >Ajouter une Vie d'Enseirb</a>
          
  </div>
    <?php

      session_start();

      // Include configuration file
      require_once 'config.php';

      // Connect to the database
      $db = mysqli_connect($config['db_host'], $config['db_user'], $config['db_password'], $config['db_base']);

      if (!$db) {
        die("Connection failed: " . mysqli_connect_error());
      }

      // Retrieve total VdEs
      $sql_total_vdes = "SELECT COUNT(*) FROM Vde";
      $result_total_vdes = mysqli_query($db, $sql_total_vdes);
      $row_total_vdes = mysqli_fetch_array($result_total_vdes);
      $total_vdes = $row_total_vdes[0];

      // Calculate number of pages
      $paginate_by = $config["paginate_by"];
      $total_pages = ceil($total_vdes / $paginate_by);

      // Set current page
      $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
      $current_page = min($current_page, $total_pages);

     

      // Check if the requested page number exceeds the total number of pages
     /*if ($_GET['page'] > $total_pages) {
        http_response_code(404);
        echo "Page not found.";
        exit();
      }*/

      // Calculate offset
      $offset = ($current_page - 1) * $paginate_by;

      // Retrieve VdEs for current page
      $sql_vde_list = "SELECT * FROM Vde ORDER BY date DESC LIMIT $paginate_by OFFSET $offset";
      $result_vde_list = mysqli_query($db, $sql_vde_list);

      if (mysqli_num_rows($result_vde_list) > 0) {

        // Output data
        while ($row = mysqli_fetch_assoc($result_vde_list)) {
            $vde_id = $row['id'];   
            /*echo $vde_id;
            echo $row['pseudo'];
            echo $row['content'];
            echo $row['date'];    */   
            $pseudo = htmlspecialchars($row['pseudo']);
            $date = htmlspecialchars($row['date']);
          $content = nl2br($row['content']);
          /*echo $vde_id;
          echo $pseudo;
          echo $row['content'];
          echo $content;*/
            
            // Query to retrieve comment count for current VdE
            $sql_comment_count = "SELECT COUNT(*) AS comment_count FROM Comments WHERE vde_id = $vde_id";
            $result_comment_count = mysqli_query($db, $sql_comment_count);
            
            if ($result_comment_count) {
                $row_comment_count = mysqli_fetch_assoc($result_comment_count);
                $comment_count = htmlspecialchars($row_comment_count['comment_count']);
            } else {
                $comment_count = 0; 
                echo "Error fetching comment count: " . mysqli_error($db);
            }

          echo "<div class='blabla'>";
          echo "<hr>";
          echo "<h2>" . $pseudo . "</h2>";
          echo "<p class='contenu'>" . $content . "</p>";
          echo "<p class='date'>Publié le : " . $date . "</p>";
          echo "<a href='show_vde.php?id=" . htmlspecialchars($vde_id) . "' class=comment_count>" . $comment_count . " commentaires</a>";
          echo "</div>"; 

        }

      } 
      
      else {
          echo "Aucune Vde trouvée.";
      }    

          

      // Close database connection
      mysqli_close($db);

      // Display pagination links (if more than one page)
      if ($total_pages > 1) {
        echo "<nav class='pagination'>";
        for ($i = 1; $i <= $total_pages; $i++) {
          $page_url = "index.php?page=$i";
          $active_class = ($i === $current_page) ? 'active' : '';
          echo "<a href='$page_url' class='$active_class'>$i</a>";
        }
        echo "</nav>";
      }
    ?>

  </body>
</html>
