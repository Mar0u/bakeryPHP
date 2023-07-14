<?php
include_once 'Classes/Database.php';
include_once 'Classes/User.php';
include_once 'Classes/UserManager.php';
include_once 'Classes/RegistrationForm.php';

if (isset($_SESSION['key']))
    header("location:userLogged.php");

$db = new Database("localhost", "root", "", "bakery");
$um = new UserManager();

$db->inquiry("CREATE TABLE IF NOT EXISTS `logged_in_users` (
`sessionId` varchar(100) NOT NULL,
`userId` int(11) NOT NULL,
`lastUpdate` datetime NOT NULL,
PRIMARY KEY (`sessionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
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
                    <div id="registrationForm">
                        <?php $rf = new RegistrationForm(); ?>
                    </div>

                    <?php
                    if (isset($_POST['submitReg'])) {
                        echo '<script>document.getElementById("registrationForm").style.display = "block";document.getElementById("button").style.display = "none";</script>';
                        if ($_POST['password'] === $_POST['password2']) {
                            if (
                                filter_input(
                                    INPUT_POST,
                                    'submitReg',
                                    FILTER_SANITIZE_FULL_SPECIAL_CHARS
                                )
                            ) {
                                $user = $rf->checkUser();

                                if ($db->checkIfLoginTaken($_POST['userName'])) {
                                    die("Login taken");
                                }

                                if ($db->checkIfEmailTaken($_POST['email'])) {
                                    die("Email is already in use");
                                }

                                if ($user !== NULL) {
                                    $user->saveToDB($db);
                                    $userId = $um->login($db, $_POST['userName'], $_POST['password']);
                                    if ($userId > 0) {

                                        if ($db->checkIfUseridAdmin($userId) == 2)
                                            header("location:adminLogged.php");
                                        else
                                            header("location:userLogged.php");
                                    }
                                }
                            } else {
                                echo "<p>Incorrect data</p>";
                            }
                        } else
                            echo "<p>Passwords do not match</p>";
                    }
                    ?>

                </h5>
            </div>

            <div class="COL A1 PADDINGLARGE">
                <h1 class="CENTER">SIGN IN</h1><br>
                <h5 class="CENTER">
                    <button class='button' onclick="location.href = 'processLogin.php'" id="return"
                        style="width:100%;text-align:center;height:50px;background-color: transparent;">Log in
                        instead</button>
                </h5>
            </div>
        </div>
    </div>

    <footer class="CENTER LIGHTGREY PADDING32">
        <p>"Local Delights" Bakery 2022</p>
    </footer>

</body>

</html>