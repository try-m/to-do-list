<?php
require 'db-conn.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To do list on PHP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="main-section">
        <div class="add-section">
            <form action="add.php" method="POST" autocomplete="off">
                <?php if(isset($_GET['mess']) && $_GET['mess'] == 'error'){ ?>
                    <input type="text" name="title" placeholder="This field is required">
                    <button type="submit">Add &nbsp; <span>&#43;</span></button>

                <?php }else { ?>
                <input type="text" name="title" placeholder="What do you need to do?">
            <button type="submit">Add &nbsp; <span>&#43;</span></button>
            <?php } ?>
            </form>
        </div>
        <?php
            $task = $conn->query("SELECT * FROM task ORDER BY id DESC");
        ?>
        <div class="show-to-do-section">
            <?php if($task->rowCount() <= 0){ ?>
                <div class="to-do-item">
                    <div class="empty">
                        <img src="writing.gif" width="80%">
                    </div>
                </div>
            <?php } ?>

            <?php while($todo = $task->fetch(PDO::FETCH_ASSOC)) { ?>
                <div class="to-do-item">
                    <span id="<?php echo $todo['id']; ?>"
                        class="remove-to-do">x</span>
                    <?php if($todo['checked']){ ?>
                        <input type="checkbox"
                            data-todo-id ="<?php echo $todo['id']; ?>"
                            class="check-box" checked>
                        <h2 class="checked"><?php echo $todo['title'] ?></h2>
                    <?php }else {?>
                        <input type="checkbox"
                            data-todo-id ="<?php echo $todo['id']; ?>"
                            class="check-box">
                        <h2><?php echo $todo['title'] ?></h2>
                    <?php } ?>
                    <br>
                    <small>created: <?php echo $todo['data_time'] ?></small>
                </div>
            <?php } ?>
        </div>    
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script>
        $(document).ready(function(){
            $('.remove-to-do').click(function(){
                const id = $(this).attr('id');

                $.post("remove.php",
                    {
                        id: id
                    },
                    (data) => {
                        if(data){
                            $(this).parent().hide(600);
                        }
                    }
                );
            });
            $(".check-box").click(function(e){
                const id = $(this).attr('data-todo-id');
                
                $.post('check.php',
                    {
                        id: id
                    },
                    (data) => {
                        if(data != 'error'){
                            const h2 = $(this).next();
                            if(data === '1'){
                                h2.removeClass('checked');
                            }else {
                                h2.addClass('checked');
                            }
                        }
                    }
                );
            });
        });
    </script>

</body>
</html>