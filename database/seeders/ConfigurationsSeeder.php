<?php

namespace Database\Seeders;

use App\Models\Common\Priority;
use App\Models\Resource\Category;
use App\Models\ServiceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ConfigurationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('configurations')->truncate();

        // Settings
        $settings = [
            'app_name'                          => 'UGEL APP',
            'company_name'                      => 'Websoft',
            'logo'                              => 'uploads/company/logo.png',
            'company_phone_number_alt'          => '024-4565-1234',
            'company_address'                   => 'Accra, Ghana',
            'company_country'                   => 'Ghana',
            'favicon'                           => 'uploads/company/favicon.png',
            'description'                       => '',
            'company_email'                     => 'info@ugel.ug.edu.gh',
            'company_phone_number'              => '024-4565-1234',
            'address_line_2'                    => 'Digital Address: GC-129-0265',
            'company_notification_email'        => 'info@ugel.ug.edu.gh',
            'facebook'							=> 'http://www.facebook.com/',
            'twitter'							=> 'http://www.twitter.com',
            'instagram'							=> 'http://www.instagram.com',
            'linkedin'							=> 'http://www.linkedin.com',
            'footer_text'						=> 'All rights reserved | Alerio',
            'system_starting_year'              => date("Y"),
            'time_zone'                         => 'Africa/Accra',
            'app_version'			        	=> '1',
            'verify_email'			        	=> 0,
            'report_start_year'			        => date('Y'),
            'google_map_url'			        => '',
            'days_before_password_expiry'       => 90,
            'notification_medium'		        => 'mail',
            'primary_color'			            => '#000000',
            'secondary_color'	                => '#000000',
            'nav_color'	                        => '#000000',
            'theme'	                            => 'style.default.css',
            'finance_url'                       => '#',
            'expire_passwords'			        => 0,
            'date_format'			            => 'd-M-Y',
            'company_label'			            => "Company",
            'enable_customer_service'	        => 0,
            'enable_hrm'			            => 1,
            'enable_workflow'			        => 1,
            'currency_code'			            => 'GHS',
            'yoovi_sms_api_key'			        => 'eldOT1FQSVFOSlRKWktxUW9xQ3E',
            'yoovi_sms_send_id'			        => 'UGEL',
            'yoovi_sms_url'			            => 'https://pipe.yoovi.me',
        ];

        foreach ($settings as $key=>$value)
        {
            DB::table('configurations')->insert(
                [
                    'name' => ucwords(Str::replace("_", " ", $key)),
                    'option_key' => $key,
                    'module' => 'core',
                    'option_value' => $value,
                    'category' => 'general',
                    'type' => 'text',
                    'options' => '',
                    'class' => '',
                    'required' => 0,
                    'db_columns' => '',
                    'displayed' => 1,
                    'rules' => ''
                ]);
        }

        Priority::create([
            'name' => 'Urgent',
            'company_id' => 1,
        ]);

        Priority::create([
            'name' => 'High',
            'company_id' => 1,
        ]);

        Priority::create([
            'name' => 'Medium',
            'company_id' => 1,
        ]);

        Priority::create([
            'name' => 'Low',
            'company_id' => 1,
        ]);
    }
}
