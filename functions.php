<?php
function isOfToday($strDate) //проверка на сегодняшнюю дату
{
    $date = new DateTime($strDate);
    $now = new DateTime('NOW');

    return ($date->format("Y-m-d") == $now->format("Y-m-d"));
}

function isOfPeriod($strDate, $val, $interval) //проверка на то, входит ли дата в диапазон. $val = количество дней/недель/месяцев от текущей даты, $interval = день ("D"), неделя ("W"), месяц ("M")
{
    $date = new DateTime($strDate);
    $now = new DateTime('NOW');
    $end = new DateTime('NOW');
    switch ($interval) {
        case "D":
            $end->add(new DateInterval('P' . $val . 'D'));
            break;
        case "W":
            $end->add(new DateInterval('P' . $val . 'W'));
            break;
        case "M":
            $end->add(new DateInterval('P' . $val . 'M'));
            break;
    }

    return ($date->format("Y-m-d") <= $end->format("Y-m-d") && $date->format("Y-m-d") > $now->format("Y-m-d"));
}
?>
<script type="application/javascript">

    $(document).ready(function () {

        $(function () {
            $("#deadline").datepicker({dateFormat: 'yy-mm-dd'});
        });

        $('a#mark_done').click(function (e) {
            e.preventDefault();

        });

        $('a#addToDo').click(function (e) {
            e.preventDefault();
            $('#overlay').fadeIn(400,
                function () {
                    $('#modal_form_todo')
                    .css('display', 'block')
                    .animate({opacity: 1, top: '50%'}, 200);
                });
        });

        $('#modal_close_todo, #overlay').click(function () {
            $('#modal_form_todo')
            .animate({opacity: 0, top: '45%'}, 200,
                    function () {
                        $(this).css('display', 'none');
                        $('#overlay').fadeOut(400);
                    }
                );
        });

        $('a#addTask').click(function (e) {
            e.preventDefault();
            $('#overlay').fadeIn(400,
                function () {
                    $('#modal_form_task')
                    .css('display', 'block')
                    .animate({opacity: 1, top: '50%'}, 200);
                });
            $('#ToDo_Id').attr('value', $(this).attr('name'));
        });

        $('#modal_close_task, #overlay').click(function () {
            $('#modal_form_task')
            .animate({opacity: 0, top: '45%'}, 200,
                    function () {
                        $(this).css('display', 'none');
                        $('#overlay').fadeOut(400);
                    }
                );
        });

        $('a#rmToDo').click(function (e) {
            e.preventDefault();
            $('#overlay').fadeIn(400,
                function () {
                    $('#modal_form_delete_todo')
                    .css('display', 'block')
                    .animate({opacity: 1, top: '50%'}, 200);
                });
            $('#ToDo_Remove_Id').attr('value', $(this).attr('name'));
        });

        $('#modal_close_delete_todo, #overlay').click(function () {
            $('#modal_form_delete_todo')
            .animate({opacity: 0, top: '45%'}, 200,
                    function () {
                        $(this).css('display', 'none');
                        $('#overlay').fadeOut(400);
                    }
                );
        });
    });
</script>