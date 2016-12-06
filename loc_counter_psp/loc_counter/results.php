<?php
require('functions.php');
$list = countFunctions();
?>


<!doctype html>
<html>
    <head>
        <meta lang="en">
        <meta charset="utf-8">
        <title>Results Page for Loc</title>
        <link href="_css/styles.css" rel="stylesheet" type="text/css">
    </head>
    
    <body>
        <table>
            <tr>
                <th>Functions</th>
                <th>LOC</th>
            </tr>
            
            <?php foreach ($list as $key => $value) : ?>
            <tr>
                <?php
                echo '<td>' . $key . '</td>';
                echo '<td>' . $value . '</td>';
                ?>
            </tr>
            <?php endforeach; ?>
        </table>
    </body>
</html>