<?php

use App\Models\Audit\ErrorLog;
use App\Models\Common\NumberGenerator;
use App\Models\Employees\Employee;
use App\Models\Finance\FinanceUser;
use App\Models\Audit\LogAction;
use App\Models\Organization\Company;
use App\Models\Settings\Configuration;
use App\Models\Settings\Currency;
use App\Models\Workflow\WorkflowPosition;
use Carbon\Carbon;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

if (!function_exists('user')) {
    /**
     * Generate a url for the application.
     *
     * @param null $guard
     * @return object
     */
    function user($guard=null)
    {
        return auth()->guard($guard)->user();
    }
}

if (! function_exists('should_queue')) {
    /**
     * Check if queue is enabled.
     */
    function should_queue(): bool
    {
        return config('queue.default') != 'sync';
    }
}

if (!function_exists('generate_otp')) {
    /**
     * Generates a random token
     *
     * @return String      [description]
     */
    function generate_otp($length = 6)
    {
        $otp = '';
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $otp .= $characters[rand(0, $charactersLength - 1)];
        }

        return $otp;
    }
}

if (!function_exists('enable_sms')) {
    /**
     * Format Date
     *
     * @param        $date
     * @param string $format
     * @return bool|string
     */
    function enable_sms()
    {
        return env('ENABLE_SMS', 1);
    }
}


if (!function_exists('company_code')) {
    /**
     * Generate a url for the application.
     *
     * @return string
     */
    function company_code()
    {
        return env('COMPANY_CODE', 'alerio');
    }
}


if (!function_exists('generate_task_number')) {
    /**
     * Generates a random token
     *
     * @return String      [description]
     */
    function generate_task_number(): string
    {
        return NumberGenerator::gen(\App\Models\Task\Task::class);
    }
}


if (!function_exists('is_workflow_admin')) {
    /**
     * Check if user is tasks.
     *
     * @return boolean
     */
    function is_workflow_admin()
    {
        if (user()->hasRole('developer|admin'))
            return true;
        else if(WorkflowPosition::firstWhere('employee_id', user()->id))
            return true;
        else if(\App\Models\Auth\User::firstWhere('supervisor_id', user()->id))
            return true;
        else
            return false;
    }
}

if (! function_exists('collect')) {
    /**
     * Create a collection from the given value.
     *
     * @template TKey of array-key
     * @template TValue
     *
     * @param  \Illuminate\Contracts\Support\Arrayable<TKey, TValue>|iterable<TKey, TValue>|null  $value
     * @return \Illuminate\Support\Collection<TKey, TValue>
     */
    function collect($value = [])
    {
        return new Collection($value);
    }
}

if (! function_exists('user_id')) {
    /**
     * Get id of current user.
     */
    function user_id()
    {
        return optional(user())->id;
    }
}


if (! function_exists('company')) {
    /**
     * Get current/any company model.
     */
    function company($id = null)
    {
        $company = null;

        if (is_null($id)) {
            $company = Company::getCurrent();
        }

        if (is_numeric($id)) {
            $company = Company::find($id);
        }

        return $company;
    }
}

if (! function_exists('company_id')) {
    /**
     * Get id of current company.
     */
    function company_id()
    {
        return optional(company())->id ?: request()->user()?->company_id;
    }
}

if (! function_exists('is_owner')) {
    /**
     * Get id of current company.
     */
    function is_owner()
    {
        return user()->company_id == 1;
    }
}

if (!function_exists('employee')) {
    /**
     * Generate a url for the application.
     *
     * @param null $guard
     * @return object
     */
    function employee()
    {
        return user()->employee;
    }
}

if (!function_exists('admin_user')) {
    /**
     * Generate a url for the application.
     *
     * @return string
     */
    function admin_user()
    {
        return auth()->guard()->user();
    }
}


if (!function_exists('get_permission_name')) {
    /**
     * Generate a url for the application.
     *
     * @return string
     */
    function get_permission_name()
    {
        // Find the proper controller for common API endpoints
        $route = app(Route::class);

        // Get the controller array
        $arr = array_reverse(explode('\\', explode('@', $route->getAction()['uses'])[0]));

        $controller = '';

        // Add folder
//        if (!in_array(strtolower($arr[1]), ['api', 'controllers'])) {
//            $controller .= Str::kebab($arr[1]) . '-';
//        }

        // Add file
        $controller .= Str::kebab($arr[0]);

        return Str::plural(str_replace('-controller', '', $controller));
    }
}

if (!function_exists('user_company')) {
    /**
     * Generate user company
     *
     * @return object
     */
    function user_company(int $id = 1)
    {
        return optional(Company::find($id));
    }
}

if (!function_exists('auth_user_company')) {
    /**
     * Generate user company
     *
     * @return object
     */
    function auth_user_company()
    {
        return optional(\App\Models\Company::find(user()->company_id));
    }
}



if (!function_exists('is_request_approver')) {
    /**
     * Generate a url for the application.
     *
     * @return string
     */
    function is_request_approver(int $employee_id)
    {
        $arr = \App\Models\WorkflowRequestDetail::pluck('implementor_id')->all();

        if(in_array($employee_id, $arr)){
            return true;
        }

        return false;
    }
}


if (!function_exists('can_access_all_companies')) {
    /**
     * Generate a url for the application.
     *
     * @return string
     */
    function can_access_all_companies()
    {
        return user()->access_all_companies;
    }
}



if (!function_exists('generate_initials')) {
    /**
     * Get initials
     *
     * @param $full_name
     * @return string
     */
    function generate_initials($full_name)
    {
        $words = explode(" ", $full_name);
        $initials = null;
        foreach ($words as $w) {
            $initials .= $w[0];
        }
        return $initials; //JB
    }
}

if (!function_exists('currency')) {
    /**
     * Get initials
     *
     * @return Currency
     */
    function currency($currency = 'GHS')
    {
        return Currency::firstWhere('code', $currency);
    }
}


if (!function_exists('update_last_active')) {
    /**
     * Check is user belongs t owner company
     *
     * @return string
     */
    function update_last_active($userId)
    {
        $user = \App\Models\User::find($userId);
        $user->last_active  = Carbon::now();
        $user->save();
    }
}

if (!function_exists('is_admin')) {
    /**
     * Check if user is admin.
     *
     * @return boolean
     */
    function is_admin()
    {
        if (user()->hasRole('admin', 'web'))
            return true;
        else
            return false;
    }
}


if (!function_exists('log_activity')) {
    /**
     * Log Activity
     * @param string $description
     * @param null $eloquent
     * @param null $logAction
     * @param null $division
     */
    function log_activity($description = '', $eloquent = null, $logAction=null)
    {
        event(new App\Events\ActivityTriggered($description, $logAction, $eloquent));
    }
}

if (!function_exists('standardize')) {
    /**
     * Checks if the string passed contains a pipe '|' and explodes the string to an array.
     * @param string|array $value
     * @param bool $toArray
     * @return string|array
     */
    function standardize($value, $toArray = false)
    {
        if (is_array($value) || ((strpos($value, '|') === false) && !$toArray)) {
            return $value;
        }
        return explode('|', $value);
    }
}

if (!function_exists('format_date')) {
    /**
     * Format Date
     *
     * @param        $date
     * @param string $format
     * @return bool|string
     */
    function format_date($date, $format = "d F Y")
    {
        if (isset($date))
            return date($format, strtotime($date));
        else
            return '';
    }
}

if (!function_exists('format_excel_date')) {
    /**
     * Format Date
     *
     * @param        $date
     * @param string $format
     * @return bool|string
     */
    function format_excel_date($date, $format = 'd/m/Y'){
        try {
            return \Carbon\Carbon::createFromFormat($format, $date)->format(env('Date_Format'));
        }catch (Exception $ex){
            return null;
        }
    }
}


if (!function_exists('generate_random_number')) {
    /**
     * Generate Application Number/Student ID
     *
     * @param null $pre
     * @return bool|string
     */
    function generate_random_number($pre = null)
    {
        $certificateNumber = strtoupper($pre).rand(10000000, 99999999).date('y');
//
//        $validator = Validator::make(['certificate_number'=>$certificateNumber],['certificate_number'=>'unique:customer_certificates,certificate_number']);
//
//        if($validator->fails()){
//            return generate_random_number($pre);
//        }

        return $certificateNumber;
    }
}
if (!function_exists('generate_staff_id')) {
    /**
     * Generate Application Number/Student ID
     *
     * @param null $company
     * @param null $code
     * @return bool|string
     */
    function generate_staff_id($code = null, $suffix = null)
    {
        $total_digits = 4;
        $id = NumberGenerator::gen(Employee::class);
        $company = company();
        $count = str_pad($id, $total_digits, '0', STR_PAD_LEFT);
        $prefix = $staffId = "";

        if($company){
            $prefix = $company->staff_id_prefix;
        }

        if($company->staff_id_format == 2)
        {
            $staffId = $prefix.date('Y').$count;
        }
        elseif($company->staff_id_format == 6)
        {
            $suffix = $suffix??date('y');
            $count = str_pad($id, $total_digits, '0', STR_PAD_LEFT);
            $staffId = $prefix.$count.$suffix;
        }
        elseif($company->staff_id_format == 3)
        {
            $count = str_pad($id, 3, '0', STR_PAD_LEFT);
            $staffId = $prefix.date('dmy').$count;
        }
        elseif($company->staff_id_format == 4)
        {
            $count = str_pad($id, 3, '0', STR_PAD_LEFT);
            $staffId = $prefix.$code.date('dmy').$count;
        }
        elseif($company->staff_id_format == 5)
        {
            $count = str_pad($id, $total_digits, '0', STR_PAD_LEFT);
            $staffId = $prefix.$code.$count;
        }else{
            $staffId = $prefix.$count;
        }

        $exists = DB::table('employees')->where('staff_id', $staffId)->get();

        if(count($exists) > 0)
        {
            return generate_staff_id($code, $prefix);
        }

        return $staffId;
    }
}

if (!function_exists('generate_username')) {
    /**
     * Generate Application Number/Student ID
     *
     * @param null $pre
     * @return bool|string
     */
    function generate_username($pre = null)
    {
        $username = \Illuminate\Support\Str::slug(strtoupper($pre).rand(1000, 9999).date('y'));

        $validator = Validator::make(['username'=>$username],['username'=>'unique:customers']);

        if($validator->fails()){
            return generate_username($pre);
        }

        return $username;
    }
}


if (!function_exists('route_admin')) {
    /**
     * Generate a URL to a named route.
     *
     * @param string $name
     * @return string
     */
    function route_admin($name)
    {
        return route('membership.'.$name);
    }
}

if (!function_exists('route_admin1')) {
    /**
     * Generate a URL to a named route.
     *
     * @param  string                    $name
     * @param  array                     $parameters
     * @param  bool                      $absolute
     * @param  Route $route
     * @return string
     */
    function route_admin1($name, $parameters = [], $absolute = true, $route = null)
    {
        return Redirect::to(app('url')->route('membership.'.$name, $parameters, $absolute, $route));
    }
}

if (!function_exists('generate_token')) {
    /**
     * Generates a random token
     *
     * @param  String $str [description]
     *
     * @return String      [description]
     */
    function generate_token($str = null)
    {
        $str = isset($str) ? $str : \Illuminate\Support\Str::random();
        $value = str_shuffle(sha1($str . microtime(true)));
        $token = hash_hmac('sha1', $value, env('APP_KEY'));

        return $token;
    }
}

if (!function_exists('generate_order_number')) {
    /**
     * Generates a random token
     *
     * @param  String $str [description]
     *
     * @return String      [description]
     */
    function generate_order_number($str = null)
    {
        return "EZ".time();
    }
}


if (!function_exists('csv_to_array')) {
    /**
     * Convert a csv to an array
     *
     * @param string $filename
     * @param string $delimiter
     * @return array|bool
     */
    function csv_to_array($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }

        $header = null;
        $data = [];
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                }
                else {
                    if (count($header) == count($row)) {
                        $data[] = array_combine($header, $row);
                    }
                }
            }
            fclose($handle);
        }

        return $data;
    }
}


if (!function_exists('array_search_value')) {
    /**
     * Search for a given value in $haystack
     * Can overide the default key to search on
     *
     * @param        $value
     * @param        $haystack
     * @param string $k
     * @return bool
     */
    function array_search_value($value, $haystack, $k = 'id')
    {
        foreach ($haystack as $key => $item) {
            if ($value == $item[$k]) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('notify_admins')) {
    function notify_admins($class, $argument, $admins, $forceEmail = "")
    {
        if (strlen($forceEmail) >= 2) {
            $admin = \App\Models\User::firstWhere('email', $forceEmail);
            $admin->notify(new $class($argument));
        }

        if ($admins) {
            foreach ($admins as $a => $admin) {
                $admin->notify(new $class($argument));
            }
        }
    }
}

if (!function_exists('get_admins')) {
    function get_admins()
    {

    }
}

if (!function_exists('send_mail')) {
    /**
     * @param $class
     * @param $argument
     * @param $recipient
     */
    function send_mail($class, $argument, $recipient)
    {
       try{
           Mail::to($recipient)->send(new $class($argument));
       }catch (Exception $ex){
           log_error(format_exception($ex), null, 'send-email-failed');
       }
    }
}


if (!function_exists('send_contract_mail')) {
    function send_contract_mail($email,$message,$subject) {
        $data = [
        'name'=>env('APP_NAME', ''),
        'content' =>$message,
        'email' =>$email,
        'subject' =>$subject

    ];
    return   Mail::send('emails.contract.alerts', $data, function($message) use ($data) {
    $sendfrom = env('MAIL_USERNAME', '');
    $brand = env('APP_NAME', '');
        $message->to($data['email'], $brand)->subject
        ($data['subject']);
        $message->from($sendfrom,$brand);
    });
  }
}

if (!function_exists('send_sms')) {
    /**
     * @param $message
     * @param $phoneNumber
     */
    function send_sms($message, $phoneNumber)
    {
        $sms = new \App\Services\SmsService();
        try{
            $sms->sendSmsViaTxtConnect($message, $phoneNumber);
        }catch (Exception $ex){
            log_error(format_exception($ex), null, 'send-sms-failed');
        }
    }
}


if (!function_exists('send_mail_job')) {
    /**
     * @param $class
     * @param $argument
     * @param $recipient
     */
    function send_mail_job($class, $argument, $recipient)
    {
        //dispatch(new \App\Jobs\SendMailJob($class, $argument, $recipient));
    }
}

if (!function_exists('general_settings')) {
    /**
     * @return mixed
     */
    function general_settings()
    {
        return \App\Models\GeneralSetting::all()->first();
    }
}

if (!function_exists('settings')) {
    /**
     * @param $field
     * @param string $default
     * @return mixed
     */
    function settings($field, $default = "")
    {
        return Configuration::get_setting($field)??$default;
    }
}

if (!function_exists('set_settings')) {
    /**
     * @param $field
     * @param string $default
     * @return
     */
    function set_settings($field, $value, $company = null)
    {
        return \App\Models\Configuration::save_setting($field, $value, $company);
    }
}


if (!function_exists('log_error')) {
    /**
     * Log Action
     *
     * @param Exception $exception
     * @param string $eloquent
     * @param $logAction
     * @return ErrorLog
     */
    function log_error(array $exception, $eloquent, $logAction)
    {
        $logAction = LogAction::firstOrCreate(['slug' => $logAction]);

        $subject = null;
        if (!is_null($eloquent)) {
            $subject = get_class($eloquent);
        }
        $subjectId = $eloquent ? $eloquent->id : null;

        if ($eloquent && !strpos($subject, '\Models')) {
            $subjectId = null;
            $subject = 'App\Models\User';
        }

        $data = new ErrorLog();

        $data['subject_id'] = $subjectId;
        $data['message'] = $exception["message"]??"";
        $data['file'] = $exception["file"]??"";
        $data['error_code'] = $exception["code"]??"";
        $data['line'] = $exception["line"]??"";
        $data['log_type_id'] = $logAction->logType->id;
        $data['log_action_id'] = $logAction->id;
        $data['subject_type'] = $subject;
        $data['client_ip'] = request()->getClientIp();
        $data['client_agent'] = request()->userAgent();

        if (user()){
            $data['user_id'] = user()->id;
            $data['user_model'] = get_class(user());
        }

        return ErrorLog::create($data->toArray());
    }
}

if (!function_exists('format_exception')) {
    /**
     * Log Action
     *
     * @param Exception $exception
     * @return array
     */
    function format_exception(Exception $exception)
    {
        $ex = [];
        $ex['message'] = $exception->getMessage();
        $ex['line'] = $exception->getLine();
        $ex['file'] = $exception->getFile();
        $ex['code'] = $exception->getCode();

        return $ex;
    }
}

if (!function_exists('update_finance_user')) {
    /**
     * Log Action
     *
     * @param string $email
     * @param array $data
     * @return string
     */
    function update_finance_user(string $email, array $data)
    {
        if(!settings('enable_account', 0))
            return;
        $usr = null;
        try {
            $usr = FinanceUser::firstWhere('email', $email);
            if($usr != null)
            {
                $usr->update($data);
                log_activity("User detail updated for ".$email, $usr, "update-finance-user-successful");
            }
        }catch (\Exception $ex){
            log_error(format_exception($ex), $usr, "update-finance-user-failed");
        }
    }
}





