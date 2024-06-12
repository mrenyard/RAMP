<?php
/**
 * Content-Type image/svg+xml
 * /svg.php/[type]-x[size]-[name]/[r],[g],[b]/[scale]
 */
header('Content-Type: image/svg+xml');
$path = explode('/',strtolower(trim($_SERVER["PATH_INFO"],'/')));
$file = explode('-', $path[0]);
$type = array_shift($file);
$size = str_replace('x','', array_shift($file));
$name = implode('-', $file);
$fillColor = explode(',', $path[1]);
$fillColorRed = $fillColor[0];
$fillColorGreen = $fillColor[1];
$fillColorBlue = $fillColor[2];
$scale = isset($path[2]) ? $path[2] : 1;
$rescaleSize = ($size * $scale);
?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 <?=$size; ?> <?=$size; ?>" width="<?=$rescaleSize; ?>" height="<?=$rescaleSize; ?>" fill="rgb(<?=$fillColorRed; ?>,<?=$fillColorGreen; ?>,<?=$fillColorBlue; ?>)">
<?php include('./dynamic/' . $path[0] . '.svg.php'); ?>
</svg>
