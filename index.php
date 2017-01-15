<?php session_start(); ?>
<html>
<head>
    <title>Organizer</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery-3.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <link href="img/icon.png" rel="icon" type="image/png"/>
    <link rel="stylesheet" href="css/jquery-ui.css">
    <script src="js/jquery-ui.js"></script>
</head>
<body>

<div class='container-fluid'>
    <h3><a id="addToDo" href="<?php echo $_SERVER['PHP_SELF']; ?>"><img src="img/add1.png" height="32px" width="32px"/>Add
            ToDo</a></h3>
    <?php
    include_once "functions.php";
    include_once "classes.php";
    if (!isset($_SESSION['todoCount'])) { //счетчик (id) дела
        $_SESSION['todoCount'] = 0;
    }

    if (isset($_POST['newToDo'])) {
        ToDo::WriteToDoToFile(new ToDo($_POST['newToDo'], $_POST['deadline']));
    }

    if (isset($_POST['newTask'])) {
        Task::WriteTaskToFile(new Task($_POST['newTask'], $_POST['ToDo_Id']));
    }

    if (isset($_POST['ToDo_Remove'])) {
        ToDo::RemoveToDo($_POST['ToDo_Remove']);
    }

    if (isset($_POST['mark_task_id'])) {
        Task::MarkTask($_POST['mark_task_id']);
    }

    if (file_exists("todo.txt")) {
        $toDoList = ToDo::ReadToDoFromFile();
    } else {
        $toDoList = array();
    }

    if (file_exists("task.txt")) {
        $taskList = Task::ReadTaskFromFile();
    } else {
        $taskList = array();
    }

    ?>
    <ul class="nav nav-tabs">
        <li><a data-toggle="tab" href="#all">All</a></li>
        <li class="active"><a data-toggle="tab" href="#today">Today</a></li>
        <li><a data-toggle="tab" href="#week">Next Week</a></li>
        <li><a data-toggle="tab" href="#month">Next Month</a></li>
    </ul>

    <div class="tab-content">
        <div id="all" class="tab-pane fade">
            <h3>ALL</h3>
            <table class='table-hover col-lg-6'>
                <thead style="text-align: center">
                <tr class='alert-info'>
                    <td>Action</td>
                    <td>ToDo</td>
                    <td>Deadline</td>
                    <td>Tasks</td>
                </tr>
                </thead>
                <?php
                for ($i = 0; $i < count($toDoList) - 1; $i++) {
                    echo "<tr><td><small><p><a id='rmToDo' name='" . $toDoList[$i]->id . "' href='" . $_SERVER['PHP_SELF'] . "' title='Remove ToDo'><img src=\"img/delete.png\" height='16px' width='16px'/> Remove ToDo</a></p>
                        <a href='" . $_SERVER['PHP_SELF'] . "' id='addTask' name='" . $toDoList[$i]->id . "' title='Add Task'><img src='img/add2.png' height='16px' width='16px'/> Add Task</a></small></td>
                    <td></b><span style='font: bold italic 16pt serif;'>" . $toDoList[$i]->todoName . "</td><td>" . $toDoList[$i]->date . "</span></td>
                    <td>";
                    for ($j = 0; $j < count($taskList) - 1; $j++) {
                        if ($taskList[$j]->todoId == $toDoList[$i]->id) {
                            echo "<a href='#' title='Mark as \"done\"' onclick='mark(" . $j . ")'><img src='img/check.png' height='16px' width='16px'/></a> ";
                            if ($taskList[$j]->mark == 1) {
                                echo "<s>" . $taskList[$j]->name . "</s><br/>";
                            } else {
                                echo $taskList[$j]->name . "<br/>";
                            }
                        }
                    }
                    echo "</td></tr>";
                }
                ?>
            </table>
        </div>

        <div id="today" class="tab-pane fade in active">
            <h3>TODAY (<?php $date = new DateTime('NOW');
                echo $date->format("Y-m-d"); ?>)</h3>

            <table class='table-hover col-lg-6'>
                <thead style="text-align: center">
                <tr class='alert-info'>
                    <td>Action</td>
                    <td>ToDo</td>
                    <td>Deadline</td>
                    <td>Tasks</td>
                </tr>
                </thead>
                <?php
                for ($i = 0; $i < count($toDoList) - 1; $i++) {
                    if (isOfToday($toDoList[$i]->date)) {
                        echo "<tr><td><small><p><a id='rmToDo' name='" . $toDoList[$i]->id . "' href='" . $_SERVER['PHP_SELF'] . "'><img src=\"img/delete.png\" height='16px' width='16px'/> Remove ToDo</a></p>
                        <a href='" . $_SERVER['PHP_SELF'] . "' id='addTask' name='" . $toDoList[$i]->id . "'><img src='img/add2.png' height='16px' width='16px'/> Add Task</a></small></td>
                    <td></b><span style='font: bold italic 16pt serif;'>" . $toDoList[$i]->todoName . "</td><td>" . $toDoList[$i]->date . "</span></td>
                    <td>";
                        for ($j = 0; $j < count($taskList) - 1; $j++) {
                            if ($taskList[$j]->todoId == $toDoList[$i]->id) {
                                echo "<a href='#' title='Mark as \"done\"' onclick='mark(" . $j . ")'><img src='img/check.png' height='16px' width='16px'/></a> ";
                                if ($taskList[$j]->mark == 1) {
                                    echo "<s>" . $taskList[$j]->name . "</s><br/>";
                                } else {
                                    echo $taskList[$j]->name . "<br/>";
                                }
                            }
                        }
                        echo "</td></tr>";
                    }
                }
                ?>
            </table>
        </div>
        <div id="week" class="tab-pane fade">
            <h3>NEXT WEEK (<?php
                $start = new DateTime('NOW');
                $start->add(new DateInterval("P1D"));
                echo $start->format("Y-m-d");
                $now = new DateTime('NOW');
                $end = $now->add(new DateInterval('P1W'));
                echo " - " . $end->format("Y-m-d");
                ?>)</h3>
            <table class='table-hover col-lg-6'>
                <thead style="text-align: center">
                <tr class='alert-info'>
                    <td>Action</td>
                    <td>ToDo</td>
                    <td>Deadline</td>
                    <td>Tasks</td>
                </tr>
                </thead>
                <?php
                for ($i = 0; $i < count($toDoList) - 1; $i++) {
                    if (isOfPeriod($toDoList[$i]->date, 1, "W")) {
                        echo "<tr><td><small><p><a id='rmToDo' name='" . $toDoList[$i]->id . "' href='" . $_SERVER['PHP_SELF'] . "'><img src=\"img/delete.png\" height='16px' width='16px'/> Remove ToDo</a></p>
                        <a href='" . $_SERVER['PHP_SELF'] . "' id='addTask' name='" . $toDoList[$i]->id . "'><img src='img/add2.png' height='16px' width='16px'/> Add Task</a></small></td>
                    <td></b><span style='font: bold italic 16pt serif;'>" . $toDoList[$i]->todoName . "</td><td>" . $toDoList[$i]->date . "</span></td>
                    <td>";
                        for ($j = 0; $j < count($taskList) - 1; $j++) {
                            if ($taskList[$j]->todoId == $toDoList[$i]->id) {
                                echo "<a href='#' title='Mark as \"done\"' onclick='mark(" . $j . ")'><img src='img/check.png' height='16px' width='16px'/></a> ";
                                if ($taskList[$j]->mark == 1) {
                                    echo "<s>" . $taskList[$j]->name . "</s><br/>";
                                } else {
                                    echo $taskList[$j]->name . "<br/>";
                                }
                            }
                        }
                        echo "</td></tr>";
                    }
                }
                ?>
            </table>
        </div>
        <div id="month" class="tab-pane fade">
            <h3>NEXT MONTH (<?php
                $start = new DateTime('NOW');
                $start->add(new DateInterval("P1D"));
                echo $start->format("Y-m-d");
                $now = new DateTime('NOW');
                $end = $now->add(new DateInterval('P1M'));
                echo " - " . $end->format("Y-m-d");
                ?>)</h3>
            <table class='table-hover col-lg-6'>
                <thead style="text-align: center">
                <tr class='alert-info'>
                    <td>Action</td>
                    <td>ToDo</td>
                    <td>Deadline</td>
                    <td>Tasks</td>
                </tr>
                </thead>
                <?php
                for ($i = 0; $i < count($toDoList) - 1; $i++) {
                    if (isOfPeriod($toDoList[$i]->date, 1, "M")) {
                        echo "<tr><td><small><p><a id='rmToDo' name='" . $toDoList[$i]->id . "' href='" . $_SERVER['PHP_SELF'] . "'><img src=\"img/delete.png\" height='16px' width='16px'/> Remove ToDo</a></p>
                        <a href='" . $_SERVER['PHP_SELF'] . "' id='addTask' name='" . $toDoList[$i]->id . "'><img src='img/add2.png' height='16px' width='16px'/> Add Task</a></small></td>
                    <td></b><span style='font: bold italic 16pt serif;'>" . $toDoList[$i]->todoName . "</td><td>" . $toDoList[$i]->date . "</span></td>
                    <td>";
                        for ($j = 0; $j < count($taskList) - 1; $j++) {
                            if ($taskList[$j]->todoId == $toDoList[$i]->id) {
                                echo "<a href='#' title='Mark as \"done\"' onclick='mark(" . $j . ")'><img src='img/check.png' height='16px' width='16px'/></a> ";
                                if ($taskList[$j]->mark == 1) {
                                    echo "<s>" . $taskList[$j]->name . "</s><br/>";
                                } else {
                                    echo $taskList[$j]->name . "<br/>";
                                }
                            }
                        }
                        echo "</td></tr>";
                    }
                }
                ?>
            </table>
        </div>
    </div>
</div>

<form id="mark_task" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="text" name="mark_task_id" id="mark_id" hidden>
</form>
<!--модальное окно создания дела-->
<div id="modal_form_todo">
    <span id="modal_close_todo">x</span>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="margin: 0 auto; text-align: center">
        <label>Enter ToDo Name</label>
        <p><label>Name: </label> <input type="text" name="newToDo" placeholder="ToDo name" style="margin-top: 15px;"
                                        required>
            <br><label>Date: </label><input type="text" id="deadline" name="deadline" placeholder="Deadline"
                                            style="margin-top: 10px;" required></p>
        <input type="submit">
    </form>
</div>
<!--модальное окно создания задачи-->
<div id="modal_form_task">
    <span id="modal_close_task">x</span>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="margin: 0 auto; text-align: center">
        <label>Enter Task Name</label>
        <p><label>Name: </label> <input type="text" name="newTask" placeholder="Task name" style="margin-top: 30px;">
        </p>
        <input type="text" name="ToDo_Id" id="ToDo_Id" value="" hidden>
        <input type="submit">
    </form>
</div>
<!--модальное окно подтверждения удаления дела-->
<div id="modal_form_delete_todo">
    <span id="modal_close_delete_todo">x</span>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="margin: 0 auto; text-align: center">
        <p><label style="margin-top: 50px">Are you sure?</label></p>
        <input type="text" name="ToDo_Remove" id="ToDo_Remove_Id" value="" hidden>
        <input type="submit" value="YES">
    </form>
</div>
<div id="overlay"></div>

<script type="application/javascript">
    function mark(id) {
        document.getElementById('mark_id').value = id;
        document.getElementById('mark_task').submit();
    }
</script>

</body>
</html>
