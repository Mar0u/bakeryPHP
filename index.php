<!DOCTYPE html>

<?php
include_once 'Classes/Database.php';
include_once 'Classes/User.php';
include_once 'Classes/UserManager.php';
include_once 'Classes/OrderManager.php';

session_start();
$_SESSION['key'] = 1;
$um = new UserManager();
$db = new Database("localhost", "root", "", "bakery");

$user_id = $um->getLoggedInUser($db, session_id());

if ($db->checkIfUseridAdmin($user_id) == 2)
    header("location:adminLogged.php");

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
            <a href="#home" class="TOP BUTTON">"Local Delights" Bakery</a>
            <div class="RIGHT HIDESMALL">
                <a href="#about" class="TOP BUTTON">About us</a>
                <a href="#menu" class="TOP BUTTON">Seasonal</a>
                <a href="#order" class="TOP BUTTON">Order</a>
                <a href="#contact" class="TOP BUTTON">Find us</a>
                <a href="processLogin.php" class="TOP BUTTON">Account</a>
            </div>
        </div>
    </div>

    <header class="CONTAINER CONTENT WIDE" style="max-width:1600px;min-width:500px" id="home">
        <img class="IMG" src="pictures/bakery-banners-with-pastries-bread/263979-P4YRHJ-46—kopia—kopia.jpg" alt="Baner"
            width="1600" height="800">
    </header>

    <div class="CONTENT" style="max-width:1100px">
        <div class="ROW PADDING64" id="about">
            <div class="COL A1 PADDINGLARGE HIDESMALL">
                <img src="https://media.istockphoto.com/photos/bakery-owner-giving-food-package-to-customer-picture-id1317782863?b=1&k=20&m=1317782863&s=170667a&w=0&h=IxuKGkjMmCQwFV-32BCEwmInUbsG_h2Dh3NHcfZUH94="
                    class="ROUND IMG OPACITYMIN" alt="Obrazek" width="600" height="750">
            </div>

            <div class="COL A1 PADDINGLARGE">
                <h1 class="CENTER">About us</h1><br>
                <h5 class="CENTER">Bakery with Traditions</h5>
                <p class="LARGE">"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                    incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                    ullamco laboris </p>
                <p class="LARGE TEXTGRAY HIDEMEDIUM">nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in
                    reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint
                    occaecat cupidatat non proident.</p>
            </div>
        </div>

        <hr>

        <div class="ROW PADDING64" id="menu">
            <div class="COL LLLLLL16 PADDINGLARGE">
                <h1 class="CENTER">Seasonal products</h1><br>

                <h4>Cake</h4>
                <p class="TEXTGRAY">Lorem ipsum dolor sit amet 2.50 zł</p><br>

                <h4>Another cake I guess</h4>
                <p class="TEXTGRAY">Sint occaecat cupidatat non proident 1.00 zł</p><br>

                <h4>Lorem ipsum</h4>
                <p class="TEXTGRAY">Doloremque laudantium, totam rem aperiam 7.50 zł</p><br>

                <h4>Dolor sit</h4>
                <p class="TEXTGRAY">Ut enim ad minima veniam, quis nostrum exercitationem 7.50zł</p><br>

                <h4>Consectetur </h4>
                <p class="TEXTGRAY">Sed ut perspiciatis unde omnis iste natus 8.50 zł</p>
            </div>

            <div class="COL LLLLLL16 PADDINGLARGE">
                <img src="pictures/cake-5946777_960_720—kopia.jpg" class="ROUND IMG OPACITYMIN" alt="Menu"
                    style="width:100%">
            </div>
        </div>

        <div class="PADDINGLARGE PADDING64" id="order">
            <h1 class="CENTER">Order</h1><br>
            <p> Every morning you can pick up your ordered products consectetur adipiscing elit, sed do eiusmod tempor
                incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                laboris nisi ut aliquip ex ea commodo consequat.</p>
            <?php
            $om = new OrderManager();
            $om->orderForm();
            ?>
        </div>

        <div class="ROW PADDING64" id="contact">
            <div class="COL LLLLLL16 PADDINGLARGE">
                <h1 class="CENTER">Contact us</h1><br>
                <p>Contact us by phone or email.</p>
                <p class="BLUEGREY LARGE" id="phoneNr" onclick="getData('phoneNr')"><b>Show phone number</b></p>
                <p class="BLUEGREY LARGE"><b><a href="mailto:bulka@bulka.pl">bulka@bulka.pl</a></b></p>
                <p class="BLUEGREY LARGE"><b>ul. Bułkowa 14, Lublin</b></p>
            </div>

            <div class="COL LLLLLL16  map-responsive">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d4996.226124951755!2d22.5460232!3d51.2354126!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4722577729316bd9%3A0x442236391b743bc!2sPolitechnika%20Lubelska%2C%2020-618%20Lublin!5e0!3m2!1spl!2spl!4v1655071113698!5m2!1spl!2spl"
                    width="600" height="450" style="border:0;" allowfullscreen=""
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>

    <footer class="CENTER LIGHTGREY PADDING32">
        <p>"Local Delights" Bakery 2022</p>
    </footer>

    <script> //<script type="text/javascript">
        function getData(id) {
            fetch("phoneNr.txt")
                .then(response => response.text())
                .then(data => {
                    document.getElementById(id).innerHTML = data;
                })
                .catch(error => {
                    console.error("Error: ", error);
                });
        }

        var prices = [2.5, 1, 2];

        function click_handler() {
            var sum = 0;
            var products = [];
            var error = '';

            for (var i = 0; i < prices.length; i++) {
                var product = parseFloat($('#product' + i).val());
                if (isNaN(product) || !Number.isInteger(product)) {
                    error = "Please provide the correct number";
                    break;
                }
                products[i] = product;
            }

            var today = new Date().toISOString().slice(0, 10);
            var date = document.getElementById('date').value;
            if (new Date(date) <= new Date(today) || date === "") {
                error = "It must be a future date";
            }

            for (var i = 0; i < prices.length; i++)
                sum = sum + products[i] * prices[i];

            if (sum === 0) {
                error = "Choose goods";
                document.getElementById("continue").disabled = true;
            }

            if (error === '') {
                $('#sum').val(sum + " zł");
                $('#error').val("");
                document.getElementById("continue").disabled = false;
            } else {
                $('#error').val(error);
                document.getElementById("continue").disabled = true;
            }
        }

        document.getElementById("product0").addEventListener("change", click_handler, false);
        document.getElementById("product1").addEventListener("change", click_handler, false);
        document.getElementById("product2").addEventListener("change", click_handler, false);
        document.getElementById("date").addEventListener("change", click_handler, false);
    </script>
</body>

</html>

<?php
include_once 'Classes/Database.php';
include_once 'Classes/User.php';
include_once 'Classes/UserManager.php';
include_once 'Classes/RegistrationForm.php';

if (!isset($_SESSION['key']))
    session_start();

$db = new Database("localhost", "root", "", "bakery");
$um = new UserManager();

$db->inquiry("CREATE TABLE IF NOT EXISTS `logged_in_users` (
`sessionId` varchar(100) NOT NULL,
`userId` int(11) NOT NULL,
`lastUpdate` datetime NOT NULL,
PRIMARY KEY (`sessionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

if (filter_input(INPUT_GET, "action") == "logout") {
    $um->logout($db);
}

if (isset($_POST['continue'])) {
    if (isset($_SESSION['key']))
        $om->addOrder($db);
    echo "<script>window.location.href = 'processLogin.php';</script>";
}
?>