<?php
$errorMessage = '';
$projectIds = [];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portfoliodatabase";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connectie gefaald: " . $conn->connect_error);
}

// Retrieve all project IDs and photo filenames
$idQuery = "SELECT id, ProjectPhoto FROM projects";
$result = $conn->query($idQuery);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $projectIds[$row['id']] = $row['ProjectPhoto']; // Store project ID and associated photo filename
    }
}

if(isset($_POST['projectId']))
{
    $projectId = $_POST['projectId'];

    // Check if the project ID exists
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM projects WHERE id = ?");
    $checkStmt->bind_param("i", $projectId);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        // Project ID exists, proceed with deletion
        // Delete all associated image files from the directory
        $photoToDelete = isset($projectIds[$projectId]) ? $projectIds[$projectId] : null;
        if ($photoToDelete) {
            $imageFiles = [
                "img/" . $photoToDelete,
                "img/Temp_" . $photoToDelete,
                "img/small_" . $photoToDelete
            ];

            foreach ($imageFiles as $file) {
                if (file_exists($file)) {
                    unlink($file); // Delete the file from the directory
                }
            }
        }

        // Delete the project from the database
        $stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->bind_param("i", $projectId);

        if ($stmt->execute()) {
            echo "Het project is verwijderd.";
        } else {
            $errorMessage = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $errorMessage = "Project ID bestaat niet.";
    }
}

$conn->close();
?>

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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
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

        .removeContainer {
            padding: 20px;
            width: 60%;
            margin-left: auto;
            margin-right: auto;
            background-color: #f2f2f2;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .removeContainer label {
            display: block;
            margin-bottom: 10px;
        }

        .removeContainer select {
            width: 100%;
            margin-bottom: 20px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .removeContainer button {
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

<body>
    <header>
        <h1>Admin Pagina</h1>
        <div class="nav-links">
            <a href="adminpage.php">Terug naar toevoegen</a>
        </div>
    </header>

    <div class="removeContainer">
        <form method="POST">
            <label for="projectId">Selecteer het Project ID om te verwijderen:</label>
            <select id="projectId" name="projectId">
                <?php foreach ($projectIds as $id => $photo): ?>
                    <option value="<?php echo $id; ?>"><?php echo $id; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Verwijder Project</button>
        </form>
        <?php if ($errorMessage): ?>
            <p>Error: <?php echo $errorMessage; ?></p>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2024 Copyright M. Vokkert</p>
    </footer>
</body>
</html>
