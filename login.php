<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Login Pagina</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
</head>

<style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background-color: #f8f8f8;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 15px 0;
            width: 100%;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        header h1 {
            margin: 0;
            font-size: 1.5em;
        }

        .buttons {
            display: flex;
            align-items: center;
        }

        .buttons a {
            margin-left: 20px;
            text-decoration: none;
        }

        header button {
            font-size: 1em;
            background-color: #555;
            color: #fff;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        header button:hover {
            background-color: #777;
        }

        .loginContainer {
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin-top: 50px;
        }

        .loginContainer input[type="text"],
        .loginContainer input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .loginContainer button {
            width: calc(100% - 20px);
            padding: 10px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .loginContainer button:hover {
            background-color: #555;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
        }

        .error-message {
            font-size: 12px;
            color: red;
            margin-top: 5px;
        }
</style>

<?php
    $errorMessage = '';

    if(isset($_POST['userName']) && isset($_POST['passWord']))
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "portfoliodatabase";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        echo "Connected successfully";

        $name = false;
    
        $loginUserName = mysqli_query($conn, "SELECT `username` FROM `userinfo` WHERE 1");
        $loginPassWord = mysqli_query($conn, "SELECT `password` FROM `userinfo` WHERE 1");

        if(mysqli_num_rows($loginUserName) > 0) {
            while($row = mysqli_fetch_assoc($loginUserName)) {
                if ($_POST['userName'] == $row['username']) {
                    $name = true;
                    break;
                }
            }
        }

        if(mysqli_num_rows($loginPassWord) > 0 && $name) {
            while($row = mysqli_fetch_assoc($loginPassWord)) {
                if ($_POST['passWord'] == $row['password']) {
                    header("Location: adminpage.php");
                    exit();
                }
            }
        }

        $errorMessage = 'Incorrect username or password';
    }
?>

<body>
    <header>
        <a href="http://localhost/ThuisOpdracht/index.php"><button>Home</button></a>
        <h1>Login Pagina</h1>
        <div class="buttons">
            <a href=""><button>Contact</button></a>
        </div>
    </header>

    <div class="loginContainer">
        <form method="POST">
            <p>Username: <input name="userName" type="text"></p>
            <p>Password: <input name="passWord" type="password"></p>
            <?php if (!empty($errorMessage)): ?>
                <span class="error-message"><?php echo $errorMessage; ?></span>
            <?php endif; ?>
            <button>Login</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2024 Copyright M. Vokkert</p>
    </footer>
</body>
</html>
