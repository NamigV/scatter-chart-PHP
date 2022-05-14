<?php 
$filename = "data2.txt"; 
$height = $_POST["height"];
$width = $_POST["width"];

$arr = file($filename);

$height_min = 0; //начало координат
$height_max = PHP_INT_MIN ;
$width_min = 0; //начало координат
$width_max = PHP_INT_MIN;

//определяем максимальные X и Y среди всех точек
foreach($arr as $line)
{
	$num = preg_split("|[-\s;]|",$line);
	if ($num[0]>$width_max) $width_max=$num[0];
	if ($num[1]>$height_max) $height_max=$num[1];
}

header('Content-Type: image/jpeg');
$img = imagecreatetruecolor(2050, 1000);

// цвета
$black = imagecolorallocate($img, 0, 0, 0);
$white = imagecolorallocate($img, 255, 255, 255);
$green = imagecolorallocate($img, 132, 135, 28);
$red = imagecolorallocate($img, 255, 0, 0);

//Отрисовка графика
imagefill($img, 0, 0, $white);
imagesetthickness($img, 3);
imageline ($img , 100, 900-$height, 100, 900, $black);
imageline ($img , 100, 900-$height, 95, 910-$height, $black);
imageline ($img , 100, 900-$height, 105, 910-$height, $black);
imageline ($img , 100, 900, 100+$width, 900, $black);
imageline ($img , 100+$width, 900, 90+$width, 895, $black);
imageline ($img , 100+$width, 900, 90+$width, 905, $black);
imagestring ($img , 5 , 100+$width , 905 ,'X' , $red );
imagestring ($img , 5 , 80 , 900-$height ,'Y' , $red ); 
imagesetthickness($img, 1);
$coordlinetext = (int)($width_max/($width/100));
for($i=200;$i<=$width+50;$i+=100)
{
	imageline ($img , $i, 900, $i, 905, $black);
	imageline ($img , $i, 900, $i, 900-$height, $red);
	imagestring ($img , 5 , $i-15 , 910 , $coordlinetext , $red );
	$coordlinetext+=(int)($width_max/($width/100));
}

$coordlinetext = (int)($height_max/($height/100));
for($i=800;$i>=950-$height;$i-=100)
{
	imageline ($img , 100, $i, 95, $i, $black);
	imageline ($img , 100, $i, 100+$width, $i, $red);
}
$coordtext = 900;
for($j=0;$j<($height/100)-1;$j+=1)
{
	$coordtext-=100;
	imagestring ($img , 5 , 65 , $coordtext-7 ,$coordlinetext , $red );
	$coordlinetext+=(int)($height_max/($height/100));
}

//Выводим точки
foreach($arr as $line)
{
	$num = preg_split("|[-\s;]|",$line);
    //Вычисляем положение точки на графике
    $image_x = 100 + (float)($width*$num[0]/$width_max);
    $image_y = 900 - $height + (float)($height*($height_max - $num[1])/$height_max);
    //Рисуем точку
	imagefilledellipse($img , $image_x, $image_y, 10 , 10 , $black );
}

imagejpeg($img);
imagedestroy($img);
?>