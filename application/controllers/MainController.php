<?php


namespace Application\Controllers;


use Application\Core\Controller;
use Application\Models\Main;

class MainController extends Controller
{
    /**
     * Main page
     */
    public function indexAction()
    {
        if (!empty($this->request['action'])) {
            $this->ajaxController();
        }

        $this->arResult['arResult']['TASKS'] = $this->model->getTasks($this->request);

        $this->view->render('Главная страница', $this->arResult);
    }

    /**
     * Controller for ajax requests
     *
     * @return false
     */
    private function ajaxController()
    {

        switch ($this->request['action']) {
            case 'add_task':
                $result = $this->addTask($this->request);
                break;
            case 'task_edit_popup':
                $result = $this->getEditTaskPopupData($this->request['task_id']);
                break;
            case 'edit_task':
                $result = $this->editTask($this->request);
                break;
            default:
                return false;
        }

        echo json_encode($result);
        exit();
    }

    /**
     * Add task
     *
     * @param $requestData
     * @return false[]
     */
    private function addTask($requestData)
    {

        $resultArray = [
            'success' => false
        ];

        if (!$requestData['user_name']) {
            $resultArray['errors']['user_name'] = 'User name is required';
        }

        if (!$requestData['email']) {
            $resultArray['errors']['email'] = 'Email is required';
        } else {
            if (!filter_var($requestData['email'], FILTER_VALIDATE_EMAIL)) {
                $resultArray['errors']['email'] = "Invalid email format";
            }
        }

        if (!$requestData['description']) {
            $resultArray['errors']['description'] = 'Task description is required';
        }

        if (!$resultArray['errors']) {
            $result = $this->model->addTaskToDb([
                'user_name' => $requestData['user_name'],
                'email' => $requestData['email'],
                'description' => $requestData['description'],
                'status' => Main::TASK_IN_PROGRESS
            ]);

            if ($result) {
                $resultArray['success'] = true;
            } else {
                $resultArray['error'] = $result;
            }
        }

        return $resultArray;
    }

    /**
     * Get data for edit task popup
     *
     * @param $taskID
     * @return false[]
     */
    private function getEditTaskPopupData($taskID)
    {
        $resultArray = [
            'success' => false
        ];

        if ($this->accessLevel === 'full') {
            $result = $this->model->getTaskByID($taskID);

            if ($result) {
                $resultArray['success'] = true;
                $resultArray['data'] = $result;
            }
        }

        return $resultArray;
    }

    /**
     * Editing task
     *
     * @param $requestData
     * @return false[]
     */
    private function editTask($requestData)
    {
        $resultArray = [
            'success' => false
        ];

        if (!$requestData['task_id']) {
            $resultArray['errors']['task_id'] = 'Task Id is not defined!';
        }

        if (!$requestData['description']) {
            $resultArray['errors']['description'] = 'Task description is required';
        }

        if ($this->accessLevel !== 'full')
            $resultArray['errors']['permission'] = 'Permission denied!';

        if (!$resultArray['errors']) {
            if (!$requestData['status'])
                $requestData['status'] = Main::TASK_IN_PROGRESS;

            $dbDescription = $this->model->getTaskByID($requestData['task_id'])['description'];

            if ($requestData['description'] != $dbDescription)
                $descriptionModified = true;

            $result = $this->model->updateTask([
                'task_id' => $requestData['task_id'],
                'description' => $requestData['description'],
                'status' => $requestData['status'],
                'is_modified_by_admin' => $descriptionModified ? $descriptionModified : false
            ]);

            if ($result) {
                $resultArray['success'] = true;
            } else {
                $resultArray['error'] = $result;
            }
        }

        return $resultArray;
    }
}