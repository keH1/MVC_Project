<?php


namespace Application\Models;


use Application\Core\Model;

class Account extends Model
{
    /**
     * Getting user from db by login:pass pair
     *
     * @param $login
     * @param $pas
     * @return false|mixed
     */
    public function getUserFromDB($login, $pas)
    {
        $sqlString = 'SELECT login FROM users WHERE login = :login AND password = :password';
        $params = [
            'login' => $login,
            'password' => $pas
        ];

        $queryDataArray = $this->db->row($sqlString, $params);

        if ($queryDataArray)
            return $queryDataArray[0];
        else
            return false;
    }

    /**
     * Returns main menu for account directory
     *
     * @return \string[][]
     */
    public function getMainMenu()
    {
        return [
            [
                'URL' => '/',
                'NAME' => 'Home'
            ],
            [
                'URL' => '/account/login/',
                'NAME' => 'Login'
            ]
        ];
    }
}