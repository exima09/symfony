<?php
namespace App\Classes;

class Login
{
    private $login;
    private $password;

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login): void
    {
        $this->login = $login;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

}
