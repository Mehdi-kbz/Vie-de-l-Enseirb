<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($vde['title'] ?? 'VdE non trouvée'); ?></title>
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

          .commentaires {
            position: relative;
            top: 60%;
            left: 50%;
            width: 400px;
            padding: 40px;
            margin-top: 70px;
            transform: translate(-50%, -50%);
            background: rgba(0,0,0,.5);
            box-sizing: border-box;
            box-shadow: 0 15px 25px rgba(0,0,0,.6);
            border-radius: 10px;
            text-align: center;

            }

            .commenaires h2 {
            margin: 0 0 30px;
            padding: 0;
            text-align: center;
            }

           
            
            .commentaires .user-box input {
            width: 100%;
            padding: 10px 0;
            font-size: 16px;
            color: #fff;
            margin-bottom: 30px;
            border: none;
            border-bottom: 1px solid #fff;
            outline: none;
            background: transparent;
            }

            .commentaires .user-box textarea {
            width: 100%;
            padding: 10px 0;
            font-size: 16px;
            color: #fff;
            margin-bottom: 30px;
            border: none;
            border-bottom: 1px solid #fff;
            outline: none;
            background: transparent;
            }
            
            .login-box .user-box label {
            position: absolute;
            top:0;
            left: 0;
            padding: 10px 0;
            font-size: 16px;
            color: #fff;
            pointer-events: none;
            transition: .5s;
            }

            .login-box .user-box input:focus ~ label,
            .login-box .user-box input:valid ~ label {
            top: -20px;
            left: 0;
            color: #03e9f4;
            font-size: 12px;
            }


            .login-box .user-box textarea:focus ~ label,
            .login-box .user-box textarea:valid ~ label {
            top: -20px;
            left: 0;
            color: #03e9f4;
            font-size: 12px;
            }

            .commentaies form {
            text-align: center; 
            }

            .commentaires form button {
            padding: 10px 20px;
            color: #03e9f4;
            background: #000000;
            font-size: 16px;
            text-decoration: none;
            text-transform: uppercase;
            overflow: hidden;
            transition: .5s;
            margin-top: 40px;
            letter-spacing: 4px;
            }
    </style>
</head>
<div class="container">

        <?php

        session_start();

        require_once 'config.php';

        // Check if the 'id' parameter is set in the URL
        if (!isset($_GET['id'])) {
            http_response_code(404);
            echo "VdE non trouvée.";
            exit();
        }

        // Extract the 'id' parameter from the URL and sanitize it
        $vde_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

        // Validate the 'id' parameter
        if ($vde_id === false || $vde_id === null || $vde_id===1234) {
            http_response_code(404);
            echo "VdE non trouvée.";
            exit();
        }

        // Connect to the database
        $conn = mysqli_connect($config['db_host'], $config['db_user'], $config['db_password'], $config['db_base']);

        if (!$conn) {
            http_response_code(404);
            echo "VdE non trouvée.";
            exit();
        }

        $sql = "SELECT id, pseudo, content, date 
        FROM Comments
        WHERE vde_id = ?
        ORDER BY date DESC";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $vde_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);


        if (mysqli_num_rows($result) === 0) {
            echo "<p>Aucun commentaire trouvé.</p>";
        } else {
            // Output comments
            $last_comment = null; // variable to store the last comment
            while ($row = mysqli_fetch_assoc($result)) {
                $last_comment = $row; // Store the last comment in the variable
                echo "<br>";
                echo "<br>";

                echo "<div class='commentaires'>";
                echo "<hr>";
                echo "<p class='titre'>Pseudo : " . htmlspecialchars($row["pseudo"]) . "</p>";
                echo "<p class='contenu'>" . nl2br(htmlspecialchars($row["content"])) . "</p>";
                echo "<p class='date'>Publié le : " . htmlspecialchars($row["date"]) . "</p>";
                echo "</div>";
            }
            
            // Update $_SESSION['pseudo'] with the pseudo of the last commenter
            if ($last_comment !== null) {
                $_SESSION['pseudo'] = $last_comment["pseudo"];      
            }
            
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        ?>

        <h2 >Ajouter un commentaire</h2>

        <form action="add_comment.php" method="post">
            <input type="hidden" name="vde_id" value="<?php echo htmlspecialchars($vde_id); ?>">
            <div class="form-group">
                <label for="pseudo">Pseudo</label>
                <input type="text" class="form-control" name="pseudo" id="pseudo" maxlength="50" value="<?= isset($_SESSION['pseudo']) ? htmlspecialchars($_SESSION['pseudo']) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="comment">Commentaire</label>
                <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
    </div>
</body>
</html>