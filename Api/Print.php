<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../Config/Connection.php';
include_once 'Object/Evaluation.php';
include_once '../Config/Autorization.php';
include_once '../vendors/dompdf/autoload.inc.php';


use Dompdf\Dompdf;

$database = new Connection();
$db = $database->getConnection();
$evaluation = new Evaluation($db);
$aut = new Autorization();
$dompdf = new Dompdf();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) 
{
  case 'GET':
    if(isset($_GET['route']) && !empty($_GET['route']))
    {
        if($_GET['route'] ==  "evaluationprint")
        {
          if(isset($_GET['id']) && !empty($_GET['id'])){
           PrintEvaluation($dompdf,$evaluation,$_GET['id']);
          }
        }
    }
    break;
  case 'PUT':
    break;
  case 'POST':
   $_POST = json_decode(file_get_contents("php://input"));
    break;
  case 'DELETE': 
    break;


}

$dompdf->setPaper('A4','landspace');
$dompdf->render();
$dompdf->stream("Evaluation",array("Attachment" => 0));

function PrintEvaluation($dompdf,$evaluation,$id)
{   
    $result = $evaluation->GetEvaluationView($id);
    if(count($result) > 0){
    $html = "<style> font-family: 'arial'; font-size:0.1 em;</style>";
    $html .= "<table border='0' style='border-collapse: collapse;width:100%;text-align:center'>";
    $html .= "<tr><td><h3>Detail Evaluasi Penawaran</h3></td></tr>";
    $html .= "</table>";

    $html .= "<table border='0' style='border-collapse: collapse;width:100%;padding-top:50px;'>";
    $html .= "<tr>";
    $html .= "<td style='width:25%'><strong>No Evaluasi Penawaran</strong></td>";
    $html .= "<td style='width:1%'><strong>:</strong></td>";
    $html .= "<td style='width:74%'><strong>". $result['EvaluationCode']."</strong></td>";
    $html .= "</tr>";
    $html .= "<tr>";
    $html .= "<td style='width:25%'><strong>Purchasing Code</strong></td>";
    $html .= "<td style='width:1%'><strong>:</strong></td>";
    $html .= "<td style='width:74%'><strong>- / -</strong></td>";
    $html .= "</tr>";
    $html .= "<tr>";
    $html .= "<td style='width:25%'><strong>RFR. / PR. No.</strong></td>";
    $html .= "<td style='width:1%'><strong>:</strong></td>";
    $html .= "<td style='width:74%'><strong>". $result['PR']['PRCode']."</strong></td>";
    $html .= "</tr>";
    $html .= "<tr>";
    $html .= "<td style='width:25%'><strong>Collective No.</strong></td>";
    $html .= "<td style='width:1%'><strong>:</strong></td>";
    $html .= "<td style='width:74%'><strong>- / -</strong></td>";
    $html .= "</tr>";
    $html .= "<tr>";
    $html .= "<td style='width:25%'><strong>Cost Center / Assets No. / Order No.</strong></td>";
    $html .= "<td style='width:1%'><strong>:</strong></td>";
    $html .= "<td style='width:74%'><strong>- / -</strong></td>";
    $html .= "</tr>";
    $html .= "<tr>";
    $html .= "<td style='width:25%'><strong>Project</strong></td>";
    $html .= "<td style='width:1%'><strong>:</strong></td>";
    $html .= "<td style='width:74%'><strong>". $result['PR']['Project']."</strong></td>";
    $html .= "</tr>";
    $html .= "</table>";
    
    $html .= "<table border='1' style='border-collapse: collapse;width:100%;padding-top:40px;'>";
    $html .= "<thead>";
    $html .= "<tr>";
    $html .= "<td rowspan='4'><strong>NO</strong></td>";
    $html .= "<td rowspan='4'><strong>QTY</strong></td>";
    $html .= "<td rowspan='4'><strong>UoM</strong></td>";
    $html .= "<td rowspan='4'><strong>NAMA BARANG/JASA</strong></td>";
    $html .= "</tr>";
    $html .= "<tr>";
    foreach($result['PRVendor'] as $key => $value){
        $html .= "<td colspan='5' style='text-align:center'><strong>Penawaran ".($key + 1)."</strong></td>";
    }   
    $html .= "</tr>";
    $html .= "<tr>";
    foreach($result['PRVendor'] as $key => $value){
    $html .= "<td colspan='5' style='text-align:center'><strong>".$value['VendorName']."</strong></td>"; 
    }  
    $html .= "</tr>";
    $html .= "<tr>";
    foreach($result['PRVendor'] as $key => $value){
    $html .= "<td><strong>Review</strong></td>";
    $html .= "<td><strong>DELIVERY</strong></td>";
    $html .= "<td><strong>UNIT PRICE</strong></td>";
    $html .= "<td><strong>TOTAL</strong></td>";
    $html .= "<td><strong>DESCRIPTION</strong></td>";
    }
    $html .= "</tr>";
    $html .= "</thead>";

     foreach($result['Evaluationdetailvendor'] as $keyDV => $valueDV){
        $html .= "<tr>";
        $html .= "<td>".($keyDV + 1)."</td>";
        $html .= "<td>".$valueDV["Qty"]."</td>";
        $html .= "<td>".$valueDV["UoMName"]."</td>";
        $html .= "<td>".$valueDV["ItemName"]."</td>";
       
             foreach($valueDV['Vendor'] as $keyV => $valueV){
               $html .= "<td>";
                if($valueV['Reviews'] !== null){
                    $html .= "<div>";
                    $html .= "<ul>";
                     foreach($valueV['Reviews'] as $keyR => $valR){
                        $html .= "<li style=\color:blue\>".$valR["FullName"]."</li>";
                      }
                    $html .= "</ul>";
                    $html .= "</div>";
                   }
                $html .= "</td>";
                $html .= "<td>".$valueV["Delivery"]."</td>";
                $html .= "<td>".$valueV["UnitPrice"]."</td>";
                $html .= "<td>".$valueV["TotalPrice"]."</td>";
                $html .= "<td>".$valueV["Description"]."</td>";
             }
        $html .= "</tr>";
     }
        $html .= "<tr>";
        $html .= "<td colspan='3'></td>";
        $html .= "<td><strong>INCLUDE</strong></td>";
            
        foreach($result['PRVendor'] as $keyV => $valueV){
            $html .= "<td colspan='5' style='padding: 0px !important;vertical-align: top !important;'>";
            $html .= "<table border='0' style='border-collapse: collapse;width:100%;'>";
            foreach($valueV['Include'] as $keyInc => $valueInc){
                $html .= "<tr>";
                $html .= "<td><span> ".($keyInc + 1)." . ".$valueInc["ItemName"]."</span>";
                $html .= "</td>";
                $html .= "</tr>";
            }
            $html .= "</table>";
            $html .= "</td>";
        }
        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td colspan='3'></td>";
        $html .= "<td><strong>EXCLUDE</strong></td>";
            
        foreach($result['PRVendor'] as $keyV => $valueV){
            $html .= "<td colspan='5' style='padding: 0px !important;vertical-align: top !important;'>";
            $html .= "<table border='0' style='border-collapse: collapse;width:100%;'>";
            foreach($valueV['Exclude'] as $keyExc => $valueExc){
                $html .= "<tr>";
                $html .= "<td><span> ".($keyExc + 1)." . ".$valueExc["ItemName"]."</span>";
                $html .= "</td>";
                $html .= "</tr>";
            }
            $html .= "</table>";
            $html .= "</td>";
        }
        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td colspan='3'></td>";
        $html .= "<td><strong>SUB TOTAL</strong></td>";
        foreach($result['PRVendor'] as $keyV => $valueV){        
            $html .= "<td></td>";
            $html .= "<td></td>";
            $html .= "<td></td>";
            $html .= "<td>".$valueV["EvaluationDetail"]["SubTotal"]."</td>";
            $html .= "<td></td>";
        }
        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td colspan='3'></td>";
        $html .= "<td><strong>DISCOUNT</strong></td>";
        foreach($result['PRVendor'] as $keyV => $valueV){        
            $html .= "<td></td>";
            $html .= "<td></td>";
            $html .= "<td></td>";
            $html .= "<td>".$valueV["EvaluationDetail"]["Discount"]."</td>";
            $html .= "<td></td>";
        }
        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td colspan='3'></td>";
        $html .= "<td><strong>TOTAL</strong></td>";
        foreach($result['PRVendor'] as $keyV => $valueV){        
            $html .= "<td></td>";
            $html .= "<td></td>";
            $html .= "<td></td>";
            $html .= "<td>".$valueV["EvaluationDetail"]["Total"]."</td>";
            $html .= "<td></td>";
        }
        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td colspan='3'></td>";
        $html .= "<td><strong> PPN 10% </strong></td>";
        foreach($result['PRVendor'] as $keyV => $valueV){        
            $html .= "<td></td>";
            $html .= "<td></td>";
            $html .= "<td></td>";
            $html .= "<td>".$valueV["EvaluationDetail"]["PPN"]."</td>";
            $html .= "<td></td>";
        }
        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td colspan='3'></td>";
        $html .= "<td><strong> GRAND TOTAL </strong></td>";
        foreach($result['PRVendor'] as $keyV => $valueV){        
            $html .= "<td></td>";
            $html .= "<td></td>";
            $html .= "<td></td>";
            $html .= "<td>".$valueV["EvaluationDetail"]["GrandTotal"]."</td>";
            $html .= "<td></td>";
        }
        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td colspan='3'></td>";
        $html .= "<td><strong> DELIVERY POINT </strong></td>";
        foreach($result['PRVendor'] as $keyV => $valueV){        
            $html .= "<td></td>";
            $html .= "<td></td>";
            $html .= "<td></td>";
            $html .= "<td>".$valueV["EvaluationDetail"]["DeliveryPoint"]."</td>";
            $html .= "<td></td>";
        }
        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td colspan='3'></td>";
        $html .= "<td><strong>  PAYMENT TERMS </strong></td>";
        foreach($result['PRVendor'] as $keyV => $valueV){        
            $html .= "<td></td>";
            $html .= "<td></td>";
            $html .= "<td></td>";
            $html .= "<td>".$valueV["EvaluationDetail"]["PaymentTerms"]."</td>";
            $html .= "<td></td>";
        }
        $html .= "</tr>";


        $html .= "<tr>";
        $html .= "<td colspan='3'></td>";
        $html .= "<td><strong>  PRICE IDR </strong></td>";
        foreach($result['PRVendor'] as $keyV => $valueV){        
            $html .= "<td></td>";
            $html .= "<td></td>";
            $html .= "<td></td>";
            $html .= "<td>".$valueV["EvaluationDetail"]["IDRCode"]."</td>";
            $html .= "<td></td>";
        }
        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td colspan='3'> <strong> REKOMENDASI </strong></td>";
        $html .= "<td colspan='".(5*count($result['PRVendor']) + 1)."'></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td colspan='".(5*count($result['PRVendor']) + 4)."' >";
        $html .= "<strong> NOTE </strong> : <br> ".$result["Note"];
        $html .= "</td>";
        $html .= "</tr>";
        
    $html .= "</table>";
    $dompdf->loadHtml($html);
    }else
    {
      RequestFail();
    }
}

function RequestFail(){
  http_response_code(404);
      echo json_encode(
            array("message" => "Request Fail.")
        );
}
?>