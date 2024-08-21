<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Admin Pagina</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        header {
            background-color: #333;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 10px 0;
        }

        header h1 {
            margin: 0;
        }

        .nav-links {
            margin-left: auto;
        }

        .nav-links a {
            text-decoration: none;
            color: #fff;
            padding: 0 10px;
        }

        .loginContainer {
            background-color: #f2f2f2;
            margin-top: 100px;
            padding: 20px;
            border-radius: 8px;
            width: 60%;
            margin-left: auto;
            margin-right: auto;
        }

        .loginContainer form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .loginContainer input[type="text"],
        .loginContainer input[type="file"] {
            width: 80%;
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .loginContainer button {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        footer {
            background-color: #333;
            color: #fff;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 10px 0;
        }

        footer p {
            margin: 0;
        }
    </style>
</head>
<?php
// Database connection code
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portfoliodatabase";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connectie gefaald: " . $conn->connect_error);
}

// Handle file upload
if (isset($_FILES['photo'])) {
    $photo = $_FILES['photo']['name']; // Get the file name
    $target_dir = "img/"; // Directory where images will be stored
    $target_file = $target_dir . basename($_FILES["photo"]["name"]); // Full path to the image

    // Move uploaded file to specified directory
    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars(basename( $_FILES["photo"]["name"])). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
} else {
    echo "No file uploaded.";
}

// Prepare and execute SQL query
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("INSERT INTO `projects`(`ProjectName`, `ProjectInfo`, `ProjectLink`, `ProjectPhoto`) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $projectName, $projectInfo, $link, $photo);

    // Set values from form inputs
    $projectName = $_POST['projectName'];
    $projectInfo = $_POST['projectInfo'];
    $link = $_POST['link'];
    $photo = $_FILES['photo']['name']; // Assuming 'photo' is the name attribute of your file input

    // Execute the query
    if ($stmt->execute()) {
        echo "Nieuw record succesvol aangemaakt.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<body>
    <header>
        <h1>Admin Pagina</h1>
        <div class="nav-links">
            <a href="http://localhost/ThuisOpdracht/index.php">Home</a>
            <a href="verwijder_project.php">Verwijder Projecten</a>
        </div>
    </header>

    <div class="loginContainer">
    <form method="POST" enctype="multipart/form-data">
    <p>Projectnaam: <input name="projectName" type="text"></p>
    <p>Projectinformatie: <input name="projectInfo" type="text"></p>
    <p>Link naar project: <input name="link" type="text"></p>
    <p>Foto: <input name="photo" type="file"></p>
    <button type="submit">Verstuur</button>
</form>

    </div>

    <footer>
        <p>&copy; 2024 Copyright M. Vokkert</p>
    </footer>
</body>
</html>
