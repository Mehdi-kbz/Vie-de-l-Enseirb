<?php
session_start();
include("config.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un commentaire</title>
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

            .login-box {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 400px;
            padding: 40px;
            transform: translate(-50%, -50%);
            background: rgba(0,0,0,.5);
            box-sizing: border-box;
            box-shadow: 0 15px 25px rgba(0,0,0,.6);
            border-radius: 10px;
            }

            .login-box h2 {
            margin: 0 0 30px;
            padding: 0;
            color: #fff;
            text-align: center;
            }

            .login-box .user-box {
            position: relative;
            }
            
            .login-box .user-box input {
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

            .login-box .user-box textarea {
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

            .login-box form {
            text-align: center; 
            }

            .login-box form button {
            display: inline-block;
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
<body>
    <div class="container">
        <h1 class="mt-5 mb-4">Ajouter un commentaire</h1>
        <?php
        
        if(isset($_SESSION["pseudo"])) {
            $pseudo = $_SESSION["pseudo"];
        } else {
            $pseudo = "";
            http_response_code(302); 
        }

        // Update $_SESSION['pseudo'] with the new pseudo value
         $_SESSION['pseudo'] = $pseudo;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Check if the "pseudo" field is too long
            if (strlen($_POST["pseudo"]) > 50) {
                echo '<div class="alert alert-danger" role="alert">Pseudo is too long.</div>';
                http_response_code(400); 
                exit(); 
            }

            // Check if the "vde_id" parameter is missing
            if (empty($_POST["vde_id"])) {
                echo '<div class="alert alert-danger" role="alert">Veuillez spécifier l\'identifiant de la VdE.</div>';
                http_response_code(400); 
                exit(); 
            }

            $vde_id = $_POST["vde_id"];
            $db_host = $config["db_host"];
            $db_user = $config["db_user"];
            $db_password = $config["db_password"];
            $db_base = $config["db_base"];

            $link = mysqli_connect($db_host, $db_user, $db_password);
            mysqli_select_db($link, $db_base);

            $check_sql = "SELECT id FROM Vde WHERE id = ?";
            $check_stmt = mysqli_prepare($link, $check_sql);
            mysqli_stmt_bind_param($check_stmt, "i", $vde_id);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);

            if(mysqli_stmt_num_rows($check_stmt) == 0) {
                // VdE does not exist, send 404 response code
                mysqli_stmt_close($check_stmt);
                mysqli_close($link);
                http_response_code(404);
                echo "VdE non trouvée.";
                exit();
            }

            mysqli_stmt_close($check_stmt);

            // If the VdE exists, proceed with adding the comment
            $pseudo = $_POST["pseudo"]; 
            $comment = $_POST["comment"];

            $sql = "INSERT INTO Comments (vde_id, pseudo, content, date) VALUES (?,?,?, NOW())";
            $stmt = mysqli_prepare($link, $sql);
            mysqli_stmt_bind_param($stmt, "iss", $vde_id, $pseudo, $comment);

            if (mysqli_stmt_execute($stmt)) {
                header("Location: show_vde.php?id=$vde_id");
                exit();
            } else {
                echo '<div class="alert alert-danger" role="alert">Erreur lors de l\'ajout du commentaire.</div>';
            }

            mysqli_stmt_close($stmt);
            mysqli_close($link);
            // Update $_SESSION['pseudo'] with the new pseudo value
            $_SESSION['pseudo'] = $_POST["pseudo"];
        } elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
            // sending a 302 status code for GET requests
            header("Location: index.php", true, 302);
            http_response_code(404);
            exit();
        }
        ?>
        <a href="index.php" class="btn btn-primary">Retour au menu</a>
    </div>
</body>
</html>