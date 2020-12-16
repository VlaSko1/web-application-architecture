<?php


$textFile = file_get_contents("text.txt");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Macrosoft World</title>
    <link rel="stylesheet" href="css/reboot.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="container"> 
        <h1 class="header_h1">Macrosoft World</h1>
    </header>
    <main class="container">
        <section class="main_redactor">
            <h4 class="main_redactor_h4">Текстовый редактор</h4>
            <p class="introduction" >Данный сайт предназначен для демонстрации возожностей копирования, вырезания и вставки, посредством связки фронтенда и бэкенда. 
        В бэкенде используется патерн команда, для реализации всех указанных действий с текстом, а так же логирования результатов редактирования, с возможностью отменять и 
        возвращать редактирование. </p>
            <div class="button_block">
                <form action="#" class="button_block_left" id="button_redactor_form" method="POST">
                        <button class="button_redactor" id="copy_but" name="copy" type="submit">Копировать</button>
                        <button class="button_redactor" id="cut_but" name="cut" type="submit">Вырезать</button>
                        <button class="button_redactor" id="insert_but" name="insert" type="submit">Вставить</button>
                        <input hidden type="number" id="first_point" name="first" value="-1">
                        <input hidden type="number" id="second_point" name="second" value="-1">

                        <input hidden type="number" id="copy_inp" name="copy_on" value="-1">
                        <input hidden type="number" id="cut_inp" name="cut_on" value="-1">
                        <input hidden type="number" id="insert_inp" name="insert_on" value="-1">
                </form>
                <form action="#" class="button_block_right" method="POST" id="button_action_form">
                    <button class="button_redactor" type="submit" id="back_but" name="back">Назад</button>
                    <button class="button_redactor" type="submit" id="forward_but" name="forward">Вперед</button>
                    <button class="button_redactor" type="submit" id="reset_but" name="reset">Сброс</button>
                    <input hidden type="number" id="back_inp" name="back_on" value="-1">
                    <input hidden type="number" id="forward_inp" name="forward_on" value="-1">
                    <input hidden type="number" id="reset_inp" name="reset_on" value="-1">
                </form>
              
            </div>
            <pre class="text_redactor" id="redactor"><?php echo $textFile; ?></pre>
            <p class="error_block" id="error"></p>
        </section>
    </main>
    <footer class="container">
        <p class="footer_div"><?php echo date('Y')?></p>
    </footer>
    <script src="js/main.js"></script>
</body>
</html>