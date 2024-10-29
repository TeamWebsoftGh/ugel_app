<?php
/**
 * Created by PhpStorm.
 * User: Brajerrie
 * Date: 3/26/2019
 * Time: 11:15 AM
 */

namespace App\Services;


use App\Constants\Constants;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IBaseService;
use Mpdf\Mpdf;

class ServiceBase implements IBaseService
{
    protected $response;

    /**
     * ServiceBase constructor.
     */
    public function __construct()
    {
        $this->response = new Response();
    }

    /**
     * @param string $content
     * @param string $format
     * @param string $filename
     * @throws \Mpdf\MpdfException
     */
    public function print(string $content, $filename = 'download.pdf', $format = 'A4', $output = "I") {
        $format = $format ?? 'A4' ;
        ini_set("pcre.backtrack_limit", "70000000");
        $param = ['mode'=>'utf-8', 'format'=>$format, 'pagenumPrefix'=>'Page '];
//        $system_code = $this->settingsRepository::getCompanyShortCodeStatic() ;
//        if($system_code == Constants::ESS_COMPANY_SHORT_CODES['APEX']){
//            $param = array_merge($param, ['margin_top'=>9, 'margin_left'=>9,'margin_right'=>9]);
//        }
        $mpdf = new Mpdf($param);
        $mpdf->useSubstitutions = false;
        $sidebar_color = Constants::PAYSLIP_COLOR;
        $tr = ".headerRow td,.headerRow th{background-color:$sidebar_color;color:#FFFFFF;padding:1mm;}";
        $stylesheet = file_get_contents(public_path('css/print.css')); // external css
        $stylesheet .= $tr ;
        $emp_name = auth()->user()?auth()->user()->username: '' ;
        $mpdf->SetHTMLFooter("
            <table width='100%'><tr><td width='33%' style='text-align:left;'><i>{PAGENO}</i></td>
                    <td width='83%' style='text-align:right;'><i>Generated on {DATE jS F, Y} @ {DATE h:i:s A} by $emp_name</i></td></tr>
            </table>");
        $mpdf->WriteHTML($stylesheet,1);
        foreach(str_split($content, Constants::PRINT_BUFFER_SIZE ) as $text){
            $mpdf->WriteHTML($text,2);
        }
        $mpdf->Output($filename,$output);
    }

    public function printGRA(string $content, $filename = 'download.pdf', $format = 'A4-L', $output = "I") {
        $format = $format ?? 'A4-L' ;
        ini_set("pcre.backtrack_limit", "70000000");
        $param = ['mode'=>'utf-8', 'format'=>$format, 'pagenumPrefix'=>'Page '];
//        $system_code = $this->settingsRepository::getCompanyShortCodeStatic() ;
//        if($system_code == Constants::ESS_COMPANY_SHORT_CODES['APEX']){
//            $param = array_merge($param, ['margin_top'=>9, 'margin_left'=>9,'margin_right'=>9]);
//        }
        $param = array_merge($param, ['margin_top'=>7, 'margin_left'=>7,'margin_right'=>7]);
        $mpdf = new Mpdf($param);
        $mpdf->useSubstitutions = false;
        $sidebar_color = Constants::PAYSLIP_COLOR;
        $tr = "table.gra {
    border: 1px solid #000000!important;
    border-collapse: initial;
}";
        $stylesheet = file_get_contents(public_path('css/print.css')); // external css
        $stylesheet .= $tr ;
        $mpdf->SetHTMLFooter("
            <table width='100%'><tr><td width='33%' style='text-align:left;'><i>{PAGENO}</i></td>
                    <td width='83%' style='text-align:right;'><i>Generated on {DATE jS F, Y} @ {DATE h:i:s A} </i></td></tr>
            </table>");
        $mpdf->WriteHTML($stylesheet,1);
        foreach(str_split($content, Constants::PRINT_BUFFER_SIZE ) as $text){
            $mpdf->WriteHTML($text,2);
        }
        $mpdf->Output($filename, $output);
    }


}
