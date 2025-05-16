<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Settings\Configuration;
use App\Repositories\Interfaces\ISettingRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\ISettingService;
use App\Traits\UploadableTrait;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class SettingService extends ServiceBase implements ISettingService
{
    use UploadableTrait;

    private ISettingRepository $settingRepo;

    /**
     * SettingService constructor.
     * @param ISettingRepository $setting
     */
    public function __construct(ISettingRepository $setting)
    {
        parent::__construct();
        $this->settingRepo = $setting;
    }

    /**
     * @param array $params
     * @return Response
     */
    public function createUpdateSetting(array $params)
    {
        //Declaration
        $setting = null;

        try{
            //Process Request
            if (isset($params['favicon']) && $params['favicon'] instanceof UploadedFile) {
                $params['favicon'] = $this->uploadPublic($params['favicon'], 'favicon', 'logo');
            }

            if (isset($params['logo']) && $params['logo'] instanceof UploadedFile) {
                $params['logo'] = $this->uploadPublic($params['logo'], 'logo', 'logo');
            }

            if (isset($params['date_format'])) {
                $js_format = config('date_format_conversion.' . $params['date_format']);

                $keys[] = ['key' => 'Date_Format', 'value' => $params['date_format']];
                $keys[] = ['key' => 'Date_Format_JS', 'value' => $js_format];
                $this->updateEnvKeys($keys);
            }

            if (isset($params['ENABLE_CLOCKIN_CLOCKOUT'])) {
                $keys[] = ['key' => 'ENABLE_CLOCKIN_CLOCKOUT', 'value' => $params['enable_clockin_clockout']];
                $this->updateEnvKeys($keys);
            }

            if (isset($params['ENABLE_EARLY_CLOCKIN'])) {
                $keys[] = ['key' => 'ENABLE_EARLY_CLOCKIN', 'value' => $params['enable_early_clockin']];
                $this->updateEnvKeys($keys);
            }

            foreach ($params as $key => $value)
            {
                $setting = Configuration::updateOrCreate([
                    'option_key' => $key
                ]);

                $setting->option_value = trim($value);

                $setting->save();
            }

        }catch (\Exception $ex){
            log_error(format_exception($ex), $setting??new Configuration(), 'update-setting-failed');
        }

        //Check if Successful
        if (!$setting || $setting == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-setting-successful';
        $auditMessage ='Setting successfully updated.';

        log_activity($auditMessage, $setting, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $setting;

        return $this->response;
    }


    /**
     * @param array $data
     * @param Configuration $setting
     * @return Response
     */
    public function updateSetting(array $data, Configuration $setting)
    {
        $result = false;
        try{

            if (isset($params['favicon']) && $params['favicon'] instanceof UploadedFile) {
                $params['favicon'] = $this->uploadOne($params['favicon'], 'company', 'favicon');
            }

            if (isset($params['logo']) && $params['logo'] instanceof UploadedFile) {
                $params['logo'] = $this->uploadOne($params['logo'], 'company', Str::random(8));
            }

            $result = $this->settingRepo->updateSetting($data, $setting->id);

        }catch (\Exception $ex){
            log_error(format_exception($ex), $setting, 'update-setting-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-settings-successful';
        $auditMessage ='General Setting successfully updated.';

        log_activity($auditMessage, $setting, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $setting;

        return $this->response;
    }


    /**
     * @param int $id
     * @return Configuration|null
     */
    public function findSettingById(int $id)
    {
        return $this->settingRepo->findSettingById($id);
    }


    public function findSettingBySlug(string $slug)
    {
        return $this->settingRepo->findOneByOrFail(['slug' => $slug]);
    }

    public function deleteSetting(Configuration $setting)
    {
        try{
            $result = $this->settingRepo->deleteSetting($setting->id);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $setting, 'delete-setting-failed');
        }

        if (!isset($result) || !$result) {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-settings-successful';
        $auditMessage ='Setting successfully deleted. Setting: '.$setting->name;

        log_activity($auditMessage, $setting, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    public function listSettings(string $orderBy = 'id', string $sortBy = 'desc', array $columns = ['*']): Collection
    {
        return $this->settingRepo->listSettings();
    }

    public function getRecords($keys)
    {
        if (is_array($keys)) {
            $records = Configuration::whereIn('option_key', $keys)->get();
        } else {
            $records = Configuration::where('option_key', $keys)->get();
        }
        if (count($records) > 0) {
            $records = $records->toArray();
            $rec = new \stdClass();
            foreach ($records as $row) {
                $rec->{$row['option_key']} = $row['option_value'];
            }
            return $rec;
        }
        return NULL;
    }

    public function updateEnvKeys($keys)
    {
        DotenvEditor::setKeys($keys);
        DotenvEditor::save();

        \Artisan::call("cache:clear");
        \Artisan::call("config:clear");
    }

    public function getEnv($key)
    {
        if (DotenvEditor::keyExists($key)) {
            return DotenvEditor::getValue($key);
        }
        return NULL;
    }
}
