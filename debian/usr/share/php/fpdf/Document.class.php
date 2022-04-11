<?php
/**
 * Extention to FPDF
 * @copyright All Rights Released
 * @author Matt Renyard (matt@justified-online.co.uk)
 * @author Andy Robinson (andy@andyr.co.uk)
 * @version 0.0.1;
 */
namespace fpdf;

require_once('fpdf/fpdf.php');

class Document extends \FPDF {

  public function __construct($orientation='P', $unit='mm', $format='A4') {
    parent::__construct($orientation, $unit, $format);
    $this->pageCoords = new \ramp\Coords(0, 0);
    $this->outlineCoords = new \ramp\Coords(0, 0);
  }

  /**
   * This method allows to print text with the following tags in it:
   *
   * based on http://www.fpdf.org/en/script/script25.php
   * @param string - text put inside framed cell <text>: renders the text in bold
   * @param int $lineHeight can override the default value of 5
   */
  public function WriteText($text, $lineHeight = 5) {
    $intPosIni = 0;
    $intPosFim = 0;
    if (strpos($text, '<') !== false && strpos($text, '[') !== false) {
      if (strpos($text, '<') < strpos($text, '[')) {
        $this->Write($lineHeight, substr($text, 0, strpos($text, '<')));
        $intPosIni = strpos($text, '<');
        $intPosFim = strpos($text, '>');
        $this->SetFont('', 'B');
        $this->Write($lineHeight, substr($text, $intPosIni + 1, $intPosFim - $intPosIni - 1));
        $this->SetFont('', '');
        $this->WriteText(substr($text, $intPosFim + 1, strlen($text)), $lineHeight);
      } else {
        $this->Write($lineHeight, substr($text, 0, strpos($text, '[')));
        $intPosIni = strpos($text, '[');
        $intPosFim = strpos($text, ']');
        $w = $this->GetStringWidth('a') * ($intPosFim - $intPosIni - 1);
        $this->Cell($w, $this->FontSize + 0.75, substr($text, $intPosIni + 1, $intPosFim - $intPosIni - 1), 1, 0, '');
        $this->WriteText(substr($text, $intPosFim + 1, strlen($text)), $lineHeight);
      }
    } else {
      if (strpos($text, '<') !== false) {
        $this->Write($lineHeight, substr($text, 0, strpos($text, '<')));
        $intPosIni = strpos($text, '<');
        $intPosFim = strpos($text, '>');
        $this->SetFont('', 'B');
        $this->WriteText(substr($text, $intPosIni + 1, $intPosFim - $intPosIni - 1), $lineHeight);
        $this->SetFont('', '');
        $this->WriteText(substr($text, $intPosFim + 1, strlen($text)), $lineHeight);
      } elseif (strpos($text, '[') !== false) {
        $this->Write($lineHeight, substr($text, 0, strpos($text, '[')));
        $intPosIni = strpos($text, '[');
        $intPosFim = strpos($text, ']');
        $w = $this->GetStringWidth('a') * ($intPosFim - $intPosIni - 1);
        $this->Cell($w, $this->FontSize + 0.75, substr($text, $intPosIni + 1, $intPosFim - $intPosIni - 1), 1, 0, '');
        $this->WriteText(substr($text, $intPosFim + 1, strlen($text)), $lineHeight);
      } else {
        $this->Write($lineHeight, $text);
      }
    }
  }

  /**
   * Allows to draw rounded rectangles. Parameters are:
   *
   * x, y: top left corner of the rectangle.
   * w, h: width and height.
   * r: radius of the rounded corners.
   * style: same as Rect(): F, D (default value), FD or DF.
   *
   * http://www.fpdf.org/en/script/script7.php
   */
  public function RoundedRect($x, $y, $w, $h, $r, $style = '')
  {
    $k = $this->k;
    $hp = $this->h;
    if($style=='F')
      $op='f';
    elseif($style=='FD' || $style=='DF')
      $op='B';
    else
      $op='S';
    $MyArc = 4/3 * (sqrt(2) - 1);
    $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
    $xc = $x+$w-$r ;
    $yc = $y+$r;
    $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));

    $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
    $xc = $x+$w-$r ;
    $yc = $y+$h-$r;
    $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
    $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
    $xc = $x+$r ;
    $yc = $y+$h-$r;
    $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
    $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
    $xc = $x+$r ;
    $yc = $y+$r;
    $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
    $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
    $this->_out($op);
  }

  public function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
  {
    $h = $this->h;
    $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
      $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
  }
}
