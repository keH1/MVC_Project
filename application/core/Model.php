<?php


namespace Application\Core;


use Application\Lib\Db;

abstract class Model
{
    public $db;

    public function __construct()
    {
        $this->db = new Db;
    }

    /**
     * Return main menu array
     *
     * @return \string[][]
     */
    public function getMainMenu()
    {
        $menuArray = [
            [
                'URL' => '/',
                'NAME' => 'Home'
            ],
        ];

        if (!$this->isLogin()) {
            $menuArray[] = [
                'URL' => '/account/login/',
                'NAME' => 'Login'
            ];
        } else {
            $menuArray[] = [
                'URL' => '/account/logout/',
                'NAME' => 'Logout'
            ];
        }

        return $menuArray;
    }

    /**
     * Checking is user is logged in
     *
     * @return bool
     */
    private function isLogin()
    {
        if ($_SESSION['LOGIN']) {
            return true;
        } else {
            return false;
        }
    }
}