<?php

namespace TestTask;

require_once("autoload.php");
DBInit::init();
?>
<!DOCTYPE html>
<html>

<head>
    <title>PHP Test</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
</head>

<body>
    <input type="submit" name="updateDB" id="updateDB" value="Загрузить данные"><br><br>
    <input type="text" name="searchText" id="searchText" placeholder=""></input><br><br>
    <input type="submit" name="doSearch" id="doSearch" value="Найти">
    <div></div>
</body>
<script>
    $(document).ready(function() {
        $("#updateDB").click(function() {
            $.post('/download.php');
        });

        $("#doSearch").click(function() {
            if ($('#searchText').val().length < 3) {
                alert("Недостаточно символов! Необходимо как минимум три символа в строке!");
            } else {
                $.post('/search.php', {
                    query: $("#searchText").val()
                }, function(data) {
                    var d = jQuery.parseJSON(data);
                    var output = "";
                    var compare_subkey_val = -1;
                    $.each(d, function(key, value) {
                        $.each(value, function(subkey, subvalue) {
                            if (subkey == "id") {
                                console.log(compare_subkey_val + " ? " + subvalue);
                                if (subvalue != compare_subkey_val)
                                    output += "<h3> Пост " + subvalue + " </h3>";
                                compare_subkey_val = subvalue;
                                return true;
                            }
                            if (subkey == "id_comment") output += "<h4> Комментарий </h4>";

                            output += "<p>" + subkey + ": " + subvalue + "</p>";
                        });
                    });
                    if (output == "") output = "Совпадения не найдены";
                    $('div').html(output);
                });
            }

        });

    });
</script>

</html>