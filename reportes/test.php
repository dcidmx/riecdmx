<?php
require_once('../vendor/setasign/fpdf/fpdf.php');
require_once('../vendor/setasign/fpdi/fpdi.php');
require_once('../vendor/visualmx/PDF_MC_Table/PDF_MC_Table.php');
class PRINTDEMO extends PDF_MC_Table
{
       var $usuarios;

       function setConfig($var,$val)
       {
              $this->{$var} = $val;
       }

       function Header()
       {
              $this->setSourceFile("../resources/plantillas_pdf/demo.pdf");
              $tplIdx = $this->importPage(1);
              // pagina importada posicion inicial 10,10 ancho de 210 mm y alto de 300 mm
              $this->useTemplate($tplIdx, 1, 1, 210, 280);
              $this->SetFont('Helvetica','',10);
       }
       function printdata(){
              $this->AddPage();
              $this->SetFont('Helvetica','',10);
              $this->SetDrawColor(255);
              $this->SetLineWidth(.3);

              $this->SetWidths(array(60, 140));

              $this->SetY(80);
              $j=0;
              for($i=0;$i<count($this->usuarios);$i++){
                     $this->SetX(14);
                     $this->Row(
                            array(
                                   $this->usuarios[$i]->usuario,
                                   $this->usuarios[$i]->correo
                            )
                     );
                     $j++;
                     if($j == 26){
                            $this->AddPage();
                            $this->Sety(95);
                            $j = 0;
                     }
              }
       }
}

$pdf = new PRINTDEMO($orientation='P', $unit='mm', $size='LETTER');
$pdf->setConfig('usuarios',$usuarios);
$pdf->printdata();
$pdf->Output('../public/tmp/'.$token.'.pdf','F');
