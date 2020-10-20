<?php


namespace Application\Controllers;


use Application\Core\Controller;

class AccountController extends Controller
{
    /**
     * Login Page
     */
    public function loginAction()
    {
        if ($_SESSION['LOGIN']) {
            $this->view->redirect(SITE_DIR);
        }

        if ($this->request['login'] && $this->request['password']) {
            $this->loginUser($this->request['login'], $this->request['password']);
        }

        $this->view->render('Authorization', $this->arResult);
    }

    /**
     * Logout Page
     */
    public function logoutAction()
    {
        unset($_SESSION['LOGIN']);
        $_SESSION['RIGHTS'] = 'guest';

        $this->view->redirect(SITE_DIR);
    }

    /**
     * Login user
     *
     * @param $login
     * @param $password
     */
    private function loginUser($login, $password)
    {
        $userData = $this->model->getUserFromDB(strtolower($login), $password);
        if ($userData) {
            $_SESSION['RIGHTS'] = 'full';
            $_SESSION['LOGIN'] = $userData['login'];

            $this->view->redirect(SITE_DIR);
        } else {
            $this->arResult['error'] = 'Incorrect login or password';
        }
    }
}