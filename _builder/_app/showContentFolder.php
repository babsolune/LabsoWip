<?php

function showContentFolder($folder) {
    $files1 = scandir($folder);
    echo '<nav class="cssmenu cssmenu-horizontal"><ul>';
    foreach ($files1 as &$value) {
        if($value!="." && $value!=".." && $value!="_app" && $value!="index.php") {
            $value2 = $folder.'/'.$value;
            echo '<li><a class="cssmenu-title" href="'.$value.'">'.$value.'</a></li>';
        }
    }
    echo '</ul></nav>';
}

?>
