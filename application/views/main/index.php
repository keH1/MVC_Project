<?php
/**
 * @var array $arResult - page data
 */

use Application\Models\Main;

?>
    <!-- Example single danger button -->
    <div class="row mt-1 mb-3">
        <div class="col-md-6">
            <div class="btn-group">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false">
                    Sort by <?= $arResult['REQUEST']['sort'] ? strtoupper($arResult['REQUEST']['sort']) : 'ID' ?>
                    (<?= $arResult['REQUEST']['by'] ? strtoupper($arResult['REQUEST']['by']) : 'DESC' ?>)
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item"
                       href="?<?= http_build_query(array_merge($_GET, ['sort' => 'id', 'by' => 'asc'])) ?>">Sort by ID:
                        ASC</a>
                    <a class="dropdown-item"
                       href="?<?= http_build_query(array_merge($_GET, ['sort' => 'id', 'by' => 'desc'])) ?>">Sort by ID:
                        DESC</a>
                    <a class="dropdown-item"
                       href="?<?= http_build_query(array_merge($_GET, ['sort' => 'user_name', 'by' => 'asc'])) ?>">Sort
                        by
                        User name: ASC</a>
                    <a class="dropdown-item"
                       href="?<?= http_build_query(array_merge($_GET, ['sort' => 'user_name', 'by' => 'desc'])) ?>">Sort
                        by
                        User name: DESC</a>
                    <a class="dropdown-item"
                       href="?<?= http_build_query(array_merge($_GET, ['sort' => 'email', 'by' => 'asc'])) ?>">Sort by
                        Email: ASC</a>
                    <a class="dropdown-item"
                       href="?<?= http_build_query(array_merge($_GET, ['sort' => 'email', 'by' => 'desc'])) ?>">Sort by
                        Email: DESC</a>
                    <a class="dropdown-item"
                       href="?<?= http_build_query(array_merge($_GET, ['sort' => 'status', 'by' => 'asc'])) ?>">Sort by
                        Status: ASC</a>
                    <a class="dropdown-item"
                       href="?<?= http_build_query(array_merge($_GET, ['sort' => 'status', 'by' => 'desc'])) ?>">Sort by
                        Status: DESC</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 d-flex justify-content-end">
            <a href="" data-toggle="modal" data-target="#addTask" class="btn btn-success">Add tsk</a>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th scope="col">Task ID</th>
                    <th scope="col">User name</th>
                    <th scope="col">E-mail</th>
                    <th scope="col">Task description</th>
                    <th scope="col">Status</th>
                </tr>
                </thead>
                <tbody>
                <? if ($arResult['TASKS']['ITEMS']) { ?>
                    <? foreach ($arResult['TASKS']['ITEMS'] as $task) { ?>
                        <tr>
                            <th scope="row"><?= $task['id'] ?></th>
                            <td><?= $task['user_name'] ?></td>
                            <td><?= $task['email'] ?></td>
                            <td><?= $task['text'] ?>
                                <? if ($task['is_modified_by_admin']) { ?>
                                    <span class="badge badge-pill badge-warning">Modified by admin</span>
                                <? } ?>
                            </td>
                            <? if ($task['status'] == Main::TASK_IN_PROGRESS) { ?>
                                <td>In progress</td>
                            <? } else { ?>
                                <td>Completed</td>
                            <? } ?>
                            <? if ($arResult['ACCESS_LEVEL'] == 'full') { ?>
                                <td class="text-center">
                                    <button type="button" data-task-id="<?= $task['id'] ?>" data-modal-id="#editTask"
                                            class="btn btn-warning edit-task-popup">Edit
                                    </button>
                                </td>
                            <? } ?>
                        </tr>
                    <? } ?>
                <? } ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
if ($arResult['TASKS']['PAGER_STRING']) {
    echo $arResult['TASKS']['PAGER_STRING'];
} ?>

    <!-- Modals -->
    <div class="modal fade" id="addTask" data-backdrop="static" data-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form name="add_task_form">
                        <input type="hidden" name="action" value="add_task">
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">User name</label>
                                <input type="text" name="user_name" class="form-control" id="validationServer01"
                                       value=""
                                       required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02">E-mail</label>
                                <input type="email" name="email" class="form-control" id="validationServer02" value=""
                                       required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="validationServer03">Task description</label>
                                <textarea class="form-control" name="description" id="validationServer03" rows="10"
                                          required></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary add-task">Add task</button>
                </div>
            </div>
        </div>
    </div>
<? if ($arResult['ACCESS_LEVEL'] == 'full') { ?>
    <div class="modal fade" id="editTask" data-backdrop="static" data-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Task edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form name="edit_task_form">
                        <input type="hidden" name="action" value="edit_task">
                        <input type="hidden" name="task_id" value="">
                        <div class="form-row">
                            <div class="col-md-6 mb-3">
                                <label for="validationServer01">User name</label>
                                <input type="text" name="user_name" class="form-control" id="validationServer01"
                                       value=""
                                       disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="validationServer02">E-mail</label>
                                <input type="email" name="email" class="form-control" id="validationServer02" value=""
                                       disabled>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-12 mb-3">
                                <label for="validationServer03">Task description</label>
                                <textarea class="form-control" name="description" id="validationServer03" rows="10"
                                          required></textarea>
                            </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status"
                                   value="<?= Main::TASK_COMPLETE ?>" id="defaultCheck1">
                            <label class="form-check-label" for="defaultCheck1">
                                Complete
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary edit-task">Save task</button>
                </div>
            </div>
        </div>
    </div>
<? } ?>