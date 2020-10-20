<?php


namespace Application\Models;


use Application\Core\Model;

class Main extends Model
{
    /**
     * Task "In progress" status
     */
    const TASK_IN_PROGRESS = 1;
    /**
     * Task "Complete" status
     */
    const TASK_COMPLETE = 5;
    /**
     * Tasks on page
     */
    const TASKS_ON_PAGE = 3;
    const TASKS_SORTING_FIELDS = ['id', 'user_name', 'email', 'status'];

    /**
     * Getting tasks and pagination
     *
     * @param $request
     * @return array
     */
    public function getTasks($request)
    {
        $arParams = $this->prepareParameters($request);
        $pager = $this->preparePager($arParams['page']);

        return [
            'ITEMS' => $this->getTasksList($pager['QUERY_OFFSET'], $arParams['sort'], $arParams['by']),
            'PAGER_STRING' => $pager['PAGER_STRING']
        ];
    }

    /**
     * Add task to DB
     *
     * @param $taskData
     * @return array|bool
     */
    public function addTaskToDb($taskData)
    {
        $sql = 'INSERT INTO tasks (user_name, email, text, status) VALUES (:user_name, :email, :description, :status)';

        $queryResult = $this->db->query($sql, $taskData);

        if (!assert($queryResult->errorCode())) {
            return true;
        } else {
            return $queryResult->errorInfo();
        }
    }

    /**
     * Update task data
     *
     * @param $taskData
     * @return array|bool
     */
    public function updateTask($taskData) {

        if (!$taskData['task_id'])
            return false;

        if (!$taskData['status'])
            $taskData['status'] = self::TASK_IN_PROGRESS;

        $sql = 'UPDATE tasks SET text = :description, status = :status, is_modified_by_admin = :is_modified_by_admin WHERE id = :task_id';

        $queryResult = $this->db->query($sql, $taskData);

        if (!assert($queryResult->errorCode())) {
            return true;
        } else {
            return $queryResult->errorInfo();
        }
    }

    /**
     * Getting tasks list from DB
     *
     * @param int $offset
     * @param string $orderBy
     * @param string $sortDirection
     * @return array
     */
    public function getTasksList($offset = 0, $orderBy = 'id', $sortDirection = 'desc')
    {
        if (!in_array(strtolower($orderBy), self::TASKS_SORTING_FIELDS)) {
            $orderBy = 'id';
        }

        if ($sortDirection !== 'asc' && $sortDirection !== 'desc') {
            $sortDirection = 'desc';
        }

        $params['offset'] = $offset;
        $params['limit'] = self::TASKS_ON_PAGE;

        $sql = 'SELECT * FROM tasks ORDER BY ' . strtolower($orderBy) . ' ' . $sortDirection . ' LIMIT :offset, :limit';

        return $this->db->row($sql, $params);
    }

    /**
     * Getting task info by task ID
     *
     * @param $taskID
     * @return mixed
     */
    public function getTaskByID($taskID)
    {
        $sql = 'SELECT id as task_id, user_name, email, text as description, status FROM tasks WHERE id = :task_id';

        return $this->db->row($sql, ['task_id' => $taskID])[0];
    }

    /**
     * Prepare pager and return pager string
     *
     * @param $currentPage
     * @return array
     */
    private function preparePager($currentPage)
    {
        $sql = 'SELECT COUNT(*) FROM tasks';

        $totalTasks = $this->db->column($sql, []);
        $countPages = ceil($totalTasks / self::TASKS_ON_PAGE);

        if ($currentPage > $countPages) {
            $currentPage = $countPages;
        }

        $startPosition = ($currentPage - 1) * self::TASKS_ON_PAGE;
        $pagerString = '';

        if ($countPages > 1) {
            $pagerString = '<div class="row justify-content-md-center">
                                <div class="col-lg-3">
                                    <nav aria-label="...">
                                        <ul class="pagination pagination-md">';

            for ($i = 1; $i <= $countPages; $i++) {
                if ($i == $currentPage) {
                    $pagerString .= '<li class="page-item active" aria-current="page">
                                          <span class="page-link">
                                            ' . $i . '
                                            <span class="sr-only">(current)</span>
                                          </span>
                                    </li>';
                } else {
                    $pagerString .= '<li class="page-item"><a class="page-link" href="?' . http_build_query(array_merge($_GET,
                            ['page' => $i])) . '">' . $i . '</a>';
                }
            }

            $pagerString .= '</ul>
                        </nav>
                    </div>
                </div>';
        }

        return [
            "PAGER_STRING" => $pagerString,
            "QUERY_OFFSET" => $startPosition
        ];
    }

    /**
     * Preparing parameters for pager and task sorting
     *
     * @param $request
     * @return array
     */
    private function prepareParameters($request)
    {
        $params = [];

        if ($request['page']) {
            $params['page'] = (int)$request['page'];
        } else {
            $params['page'] = 1;
        }

        if ($request['sort']) {
            $params['sort'] = strtolower($request['sort']);
        } else {
            $params['sort'] = 'id';
        }

        if ($request['by']) {
            $params['by'] = strtolower($request['by']);
        } else {
            $params['by'] = 'desc';
        }

        return $params;
    }
}