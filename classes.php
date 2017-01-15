<?php

class Task
{
    public $name;
    public $todoId;
    public $mark;

    /**
     * Task constructor.
     * @param string $name
     * @param string $todoId
     */
    public function __construct(string $name, string $todoId)
    {
        $this->name = $name;
        $this->todoId = $todoId;
        $this->mark = 0;
    }

    static public function MarkTask(int $num)
    {
        $arr = self::ReadTaskFromFile();
        $arr[$num]->mark == 0 ? $arr[$num]->mark = 1 : $arr[$num]->mark = 0;
        unlink("task.txt");
        for ($i = 0; $i < count($arr) - 1; $i++) {
            self::WriteTaskToFile($arr[$i]);
        }
    }

    static public function ReadTaskFromFile()
    {
        $arr = array();
        $file = "task.txt";
        $fd = fopen($file, "r");
        while (!feof($fd)) {
            array_push($arr, unserialize(fgets($fd)));
        }
        return $arr;
    }

    static public function WriteTaskToFile(Task $td)
    {
        $fd = fopen("task.txt", "a");
        fwrite($fd, serialize($td) . PHP_EOL);
        fclose($fd);
    }
}

class ToDo
{
    public $todoName;
    public $date;
    public $id;

    /**
     * ToDo constructor.
     * @param string $name
     * @param string $date
     */
    public function __construct(string $name, string $date)
    {
        $this->todoName = $name;
        $this->date = $date;
        $this->id = $_SESSION['todoCount'];
        $_SESSION['todoCount']++;
    }

    static public function sortByDate($arr)
    {
        $index = array();
        foreach ($arr as $a) {
            if (isset($a->date)) {
                $index[] = $a->date;
            }
        }
        array_multisort($index, $arr);
        return $arr;
    }

    static public function ReadToDoFromFile()
    {
        $arr = array();
        $file = "todo.txt";
        $fd = fopen($file, "r");
        $maxId = 0;
        while (!feof($fd)) {
            array_push($arr, unserialize(fgets($fd)));
        }
        for ($i = 0; $i < count($arr); $i++) {
            if (isset($arr[$i]->id)) {
                if ($arr[$i]->id > $maxId) {
                    $maxId = $arr[$i]->id;
                }
            }
        }
        $_SESSION['todoCount'] = $maxId + 1;

        return $arr;
    }

    static public function WriteToDoToFile(ToDo $td)
    {
        $fd = fopen("todo.txt", "a");
        fwrite($fd, serialize($td) . PHP_EOL);
        fclose($fd);
    }

    static public function RemoveToDo(int $id)
    {
        $arr_todo = array();
        $arr_task = array();
        $arr_todo = self::ReadToDoFromFile();
        $arr_task = Task::ReadTaskFromFile();

// Удаляем дело
        for ($i = 0; $i < count($arr_todo); $i++) {
            if ($arr_todo[$i]->id == $id) {
                unset($arr_todo[$i]);
            }
        }

        unlink("todo.txt"); //удаляем исходный файл

        for ($i = 0; $i < count($arr_todo); $i++) { //формируем новый файл
            if (isset($arr_todo[$i])) {
                self::WriteToDoToFile($arr_todo[$i]);
            }
        }

        $arr_to_remove = array();
        $count_arr = count($arr_task);
// Удаляем связанные с ним задачи
        for ($i = 0; $i < count($arr_task); $i++) {
            if (isset($arr_task[$i]->todoId)) {
                if ($arr_task[$i]->todoId == $id) {
                    array_push($arr_to_remove, $i);
                }
            }
        }

        for ($i = 0; $i < count($arr_to_remove); $i++) {
            unset($arr_task[$arr_to_remove[$i]]);
        }

        unlink("task.txt"); //удаляем исходный файл

        for ($i = 0; $i < $count_arr; $i++) { //формируем новый файл
            if (isset($arr_task[$i]) && !is_bool($arr_task[$i])) {
                Task::WriteTaskToFile($arr_task[$i]);
            }
        }
    }
}