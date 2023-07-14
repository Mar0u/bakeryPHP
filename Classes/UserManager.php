<?php

class UserManager
{

    public function loginForm()
    {
        $content = "<form action='?' method='post'>";
        $content .= "<label for='login' class='text-box' style='width:100%; border-bottom:0'>Login:</label><input style='width:77%' id='login' type='text' name='login' /><br/><br/>";
        $content .= "<label for='passwd' class='text-box' style='width:100%; border-bottom:0'>Password:</label><input style='width:77%' id='passwd' type='password' name='passwd'/><br/><br/><br/>";
        $content .= "<input class='button' name='submit' type='submit' id='submit' value='>> Sign in <<' style='width:39%;text-align:center;border: 2px solid #e1b78e;'>";
        $content .= "<input class='button' name='cancel' type='reset' id='cancel' value='Cancel' style='width:39%;text-align:center'>";
        $content .= "</form>";
        print($content);
    }

    public function login($db, $l, $p)
    {

        if ($l === NULL and $p === NULL) {
            $args = [
                "login" => FILTER_SANITIZE_STRING,
                "passwd" => FILTER_UNSAFE_RAW,
            ];

            $data = filter_input_array(INPUT_POST, $args);
        } else {
            $data["login"] = $l;
            $data["passwd"] = $p;
        }

        $errors = "";
        foreach ($data as $key => $val)
            if ($val === false or $val === NULL)
                $errors .= $key . " ";
        if ($errors)
            die("<br>Incorrect data: " . $errors);

        $userId = $db->selectUser($data["login"], $data["passwd"], "users");
        if ($userId >= 0) {
            session_start();
            $res = $db->inquiry("DELETE FROM logged_in_users WHERE userId ='$userId';");
            if (!$res) {
                print("Deleting failed: <br>" . $db->getMysqli()->error . "<br>");
                return -1;
            }

            $session_id = session_id();
            $date_now = date("Y-m-d H:i:s");
            $res = $db->inquiry("INSERT INTO logged_in_users VALUE('$session_id', '$userId', '$date_now');");
            if (!$res) {
                print("Adding failed: <br>" . $db->getMysqli()->error . "<br>");
                return -1;
            }
            return $userId;
        } else if ($userId == -1) {
            return -1;
        }
    }

    public function logout($db)
    {
        //    session_start();
        $session_id = session_id();
        $res = $db->inquiry("DELETE FROM logged_in_users WHERE sessionId ='$session_id';");
        if (!$res) {
            print("Deleting failed: <br>" . $db->getMysqli()->error . "<br>");
            return -1;
        }
        if ($session_id)
            session_destroy();
    }

    public function getLoggedInUser($db, $sessionId)
    {
        $res = $db->getMysqli()->query("SELECT userId FROM logged_in_users WHERE sessionId = '$sessionId'");
        if (!$res || $res->num_rows != 1)
            return -1;
        return $res->fetch_object()->userId;
    }

}