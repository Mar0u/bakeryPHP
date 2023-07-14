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

if ($db->checkIfUseridAdmin($user_id) != 2)
    header("location:userLogged.php");

if ($user_id == -1) {
    session_destroy();
    header("location:processLogin.php");
}

if ($db->checkIfUseridAdmin($user_id) == 1)
    header("location:userLogged.php");
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
            <a href="index.php#home" class="TOP BUTTON">"Local Delights" Bakery's ADMIN ACCOUNT</a>
            <div class="RIGHT HIDESMALL">
                <a href="index.php?action=logout" class="TOP BUTTON">Logout</a>
            </div>
        </div>
    </div>
    <div class="CONTENT" style="max-width:1100px">

        <div class="ROW PADDING64">
            <h1>ADMINISTRATOR:
                <?php
                echo $db->select("SELECT * FROM users WHERE id='$user_id'", ["fullName"]);
                ?>
            </h1>
        </div>

        <div class="ROW PADDING64">

            <form action="" method="post">
                <p style="font-size: 130%"> <label class="PADDING16" for="date">Download a file for a specific
                        date:</label><input class="INPUT PADDING16" id="date" type="date" name="date"
                        value="<?php echo date('Y-m-d'); ?>"></p>
                <input class="TOP BUTTON CENTER"
                    style="font-family: 'Playfair Display';letter-spacing: 5px;width:100%;text-align:center;border: 2px solid #e1b78e; height:50px;background-color: transparent;"
                    type="submit" name="download" value="Download">
            </form>

            <?php
            $om = new OrderManager();

            if (isset($_POST['download'])) {
                $date = $_POST['date'];
                $plik = $om->downloadOrdersForDate($db, $date);
                ob_clean();
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . basename($plik) . '"');
                header('Content-Length: ' . filesize($plik));
                readfile($plik);
                exit;
            }
            ?>
        </div>

        <div class="COL LLLLLL16 PADDINGLARGE" style="width:100%">
            <br>
            <h5 class="CENTER">All orders</h5>
            <p class="LARGE">

                <?php
                echo '<table style="width: 100%;">
                        <tr>
                            <th>Order ID</th>
                            <th>Name</th>
                            <th>Product 1</th>
                            <th>Product 2</th>
                            <th>Product 3</th>
                            <th>Additional Info</th>
                            <th>Order Date</th>
                            <th>Realisation Date</th>
                            <th>Status</th>
                            <th>Change stauts</th>
                        </tr>';
                $orders = $om->showAllUsersOrders($db);
                foreach ($orders as $order) {
                    echo "<tr style='text-align:center;'>";
                    echo "<td>" . $order[0] . "</td>";
                    echo "<td>" . $order[1] . "</td>";
                    echo "<td>" . $order[2] . "</td>";
                    echo "<td>" . $order[3] . "</td>";
                    echo "<td>" . $order[4] . "</td>";
                    echo "<td>" . $order[5] . "</td>";
                    echo "<td>" . $order[6] . "</td>";
                    echo "<td>" . $order[7] . "</td>";
                    $color = 'black';
                    if ($order[8] === 0) {
                        $order[8] = "in preparation";
                    } else if ($order[8] === 1) {
                        $order[8] = "ready for collection";
                        $color = 'green';
                    } else if ($order[8] === 2) {
                        $order[8] = "collected";
                    } else if ($order[8] === 3) {
                        $order[8] = "not collected";
                        $color = 'red';
                    }
                    echo "<td style='color:" . $color . "'>" . $order[8] . "</td>";

                    echo '<td><form method="post" action="">
    <select id="status_select" name="new_status" style="width:60%">
    <option value=""></option>
        <option value="0">in preparation</option>
        <option value="1">ready for collection</option>
        <option value="2">collected</option>
        <option value="3">not collected</option>
    </select>
    <input type="hidden" name="order_id_to_change" value="' . $order[0] . '">
    <button type="submit" class="TOP BUTTON CENTER" style="width:35%">Edit</button>
</form>
</td>';

                    if (isset($_POST["new_status"]) && isset($_POST["order_id_to_change"])) {
                        $new_status = $_POST["new_status"];
                        $order_id = $_POST["order_id_to_change"];
                        $changeSuccess = $om->changeOrderStatus($db, $order_id, $new_status);

                        if ($changeSuccess) {
                            //echo '<script>alert("Order '.$order_id.' status changed to '.$new_status.'")</script>';
                            echo "<script>location.reload();</script>";
                        } else {
                            //  echo "Order status not changed - repeated status";
                        }
                    }

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