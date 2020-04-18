<?php

/*
 * Tutorial: PHP Login Registration system
 *
 * Page: Application library
 * */

class RunLib
{

    /*
     * Register New User
     *
     * @param $name, $email, $username, $password
     * @return ID
     * */
    public function Register($name, $email, $username, $password)
    {
        try {
            $db = DB();
            $query = $db->prepare("INSERT INTO users(name, email, username, password) VALUES (:name,:email,:username,:password)");
            $query->bindParam("name", $name, PDO::PARAM_STR);
            $query->bindParam("email", $email, PDO::PARAM_STR);
            $query->bindParam("username", $username, PDO::PARAM_STR);
            $enc_password = hash('sha256', $password);
            $query->bindParam("password", $enc_password, PDO::PARAM_STR);
            $query->execute();
            return $db->lastInsertId();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    /*
     * Check Username
     *
     * @param $username
     * @return boolean
     * */
    public function isUsername($user_name)
    {
        try {
            $db = DB();
            $query = $db->prepare("SELECT user_id FROM pmi_employees WHERE user_name=:user_name");
            $query->bindParam("user_name", $user_name, PDO::PARAM_STR);
            $query->execute();
            if ($query->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    /*
     * Login
     *
     * @param $username, $password
     * @return $mixed
     * */
    public function Login($user_name, $user_pass)
    {
        try {
            $db = DB();
            $query = $db->prepare("SELECT user_id FROM pmi_employees WHERE (user_name=:user_name) AND user_pass=:user_pass");
            $query->bindParam("user_name", $user_name, PDO::PARAM_STR);
            $enc_password = hash('sha256', $user_pass);
            $query->bindParam("user_pass", $enc_password, PDO::PARAM_STR);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetch(PDO::FETCH_OBJ);
                return $result->user_id;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    /*
     * get User Details
     *
     * @param $user_id
     * @return $mixed
     * */
    public function UserDetails($user_id)
    {
        try {
            $db = DB();
            $query = $db->prepare("SELECT * FROM pmi_employees LEFT JOIN pmi_timesheet ON pmi_employees.user_id=pmi_timesheet.user_id WHERE pmi_employees.user_id=:user_id");
            $query->bindParam("user_id", $user_id, PDO::PARAM_STR);
            $query->execute();
            if ($query->rowCount() > 0) {
                return $query->fetch(PDO::FETCH_OBJ);
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function CheckAttendance($user_id)
    {
        try {
            $date = date('m/d/Y');
            $db = DB();
            $query = $db->prepare("SELECT * FROM pmi_employees LEFT JOIN pmi_timesheet ON pmi_employees.user_id=pmi_timesheet.user_id WHERE pmi_employees.user_id=:user_id AND date=:date");
            $query->bindParam("user_id", $user_id, PDO::PARAM_STR);
            $query->bindParam("date", $date, PDO::PARAM_STR);
            $query->execute();
            if ($query->rowCount() > 0) {
                return $query->fetch(PDO::FETCH_OBJ);
            }
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

}