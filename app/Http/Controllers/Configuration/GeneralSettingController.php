<?php

namespace App\Http\Controllers\Configuration;

use App\Constants\Constants;
use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Http\Controllers\Controller;
use App\Mail\TestMail;
use App\Services\Interfaces\ISettingService;
use App\Traits\SmsTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class GeneralSettingController extends Controller
{
    use SmsTrait;

    private ISettingService $settingService;

    /**
     * @param ISettingService $settingService
     */
    public function __construct(ISettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function general()
    {
        $data = [];

        $zones_array = array();
        $timestamp = time();

        $date_formats = Constants::DATE_FORMATS;

        foreach (timezone_identifiers_list() as $key => $zone)
        {
            date_default_timezone_set($zone);
            $zones_array[$key]['zone'] = $zone;
            $zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
        }

        $rec = $this->settingService->getRecords([
            'app_name',
            'company_name',
            'description',
            'company_email',
            'company_address',
            'send_emails',
            'verify_email',
            'vision',
            'mission',
            'favicon',
            'logo',
            'company_phone_number',
            'days_before_password_expiry',
            'report_start_year',
            'report_title',
            'notification_medium',
            'expire_passwords',
            'status',
            'company_notification_email',
            'hide_website',
            'address_line_2',
            'company_email_alt',
            'company_phone_number_alt',
            'google_map',
            'time_zone',
            'date_format'
        ]);

        return view('configuration.settings.general', compact('rec', 'data', 'zones_array', 'date_formats'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request)
    {
        $data = $request->except('_token', '_method', 'id');

        if($request->has('general'))
        {
            $validatedData = $request->validate([
                'app_name' => 'required',
                'company_name' => 'required',
                'company_phone_number' => 'required',
                'company_email' => 'required|email',
                'company_address' => 'required',
                'company_notification_email' => 'required|email',
            ]);
        }

        if($request->has('currency'))
        {
            $data = $request->except('_token', '_method', 'logo', 'id', 'favicon');
            $validatedData = $request->validate([
                'currency_symbol',
                'currency_code',
                'digit_grouping_method',
                'decimal_symbol',
                'thousand_separator'
            ]);
        }

        if($request->has('task'))
        {
            $data = $request->except('_token', '_method', 'logo', 'id', 'favicon');
            $validatedData = $request->validate([
                'task_number_prefix',
                'enable_sms_notification',
                'enable_task_reopen',
                'enforce_due_date',
            ]);
        }

        $results = $this->settingService->createUpdateSetting($data);

        if ($results->status != ResponseType::SUCCESS)
        {
            return redirect()->back()->with('error', $results->message);
        }
        request()->session()->flash('message', "Settings successfully updated.");

        return redirect()->back();
    }

    public function siteMail()
    {
        $rec = new \stdClass();

        $emailData = $this->emailKeys();

        foreach ($emailData as $key => $value)
        {
            $rec->{$key} = $this->settingService->getEnv($value);
        }

        $data['queue_connection_options'] = [
            'sync' => 'Sync',
            'database' => 'Database',
        ];

        $data['email_sending_options'] = [
            'smtp' => 'SMTP',
            'mailgun' => 'Mailgun',
            'log'   => 'Turn off email'
        ];

        return view('configuration.settings.mail', compact('rec', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateSiteMail(Request $request)
    {
        $data = $request->except('_token', '_method', 'id');

        if($request->has('test_email_address'))
        {
            $validatedData = $request->validate([
                'test_email_address' => 'required',
            ]);
            try {

                Mail::to($request->test_email_address)->send(new TestMail());

                request()->session()->flash('message', "Email successfully sent.");
            } catch (\Exception $e) {

                request()->session()->flash('error', "Could not send the email. Please check your email settings");
            }
        }
        else{
            if ($request['company_email_send_using'] == 'mailgun') {
                $validatedData = $request->validate([
                    'company_email_send_using' => 'required',
                    'company_email_mailgun_domain' => 'required',
                    'company_email_mailgun_key' => 'required',
                    'company_email_from_address' => 'required|email'
                ]);
            } else {
                $validatedData = $request->validate([
                    'company_email_send_using' => 'required',
                    'company_email_smtp_host' => 'required',
                    'company_email_smtp_port' => 'required',
                    'company_email_from_address' => 'required|email',
                    'company_email_smtp_password' => 'required'
                ]);
            }

            foreach ($this->emailKeys() as $input => $envKey)
            {
                $keys[] = ['key' => $envKey, 'value' => $request[$input]];
            }

            $this->settingService->updateEnvKeys($keys);

            request()->session()->flash('message', "Settings successfully updated.");
        }

        return redirect()->back();
    }

    public function siteSMS()
    {
        $rec = $this->settingService->getRecords([
            'yoovi_sms_url',
            'yoovi_sms_api_key',
            'yoovi_sms_send_id',
            'npontu_sms_url',
            'npontu_sms_username',
            'npontu_sms_password',
            'npontu_sms_source',
            'npontu_sms_request_method',
            'sms_base_url',
            'sms_send_to_param_name',
            'sms_msg_param_name',
            'sms_request_method',
            'sms_header_1',
            'sms_header_2',
            'sms_header_3',
            'sms_header_val_1',
            'sms_header_val_2',
            'sms_header_val_3',
            'sms_param_1',
            'sms_param_2',
            'sms_param_3',
            'sms_param_4',
            'sms_param_5',
            'sms_param_val_1',
            'sms_param_val_2',
            'sms_param_val_3',
            'sms_param_val_4',
            'sms_param_val_5',
            'sms_service',
        ]);


        $data['queue_connection_options'] = [
            'sync' => 'Sync',
            'database' => 'Database',
        ];

        $data['sms_sending_options'] = [
            'yoovi'    => 'Yoovi',
            'npontu'    => 'Npontu',
            'other'    => 'Other'
        ];

        return view('configuration.settings.sms', compact('rec', 'data'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateSiteSms(Request $request)
    {
        $data = $request->except('_token', '_method', 'id');

        if($request->has('sms_test_number'))
        {
            $validatedData = $request->validate([
                'sms_test_number' => 'required',
            ]);
            try {

                $data['phone_number'] = $data['sms_test_number'];
                $data['message'] = 'Test sms from '.settings("app_name");
                $this->sendSms($data['phone_number'], $data['message']);
                request()->session()->flash('message', "SMS successfully sent.");
            } catch (\Exception $e) {
                request()->session()->flash('error', "Could not send the sms. Please check your sms settings");
            }
        }
        else{
            $results = $this->settingService->createUpdateSetting($data);

            request()->session()->flash('message', "Settings successfully updated.");
        }

        return redirect()->back();
    }

    public function task()
    {
        $data = [];
        $rec = $this->settingService->getRecords([
            'task_number_prefix',
            'enable_sms_notification',
            'enable_task_reopen',
            'enforce_due_date',
        ]);

        return view('configuration.settings.task', compact('rec', 'data'));
    }


    private function emailKeys()
    {
        return [
            'company_email_send_using' => 'MAIL_MAILER',
            'company_email_smtp_host' => 'MAIL_HOST',
            'company_email_smtp_port' => 'MAIL_PORT',
            'company_email_smtp_username' => 'MAIL_USERNAME',
            'company_email_smtp_password' => 'MAIL_PASSWORD',
            'company_email_encryption' => 'MAIL_ENCRYPTION',
            'company_email_from_address' => 'MAIL_FROM_ADDRESS',
            'company_email_mailgun_domain' => 'MAILGUN_DOMAIN',
            'company_email_mailgun_key' => 'MAILGUN_SECRET',
            'queue_connection' => 'QUEUE_CONNECTION',

        ];
    }

    public function exportDatabase()
    {
        if(auth()->user()->id != 1)
        {
            return redirect()->back()->with('msg', ResponseMessage::DEFAULT_NOT_AUTHORIZED);
        }
        // Database configuration
        $host = env('DB_HOST');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $database_name = env('DB_DATABASE');

        // Get connection object and set the charset
        $conn = mysqli_connect($host, $username, $password, $database_name);
        $conn->set_charset("utf8");


        // Get All Table Names From the Database
        $tables = array();
        $sql = "SHOW TABLES";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_row($result)) {
            $tables[] = $row[0];
        }

        $sqlScript = "";
        foreach ($tables as $table) {

            // Prepare SQLscript for creating table structure
            $query = "SHOW CREATE TABLE $table";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_row($result);

            $sqlScript .= "\n\n" . $row[1] . ";\n\n";


            $query = "SELECT * FROM $table";
            $result = mysqli_query($conn, $query);

            $columnCount = mysqli_num_fields($result);

            // Prepare SQLscript for dumping data for each table
            for ($i = 0; $i < $columnCount; $i ++) {
                while ($row = mysqli_fetch_row($result)) {
                    $sqlScript .= "INSERT INTO $table VALUES(";
                    for ($j = 0; $j < $columnCount; $j ++) {
                        $row[$j] = $row[$j];

                        if (isset($row[$j])) {
                            $sqlScript .= '"' . $row[$j] . '"';
                        } else {
                            $sqlScript .= '""';
                        }
                        if ($j < ($columnCount - 1)) {
                            $sqlScript .= ',';
                        }
                    }
                    $sqlScript .= ");\n";
                }
            }

            $sqlScript .= "\n";
        }

        if(!empty($sqlScript))
        {
            // Save the SQL script to a backup file
            $backup_file_name = public_path().'/'.$database_name . '_backup_' . time() . '.sql';
            //return $backup_file_name;
            $fileHandler = fopen($backup_file_name, 'w+');
            $number_of_lines = fwrite($fileHandler, $sqlScript);
            fclose($fileHandler);

//			$zip = new ZipArchive();
//			$zipFileName = $database_name . '_backup_' . time() . '.zip';
//			$zip->open(public_path() . '/' . $zipFileName, ZipArchive::CREATE);
//			$zip->addFile($backup_file_name, $database_name . '_backup_' . time() . '.sql');
//			$zip->close();

            // Download the SQL backup file to the browser
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($backup_file_name));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($backup_file_name));
            ob_clean();
            flush();
            readfile($backup_file_name);
            exec('rm ' . $backup_file_name);
        }
        //return redirect('public/' . $zipFileName);
    }
}
