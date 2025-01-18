<?php
session_start();

require_once 'config.php';

// Function to connect to the database
function connectDatabase($config) {
    $conn = mysqli_connect($config['db_host'], $config['db_user'], $config['db_password'], $config['db_base']);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $conn;
}

// Function to insert VdE into the database
function insertVde($conn, $pseudo, $content) {
    $sql = "INSERT INTO Vde (pseudo, content, date) VALUES (?, ?, NOW())";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $pseudo, $content);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Function to handle form submission
function handleFormSubmission($config) {
    // Check if the form has been submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        http_response_code(302);

        // Extract and sanitize the form data
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $content = htmlspecialchars($_POST['content']);


         // Update $_SESSION['pseudo'] with the new pseudo value
         $_SESSION['pseudo'] = $pseudo;
         
        // Check if the "pseudo" field is too long
        if (strlen($pseudo) > 50) {
            http_response_code(400); // Bad Request
            $error = 'Pseudo is too long.';
        } else {
            // Validate the input data
            if (empty($pseudo) || empty($content)) {
                http_response_code(400); // Bad Request
                $error = 'Please fill in all fields.';
            } else {
                // Connect to the database
                $conn = connectDatabase($config);

                // Insert the new VdE into the database using prepared statements
                insertVde($conn, $pseudo, $content);

                // Close the database connection
                mysqli_close($conn);

                // Redirect to the homepage with a 302 status code
                header('Location: index.php', true, 302);
                exit();
            }
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
        // Send a 200 OK response for GET requests
        http_response_code(200);
    }
}

// Call the function to handle form submission
handleFormSubmission($config);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Vie de l'Enseirb</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
            html {
            height: 100%;
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
            color: #fff;
            font-size: 12px;
            }


            .login-box .user-box textarea:focus ~ label,
            .login-box .user-box textarea:valid ~ label {
            top: -20px;
            left: 0;
            color: #fff;
            font-size: 12px;
            }

            .login-box form {
            text-align: center; 
            }

            .login-box form button {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background: #000000;
            font-size: 16px;
            text-decoration: none;
            text-transform: uppercase;
            overflow: hidden;
            transition: .5s;
            margin-top: 40px;
            letter-spacing: 4px;
            }
            
            body {
                height: 100vh;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(#141e30, #243b55);
            }
    </style>
</head>
<body>
<div class="container">
    <div class="login-box">
        <h2>Ajouter une Vie de l'Enseirb</h2>
        <?php if (isset($error)): ?>
            <p class="error-message"><?= $error ?></p>
        <?php endif; ?>
        <form method="post" action="add_vde.php">
            <div class="user-box">
            <input type="text" class="form-control" name="pseudo" id="pseudo" maxlength="50" value="<?= isset($_SESSION['pseudo']) ? htmlspecialchars($_SESSION['pseudo']) : '' ?>" required>
                <label for="pseudo">Pseudo</label>
            </div>
            <div class="user-box">
                <textarea class="form-control" name="content" id="content" required><?= isset($_POST['content']) ? htmlspecialchars($_POST['content']) : '' ?></textarea>
                <label for="content">Vde</label>
            </div>
            <button type="submit" class="btn btn-primary">Soumettre</button>
        </form>
    </div>
</div>
</body>
</html>
