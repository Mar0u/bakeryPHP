<?php

class User
{

    const STATUS_USER = 1;
    const STATUS_ADMIN = 2;

    protected $userName;
    protected $passwd;
    protected $fullName;
    protected $email;
    protected $date;
    protected $status;

    function __construct($userName, $fullName, $email, $passwd)
    {
        $this->userName = $userName;
        $this->fullName = $fullName;
        $this->email = $email;
        $this->passwd = password_hash($passwd, PASSWORD_DEFAULT);
        $this->date = new DateTime();
        $this->status = User::STATUS_USER;
    }

    public function show()
    {
        echo "Username:  $this->userName passwd:  $this->passwd fullname:  $this->fullName email:  $this->email status:  $this->status date: " . date_format($this->date, 'd.m.Y') . "<br>";
    }

    public function setUserName($name)
    {
        $this->userName = $name;
    }

    public function getUserName()
    {
        return $this->userName;
    }

    public function setPasswd($passwd)
    {
        $this->passwd = $passwd;
    }

    public function getPasswd()
    {
        return $this->passwd;
    }

    public function setFullName($name)
    {
        $this->fullName = $name;
    }

    public function getFullName()
    {
        return $this->fullName;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setStatus($status)
    {
        if ($status === 1) {
            $this->status = User::STATUS_USER;
        }
        if ($status === 2) {
            $this->status = User::STATUS_ADMIN;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    function toArray()
    {
        $format = $this->date->format('Y-m-d');
        $arr = [
            "userName" => $this->userName,
            "fullName" => $this->fullName,
            "email" => $this->email,
            "passwd" => $this->passwd,
            "status" => $this->status,
            "date" => $format
        ];
        return $arr;
    }

    function saveToDB($db)
    {
        $arr = $this->toArray();
        $inq = "Insert into users values (Null";
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $inq .= ",'" . implode(',', $value) . "'";
            } else {
                $inq .= ",'" . $value . "'";
            }
        }
        $inq .= ");";
        $db->inquiry($inq);
    }

    static public function getAllUsersDb($bd)
    {
        echo $bd->select("select * from users", ['userName', 'fullName', 'email', 'status']);
    }

    public function selectUser($login, $passwd, $table)
    {
        $id = -1;
        $sql = "SELECT * FROM $table WHERE userName='$login'";
        if ($result = $this->mysqli->query($sql)) {
            $ile = $result->num_rows;
            if ($ile == 1) {
                $row = $result->fetch_object();
                if (password_verify($passwd, $hash)) {
                    $id = $row->id;
                }
            }
        }
        return $id; //id of logged user
    }

}

?>