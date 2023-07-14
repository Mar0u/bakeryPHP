<?php

class RegistrationForm
{

    protected $user;

    function __construct()
    {
        echo '<form action="processRegister.php" method="post">
        Username<br><input style=width:80% type="text" name="userName"><br><br>
        Password<br><input style=width:80% type="password" name="password"><br><br>
        Confirm password<br><input style=width:80% type="password" name="password2"><br><br>
        Full name<br><input style=width:80% type="text" name="fullName"><br><br>
        Email<br><input style=width:80% type="email" name="email"><br><br><br>
        <input class="button" type="submit" name= "submitReg" value=">> Register <<" style="width:80%;text-align:center;border: 2px solid #e1b78e;">     
        </form>';
    }

    function checkUser()
    {
        $errors = "";
        $args = [
            'userName' => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => '/^[0-9A-Za-ząęłńśćźżóĄĘŁŚĆŹŻÓ]{2,25}$/']],
            'password' => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => '/^[0-9A-Za-ząęłńśćźżóĄĘŁŚĆŹŻÓ]{2,25}$/']],
            'email' => ['filter' => FILTER_VALIDATE_EMAIL],
            'fullName' => ['filter' => FILTER_VALIDATE_REGEXP, 'options' => ['regexp' => '/^[A-Z]{1}[a-ząęłńśćźżó-]{1,25}[ ]{1}[A-Z]{1}[a-ząęłńśćźżó-]{1,25}$/']],
        ];
        $data = filter_input_array(INPUT_POST, $args);
        foreach ($data as $key => $val) {
            if ($val === false or $val === NULL) {
                $errors .= $key . " ";
            }
        }

        if ($errors === "") {
            $this->user = new User($data['userName'], $data['fullName'], $data['email'], $data['password']);

            return $this->user;
        } else {
            return NULL;
        }
    }

}

?>