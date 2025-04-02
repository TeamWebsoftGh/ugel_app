<?php

namespace App\Services;

use App\Constants\Constants;
use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IBaseService;

class ServiceBase implements IBaseService
{
    protected Response $response;

    /**
     * ServiceBase constructor.
     */
    public function __construct()
    {
        $this->response = new Response();
    }

    /**
     * Method to generate a PDF
     *
     * @param string $content
     * @param string $filename
     * @param string $format
     * @param string $output
     * @param bool $isGRA
     * @throws \Mpdf\MpdfException
     */
    private function generatePdf(string $content, $filename = 'download.pdf', $format = 'A4', $output = "I", $isGRA = false)
    {
        // Set the default format if not provided
        $format = $format ?: 'A4';

        // Increase the PCRE backtrack limit to handle large content
        ini_set("pcre.backtrack_limit", "70000000");

        // Set mPDF parameters
        $params = [
            'mode' => 'utf-8',
            'format' => $format,
            'pagenumPrefix' => 'Page ',
            'margin_top' => 7,
            'margin_left' => 7,
            'margin_right' => 7
        ];

        // Create a new instance of mPDF
        $mpdf = new Mpdf($params);
        $mpdf->useSubstitutions = false;

        // Common CSS setup
        $sidebar_color = Constants::PAYSLIP_COLOR;
        $tr = ".headerRow td,.headerRow th{background-color:$sidebar_color;color:#FFFFFF;padding:1mm;}";

        // Include external stylesheet
        $stylesheet = file_get_contents(public_path('css/print.css'));
        $stylesheet .= $tr;

        // Define footer
        $emp_name = auth()->user() ? auth()->user()->username : '';
        $footer = "
            <table width='100%'>
                <tr>
                    <td width='33%' style='text-align:left;'><i>{PAGENO}</i></td>
                    <td width='83%' style='text-align:right;'><i>Generated on {DATE jS F, Y} @ {DATE h:i:s A} by $emp_name</i></td>
                </tr>
            </table>";

        // Set footer for GRA printing
        if ($isGRA) {
            $footer = "
                <table width='100%'>
                    <tr>
                        <td width='33%' style='text-align:left;'><i>{PAGENO}</i></td>
                        <td width='83%' style='text-align:right;'><i>Generated on {DATE jS F, Y} @ {DATE h:i:s A}</i></td>
                    </tr>
                </table>";
        }

        $mpdf->SetHTMLFooter($footer);

        // Write the content to the PDF
        $mpdf->WriteHTML($stylesheet, 1);

        // Split content into smaller chunks for large documents
        foreach (str_split($content, Constants::PRINT_BUFFER_SIZE) as $text) {
            $mpdf->WriteHTML($text, 2);
        }

        // Output the PDF
        $mpdf->Output($filename, $output);
    }

    /**
     * Generate a standard PDF document.
     *
     * @param string $content
     * @param string $filename
     * @param string $format
     * @param string $output
     * @throws \Mpdf\MpdfException
     */
    public function print(string $content, $filename = 'download.pdf', $format = 'A4', $output = "I")
    {
        $this->generatePdf($content, $filename, $format, $output, false);
    }

    /**
     * Generate a GRA PDF document.
     *
     * @param string $content
     * @param string $filename
     * @param string $format
     * @param string $output
     * @throws \Mpdf\MpdfException
     */
    public function printGRA(string $content, $filename = 'download.pdf', $format = 'A4-L', $output = "I")
    {
        $this->generatePdf($content, $filename, $format, $output, true);
    }

    /**
     * Helper method to construct a response object.
     *
     * @param mixed $data
     * @return Response
     */
    public function buildCreateResponse($data): Response
    {
        if ($data)
        {
            $this->response->status = ResponseType::SUCCESS;
            $this->response->message = ResponseMessage::DEFAULT_SUCCESS_CREATE;
            $this->response->data = $data;
        } else
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;
        }

        return $this->response;
    }

    public function buildUpdateResponse($data, $result): Response
    {
        if ($result)
        {
            $this->response->status = ResponseType::SUCCESS;
            $this->response->message = ResponseMessage::DEFAULT_SUCCESS_UPDATE;
            $this->response->data = $data;
        } else
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;
        }

        return $this->response;
    }

    public function buildDeleteResponse($result, $msg = null): Response
    {
        if ($result)
        {
            $this->response->status = ResponseType::SUCCESS;
            $this->response->message = $msg??ResponseMessage::DEFAULT_SUCCESS_DELETE;
        } else
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;
        }

        return $this->response;
    }

    /**
     * Build error response
     */
    public function errorResponse(string $message): Response
    {
        $this->response->status = ResponseType::ERROR;
        $this->response->message = $message;

        return $this->response;
    }
}
