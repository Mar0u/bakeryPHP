<?php
include_once 'Classes/Database.php';
include_once 'Classes/User.php';
include_once 'Classes/UserManager.php';
include_once 'Classes/RegistrationForm.php';

//if (isset($_SESSION['key']))
//    header("location:userLogged.php");

$db = new Database("localhost", "root", "", "bakery");
$um = new UserManager();
session_start();
$db->inquiry("CREATE TABLE IF NOT EXISTS `logged_in_users` (
`sessionId` varchar(100) NOT NULL,
`userId` int(11) NOT NULL,
`lastUpdate` datetime NOT NULL,
PRIMARY KEY (`sessionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$user_id = $um->getLoggedInUser($db, session_id());

if ($db->checkIfUseridAdmin($user_id) == 2)
    header("location:adminLogged.php");
else if ($db->checkIfUseridAdmin($user_id) == 1)
    header("location:userLogged.php");

if (filter_input(INPUT_GET, "action") == "logout") {
    $um->logout($db);
}
?>

<html>

<head>
    <title>Local Delights Bakery</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/png" href="pictures/croissant_7311454.png">
    <style>
        body {
            font-family: "Times New Roman", Georgia, Serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: "Playfair Display";
            letter-spacing: 5px;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>

<body>

    <div class="NAVI">
        <div class="BAR WHITE PADDING" style="letter-spacing:4px;">
            <a href="index.php#home" class="TOP BUTTON">"Local Delights" Bakery</a>
            <div class="RIGHT HIDESMALL">
                <a href="index.php#about" class="TOP BUTTON">About us</a>
                <a href="index.php#menu" class="TOP BUTTON">Seasonal</a>
                <a href="index.php#order" class="TOP BUTTON">Order</a>
                <a href="index.php#contact" class="TOP BUTTON">Find us</a>
            </div>
        </div>
    </div>

    <div class="CONTENT" style="max-width:1100px">

        <div class="ROW PADDING64">
            <div class="COL A1 PADDINGLARGE">
                <h1 class="CENTER">REGISTER</h1>
                <h5 class="CENTER">
                    <button class="button" id="button" onclick="location.href = 'processRegister.php'"
                        style="width:100%;text-align:center;border: 2px solid #e1b78e; height:50px;background-color: transparent;">
                        >> Create an account << </button>
                </h5>
            </div>


            <div class="COL A1 PADDINGLARGE">
                <h1 class="CENTER">SIGN IN</h1><br>
                <h5 class="CENTER">
                    <button class='button' id="return"
                        style="display:none;width:100%;text-align:center;height:50px;background-color: transparent;">Log
                        in instead</button>

                    <?php
                    if (filter_input(INPUT_POST, "login")) {
                        $userId = $um->login($db, NULL, NULL);
                        if ($userId > 0) {

                            if ($db->checkIfUseridAdmin($userId) == 2)
                                header("location:adminLogged.php");
                            else
                                header("location:userLogged.php");
                        } else {
                            echo "<p>Invalid username or password</p>";
                            echo '<div id="loginForm" style="display:block">';
                            $um->loginForm();
                            echo '</div>';
                        }
                    } else {
                        echo '<div id="loginForm" style="display:block">';
                        $um->loginForm();
                        echo '</div>';
                    }
                    ?>
                </h5>

            </div>
        </div>
    </div>

    <footer class="CENTER LIGHTGREY PADDING32">
        <p>"Local Delights" Bakery 2022</p>
    </footer>

</body>

</html>