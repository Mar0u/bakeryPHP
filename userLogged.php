<!DOCTYPE html>
<?php
include_once 'Classes/Database.php';
include_once 'Classes/User.php';
include_once 'Classes/UserManager.php';
include_once 'Classes/OrderManager.php';

session_start();
$_SESSION['key'] = 1;

$db = new Database("localhost", "root", "", "bakery");
$um = new UserManager();

$user_id = $um->getLoggedInUser($db, session_id());
if ($user_id == -1) {
    session_destroy();
    header("location:processLogin.php");
}

if ($db->checkIfUseridAdmin($user_id) == 2)
    header("location:adminLogged.php");
?>
<html>

<head>
    <title>Your account</title>
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
                <a href="index.php?action=logout" class="TOP BUTTON">Logout</a>
            </div>
        </div>
    </div>
    <div class="CONTENT" style="max-width:1100px">
        <div class="ROW PADDING64">
            <h1>Hello,
                <?php
                echo $db->select("SELECT * FROM users WHERE id='$user_id'", ["fullName"]);
                ?>
            </h1>
            <button class="TOP BUTTON CENTER"
                style="font-size: 110%;font-family: 'Playfair Display';letter-spacing: 5px; border: 2px solid #e1b78e;"
                onclick='location.href = "index.php#order";'>New order</button>
        </div>

        <form action="" method="post"><button class="TOP BUTTON CENTER"
                style="font-size: 110%;font-family: 'Playfair Display';letter-spacing: 5px;" name="showChangingPassword"
                id="showChangingPassword">▼ Change password</button></form>

        <div id="formChangingPassword" style="display:none">
            <form action="" method="POST">
                <label style="width:98%;">Current password:</label>
                <input class="CENTER" type="password" name="current_password" value="" style="width:100%;" /></br></br>
                <label style="width:98%;">New password:</label>
                <input class="CENTER" type="password" name="new_password" value="" style="width:100%;" /></br></br>
                <label style="width:98%;">Confirm new password:</label>
                <input class="CENTER" type="password" name="confirm_new_password" value=""
                    style="width:100%;" /></br></br>
                <input class="TOP BUTTON CENTER"
                    style="width:48%;font-family: 'Playfair Display';letter-spacing: 5px; border: 2px solid #e1b78e;"
                    type="submit" name="changePassw" value=">> Change password <<" />
                <button class="TOP BUTTON CENTER" style="width:48%;font-family: 'Playfair Display';letter-spacing: 5px;"
                    onclick="location.reload();">Cancel</button>
            </form>
        </div>

        <form action="" method="post"><button class="TOP BUTTON CENTER"
                style="font-size: 110%;font-family: 'Playfair Display';letter-spacing: 5px;" name="deleteAccountShow"
                id="deleteAccountShow">▼ Delete account</button></form>

        <div id="deleteForm" style="display:none">
            <form action="" method="POST">
                <label style="width:98%;">Current password:</label>
                <input type="password" name="passwrd" value="" class="CENTER"
                    style="width:100%;font-family: 'Playfair Display';letter-spacing: 5px;" />
                <input type="submit" name="deleteAccount" id="deleteAccount" value=">> Delete account <<"
                    class="TOP BUTTON CENTER"
                    style="width:48%;font-family: 'Playfair Display';letter-spacing: 5px; border: 2px solid #e1b78e;" />
                <button class="TOP BUTTON CENTER" style="width:48%;font-family: 'Playfair Display';letter-spacing: 5px;"
                    onclick="location.reload();">Cancel</button>
            </form>
        </div>

        <form action="" method="POST">
            <button class="TOP BUTTON CENTER"
                style="font-size: 110%;width:100%;font-family: 'Playfair Display';letter-spacing: 5px; display: none"
                name="deleteAccountConfirm" id="deleteAccountConfirm">I am sure, delete my account</button>
        </form>
        <?php
        if (isset($_POST['deleteAccountShow'])) {
            echo '<script>
                        document.getElementById("deleteForm").style.display = "block";
                    </script>';
        }

        if (isset($_POST['deleteAccount'])) {

            $password = $_POST['passwrd'];

            if ($db->checkPassword($user_id, $password) === 1) {
                echo '<script>
                        document.getElementById("deleteAccountConfirm").style.display = "block";

                    </script>';
            } else {
                echo "<script>
                    alert('Incorrect password');
                    </script>";
            }
        }

        if (isset($_POST['deleteAccountConfirm'])) {

            $db->deleteUser($user_id);

            session_destroy();

            echo "<script>
               window.location.replace('index.php?action=logout');
                    </script>";
        }
        ?>

        <?php
        if (isset($_POST['showChangingPassword'])) {
            echo '<script>
            document.getElementById("formChangingPassword").style.display = "block";
    </script>';
        }
        if (isset($_POST['changePassw'])) {

            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_new_password'];

            if ($new_password == $confirm_password) {

                if ($db->checkPassword($user_id, $current_password) === 1) {
                    $result = $db->changePassword($user_id, $current_password, $new_password);
                    echo $result;
                    echo "<script>
                    alert('Haslo zmienione');
                    </script>";
                } else {
                    echo "<script>
                    alert('Incorrect password');
                    </script>";
                }
            } else {
                echo "New password and confirm password must be the same";
            }
        }
        ?>

        <div class="COL LLLLLL16 PADDINGLARGE" style="width:100%">
            <br>
            <h5 class="CENTER">Your orders</h5>
            <p class="LARGE">

                <?php
                $om = new OrderManager();

                echo '<table style="width: 100%;">
                <tr class="LARGE">
                <th>Order ID</th>
                    <th>Product 1</th>
                    <th>Product 2</th>
                    <th>Product 3</th>
                    <th>Additional Info</th>
                    <th>Order Date</th>
                    <th>Realisation Date</th>
                    <th>Status</th>
                </tr>';
                $orders = $om->showOneUserOrders($db, $user_id);
                foreach ($orders as $order) {
                    echo "<tr style='text-align:center;'>";
                    echo "<td>" . $order[0] . "</td>";
                    echo "<td>" . $order[1] . "</td>";
                    echo "<td>" . $order[2] . "</td>";
                    echo "<td>" . $order[3] . "</td>";
                    echo "<td>" . $order[4] . "</td>";
                    echo "<td>" . $order[5] . "</td>";
                    echo "<td>" . $order[6] . "</td>";

                    $color = 'black';
                    if ($order[7] === 0) {
                        $order[7] = "in preparation";
                    } else if ($order[7] === 1) {
                        $order[7] = "ready for collection";
                        $color = 'green';
                    } else if ($order[7] === 2) {
                        $order[7] = "collected";
                    } else if ($order[7] === 3) {
                        $order[7] = "not collected";
                        $color = 'red';
                    }
                    echo "<td style='color:" . $color . "'>" . $order[7] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                ?>
            </p>
        </div>
        <button class="TOP BUTTON CENTER" style="width:100%;font-family: 'Playfair Display';letter-spacing: 5px;"
            onclick="window.location.replace('index.php?action=logout')">Logout</button>
    </div>

    <footer class="CENTER LIGHTGREY PADDING32">
        <p>"Local Delights" Bakery 2022</p>
    </footer>

</body>

</html>