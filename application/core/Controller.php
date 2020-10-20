<?php


namespace Application\Core;


use Application\Models\Main;

abstract class Controller
{
    public $route;
    public $accessLevel;

    protected $view;
    protected $model;
    protected $arResult;
    protected $request;

    public function __construct($route)
    {
        $this->checkAccessLevel();

        if ($_POST || $_GET) {
            $this->request = $this->prepareRequest($_POST ? $_POST : $_GET);
            $this->arResult['arResult']['REQUEST'] = $this->request;
        }

        $this->route = $route;

        $this->view = new View($route);
        $this->model = $this->loadModel($route['controller']);

        if ($this->model) {
            $this->arResult['arResult']['MENU'] = $this->model->getMainMenu();
        }
    }

    /**
     * Loading Model
     *
     * @param $name
     * @return Main|mixed
     */
    public function loadModel($name)
    {
        $path = 'application\models\\' . ucfirst($name);

        if (class_exists($path)) {
            return new $path;
        }

        //If model does nit exist, load Main model.
        return new Main();
    }

    /**
     * Preparing data into $_GET an $_POST arrays
     *
     * @param $request
     * @return mixed
     */
    private function prepareRequest($request)
    {
        foreach ($request as $reqKey => $reqParam) {

            $reqParam = htmlspecialchars(strip_tags(trim($reqParam)));

            switch ($reqKey){
                case 'password':
                    $reqParam = md5($reqParam);
                    break;
                case 'sort':
                    if (!in_array(strtolower($reqParam), Main::TASKS_SORTING_FIELDS)) {
                        $reqParam = 'id';
                    }
                    break;
                case 'by':
                    if (strtolower($reqParam) !== 'asc' && strtolower($reqParam) !== 'desc') {
                        $reqParam = 'desc';
                    }
                    break;
                default:
                    break;
            }

            $request[$reqKey] = $reqParam;
        }

        return $request;
    }

    /**
     * Checking access level for current user
     */
    public function checkAccessLevel()
    {
        if ($_SESSION['RIGHTS'] === 'full') {
            $this->accessLevel = 'full';
        } else {
            $this->accessLevel = 'guest';
        }

        $this->arResult['arResult']['ACCESS_LEVEL'] = $this->accessLevel;
    }
}