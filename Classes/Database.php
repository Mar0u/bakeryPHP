<?php

class Database
{

    private $mysqli; //uchwyt do BD

    public function __construct($server, $user, $pass, $dbase)
    {
        $this->mysqli = new mysqli($server, $user, $pass, $dbase);
        if ($this->mysqli->connect_errno) {
            printf(
                "No connection: %s\n",
                $mysqli->connect_error
            );
            exit();
        }
        if ($this->mysqli->set_charset("utf8")) {

        }
    }

    function __destruct()
    {
        $this->mysqli->close();
    }

    public function select($sql, $boxes)
    {
        $content = "";
        if ($result = $this->mysqli->query($sql)) {
            $boxCount = count($boxes);
            $resultCount = $result->num_rows;
            $content .= "<table><tbody>";
            while ($row = $result->fetch_object()) {
                $content .= "<tr>";

                for ($i = 0; $i < $boxCount; $i++) {
                    $p = $boxes[$i];
                    $content .= "<td>" . $row->$p . "</td>";
                }
                $content .= "</tr>";
            }
            $content .= "</table></tbody>";

            $result->close();
        }
        return $content;
    }

    public function inquiry($sql)
    {
        if ($this->mysqli->query($sql))
            return true;
        else
            return false;
    }

    public function getMysqli()
    {
        return $this->mysqli;
    }

    public function selectUser($login, $passwd, $table)
    {
        $id = -1;
        $sql = "SELECT * FROM $table WHERE userName='$login'";
        if ($result = $this->mysqli->query($sql)) {
            $rowsCount = $result->num_rows;
            if ($rowsCount == 1) {
                $row = $result->fetch_object();
                $hash = $row->passwd;
                if (password_verify($passwd, $hash))
                    $id = $row->id;
            }
        }
        return $id; //logged user id
    }

    public function checkIfLoginTaken($login)
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM users WHERE userName=?");
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows;
    }

    public function checkIfEmailTaken($email)
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows;
    }

    public function checkIfUseridAdmin($id)
    {
        $stmt = $this->mysqli->prepare("SELECT status FROM users WHERE id=?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $stmt->bind_result($status);
        $stmt->fetch();
        return $status;
    }

    function changePassword($user_id, $oldPassword, $newPassword)
    {
        if (!preg_match('/^[0-9A-Za-ząęłńśćźżóĄĘŁŚĆŹŻÓ]{2,25}$/', $newPassword)) {
            return "Invalid format of the new password";
        }

        $query = "SELECT passwd FROM users WHERE id = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        if (!password_verify($oldPassword, $user['passwd'])) {
            return "Incorrect old password";
        }

        $password_hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $query = "UPDATE users SET passwd = ? WHERE id = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("si", $password_hash, $user_id);
        if (!$stmt->execute()) {
            return "Failed to change the password";
        }
        $stmt->close();
        return "Password changed";
    }

    public function checkPassword($user_id, $password)
    {
        $query = "SELECT passwd FROM users WHERE id = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        if (!password_verify($password, $user['passwd']))
            return 0;
        else
            return 1;
    }

    public function deleteUser($user_id)
    {
        $query = "DELETE FROM users WHERE id = ?";
        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            echo "Error: " . mysqli_error($this->mysqli);
        } else {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
        }
    }

}