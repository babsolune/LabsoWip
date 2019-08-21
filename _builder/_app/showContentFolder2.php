<?php

function showContentFolder($folder) {
    $files1 = scandir($folder);
    echo '<nav class="cssmenu cssmenu-horizontal"><ul>';
    foreach ($files1 as &$value) {
        if($value!="." && $value!=".." && $value!="_app" && $value!="index.php") {
            $value2 = $folder.'/'.$value;
            if(is_dir($value))
                echo '<li><a class="cssmenu-title" href="'.$value.'"><i class="fa fa-folder"></i> '.$value.'</a></li>';
            else
                echo '<li><a class="cssmenu-title" href="'.$value.'">'.$value.'</a></li>';
        }
    }
    echo '</ul></nav>';
}

?>
