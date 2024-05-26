<?php
/**
 * Combine and compress CSS
 * Based on the work of Reinhold Weber, with thanks
 * http://www.catswhocode.com/blog/3-ways-to-compress-css-files-using-php
 * @author Matt Renyard (mrenyard@gmail.com)
 * @author Reinhold Weber
 * @package webshop
 * @version 0.0.9
 */

function compress($buffer) {
  /* remove comments */
  $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
  /* remove tabs, spaces, newlines, etc. */
  $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
  return $buffer;
}

ob_start("compress");
if (file_exists('import/css.manifest')) {
  foreach (file('import/css.manifest') as $line){
    $line = trim($line);
    if((strpos($line,';') !== 0) && ($line !== '')) {
      include('import/'.$line.'.css');
    }
  }
}
$allcss = ob_get_contents();
ob_end_clean();

$allcss = compress($allcss);
$fileName = 'combined.'.date('Y-d-m').'.css';
$cssfile = fopen($fileName, 'wb');
fwrite($cssfile, $allcss);
fclose($cssfile);

return $fileName;
