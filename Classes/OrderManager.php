<?php

class OrderManager
{

    public function orderForm()
    {
        print('
         <form action="" method="post">
                <p> <label class="PADDING16" for="product0">Cake and stuff, 2,50 zł</label><input class="INPUT PADDING16" id="product0" type="number" placeholder="0" name="product0" min="0" max="1000" value="0"></p>
                <p> <label class="PADDING16" for="product1">Another cake, 1 zł</label><input class="INPUT PADDING16" id="product1" type="number" placeholder="0" name="product1"  min="0" max="1000" value="0"></p>
                <p> <label class="PADDING16" for="product2">Lorem ipsum, 2 zł</label><input class="INPUT PADDING16" id="product2" type="number" placeholder="0" name="product2" min="0" max="1000" value="0"></p>
                <p> <label class="PADDING16" for="date">Realisation date:</label><input class="INPUT PADDING16" id="date" type="date" name="date" value="' . date('Y-m-d', strtotime('+1 day')) . '"></p>
                <p> <label class="PADDING16" for="info">Additional information:</label><input class="INPUT PADDING16" id="info" type="text" placeholder="Double topping etc..." name="info"></p>              
                <input style="width:100%; text-align: center; color: red; border:0" id="error" disabled value=""/>
                <p> <label class="PADDING16" for="sum"><b>Total:</b></label><input style="font-weight:bold; color: black" class="INPUT PADDING16" id="sum" name="sum" disabled value=""></p>  
                <p><button id="continue" name="continue" class="BUTTON LIGHTGREY SECTION" type="submit" disabled>CHECKOUT</button></p>
</form>
');
    }

    public function addOrder($db)
    {
        $errors = "";
        $args = [
            "product0" => FILTER_SANITIZE_STRING,
            "product1" => FILTER_SANITIZE_STRING,
            "product2" => FILTER_SANITIZE_STRING,
            "info" => FILTER_SANITIZE_STRING,
            "date" => FILTER_SANITIZE_STRING,
        ];
        $data = filter_input_array(INPUT_POST, $args);
        foreach ($data as $key => $val) {
            if ($val === false or $val === NULL) {
                $errors .= $key . " ";
            }
        }
        if ($errors)
            die("<br>Incorrectly supplied data: " . $errors);

        $um = new UserManager();

        $user_id = $um->getLoggedInUser($db, session_id());
        if ($user_id == -1) {
            session_destroy();
            header("location:processLogin.php");
        }

        $product0 = $data['product0'];
        $product1 = $data['product1'];
        $product2 = $data['product0'];
        $info = preg_replace("/;/", ",", $data['info']);
        $date_now = date("Y-m-d H:i:s");
        $date_rea = $data['date'];
        $res = $db->inquiry("INSERT INTO orders (user_id,product0,product1,product2,info,order_date,realisation_date,status) VALUE('$user_id','$product0','$product1','$product2','$info','$date_now','$date_rea',0);");
        if (!$res) {
            print("Adding failed: <br>" . $db->getMysqli()->error . "<br>");
            return -1;
        }
    }

    public function showOneUserOrders($db, $userId)
    {
        $stmt = $db->getMysqli()->prepare("SELECT id, product0, product1, product2, info, order_date, realisation_date, status FROM orders WHERE user_id=? ORDER BY orders.realisation_date DESC");
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $orders = array();
        while ($row = $result->fetch_assoc()) {
            $orders[] = array($row['id'], $row['product0'], $row['product1'], $row['product2'], $row['info'], date("d.m.Y H:m", strtotime($row['order_date'])), date("d.m.Y", strtotime($row['realisation_date'])), $row['status']);
        }
        return $orders;
    }

    public function showAllUsersOrders($db)
    {
        $stmt = $db->getMysqli()->prepare("SELECT orders.user_id, orders.id, orders.product0, orders.product1, orders.product2, orders.info, orders.order_date, orders.realisation_date, orders.status, users.fullName FROM orders JOIN users ON orders.user_id = users.id ORDER BY orders.realisation_date DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        $orders = array();
        while ($row = $result->fetch_assoc()) {
            $orders[] = array($row['id'], $row['fullName'], $row['product0'], $row['product1'], $row['product2'], $row['info'], date("d.m.Y H:m", strtotime($row['order_date'])), date("d.m.Y", strtotime($row['realisation_date'])), $row['status']);
        }
        return $orders;
    }

    public function downloadOrdersForDate($db, $date)
    {
        $stmt = $db->getMysqli()->prepare("SELECT orders.user_id, orders.id, orders.product0, orders.product1, orders.product2, orders.info, orders.realisation_date, users.fullName FROM orders JOIN users ON orders.user_id = users.id WHERE realisation_date = '$date'");
        $stmt->execute();
        $result = $stmt->get_result();
        $name = 'orders_' . $date . '.txt';
        $file = fopen($name, 'w+');
        $content = "ORDER ID;NAME;PRODUCT1;PRODUCT2;PRODUCT3;INFO";
        fwrite($file, $content);
        fwrite($file, PHP_EOL);

        while ($row = $result->fetch_assoc()) {
            $content = $row['id'] . ";" . $row['fullName'] . ";" . $row['product0'] . ";" . $row['product1'] . ";" . $row['product2'] . ";" . $row['info'];
            fwrite($file, $content);
            fwrite($file, PHP_EOL);
        }
        fclose($file);
        return $name;
    }

    public function changeOrderStatus($db, $order_id, $new_status)
    {
        if ($new_status == "")
            return false;
        $stmt = $db->getMysqli()->prepare("SELECT status FROM orders WHERE id=?");
        $stmt->bind_param("s", $order_id);
        $stmt->execute();
        $stmt->bind_result($current_status);
        $stmt->fetch();
        $stmt->close();

        if ($current_status != $new_status) {
            $stmt = $db->getMysqli()->prepare("UPDATE orders SET status=? WHERE id=?");
            $stmt->bind_param("ss", $new_status, $order_id);
            $stmt->execute();
            $stmt->close();
            return true;
        } else {
            return false;
        }
    }


}