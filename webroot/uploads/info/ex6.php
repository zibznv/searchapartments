<?php

echo '<meta charset="utf-8">';
echo "<hr><p>6. Создать страницу, на которой можно загрузить несколько фотографий в галерею. 
Все загруженные фото должны помещаться в папку gallery и выводиться на странице в виде таблицы.</p><br/><hr>";

$ddr= __DIR__ .DIRECTORY_SEPARATOR;      //"C:/OpenServer/domains/functionsforms"; $ddr=trim($ddr, ' \/') . '/';

// Create directory
$opdir=opendir($ddr);
if (!file_exists ( $ddr . 'gallery')): mkdir('gallery', 0777); endif;
closedir($opdir);

//Перенос файлов на сервер
$allowTypes = array('image/jpeg', 'image/jpg', 'image/png'); //echo '<pre>'; print_r($_FILES); echo '</pre><br>';

if (!empty($_FILES)){
    $uploads_dir = $ddr . 'gallery';
    foreach ($_FILES["img"]["error"] as $key => $error) {
        if ($error == UPLOAD_ERR_OK && in_array($_FILES['img']['type'][$key], $allowTypes)) {
            $tmp_name = $_FILES["img"]["tmp_name"][$key];
            $name = $_FILES["img"]["name"][$key];
            if (move_uploaded_file($tmp_name, "$uploads_dir/$name")) {
                echo "Файл $name загружен.<br/>";
            }
            else{
                echo "Ошибка загрузки файла $name.<br/>";
            }
        }else{
            echo 'Недопустимый тип файла.<br/>';
        }
    }
}
//Вывод элементов gallery
$list_gal=lst($ddr . 'gallery/'); //echo '<pre>'; print_r($list_gal); echo '</pre><br>';

// Список путей к изображениям в gallery
function lst($dir) {
    $kk=array();
    if (is_dir($dir)) {
        $ll=glob($dir . "*.*", GLOB_NOSORT);
        foreach ($ll as $item): $kk[]=basename($item); endforeach;
        return $kk;
    }else{
        echo "Directory not found!<br>";
        return false;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <style>
        body {
            height: 5000px;
        }
        .sbb {
            margin-left: 15px;
            background-color: #aeb6b4;
            color: #1113b6;
            border-radius: 10px;
            text-align: center;
            height: 40px;
            font-size: large;
        }
        .sbb:hover {
            background-color: #eff636;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
<form action="ex6.php" method="post" enctype="multipart/form-data">
    <p>
        Выберите несколько файлов для отправки в галерею!
        <input type="hidden" name="MAX_FILE_SIZE" value="9000000"/>
        <input type="file" name="img[]" multiple="multiple"/>
    </p>
    <p>
        <input  class="sbb"type="submit" value="Отправить" />
        <input  class="sbb"type="reset" value="Сбросить" />
    </p>
</form>

<table style='width: 100%'>
<?php
$i=0;
foreach ($list_gal as $item){
    if($i==0){echo "<tr>";}
    echo "<td style='width: 24%'><img style='width: 99%' src='gallery/$item' alt='Angry face'/></td>";
    $i++;
    if($i==4){ echo "</tr>"; $i=0;}
}
if ($i!=0){
    for ($y=$i; $y<4; $y++){echo "<td style='width: 24%'></td>";}
    echo "</tr>";
}
?>
</table>
</body>
</html>

