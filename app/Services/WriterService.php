<?php

namespace App\Services;

use App\Constants\Constants;
use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Events\TerminationEvent;
use App\Mail\Writer\AccountConfirmedMail;
use App\Mail\Writer\AccountCreatedMail;
use App\Mail\Writer\PasswordResetMail;
use App\Models\Writer;
use App\Repositories\Interfaces\IOffenseRepository;
use App\Repositories\Interfaces\IEmployeeRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IWriterService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class WriterService extends ServiceBase implements IWriterService
{
    private $writerRepo;
    private $serviceTypeRepo;

    /**
     * WriterService constructor.
     *
     * @param IEmployeeRepository $writer
     * @param IOffenseRepository $serviceType
     */
    public function __construct(IEmployeeRepository $writer, IOffenseRepository $serviceType){
        parent::__construct();
        $this->writerRepo = $writer;
        $this->serviceTypeRepo = $serviceType;
    }

    /**
     * List all the Application Writers
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listWriters(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->writerRepo->listWriters($order, $sort);
    }

    /**
     * List all the Available Writers
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listAvailableWriters(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->writerRepo->listWriters($order, $sort)->where('IsAvailable', '==', 1);
    }

    /**
     * Create the Writer
     *
     * @param array $data
     *
     * @return Response
     */
    public function createWriter(array $data)
    {
        //Declaration
        $writer = null;
        $password = Str::random(10);
        $msg = null;

        //Process Request
        try {
            $data['username'] = Str::slug($data['username']);

            if(isset($data['password']))
            {
                $password = "Use your password.";
                $msg = "Account created successfully.";
            }else{
                $data['password'] = $password;
            }
            $writer = $this->writerRepo->createWriter($data);

            if(isset($data['services']))
                $writer->services()->sync($data['services']);

            $writer->password = $password;
            event(new TerminationEvent($writer));

        } catch (\Exception $e) {
            log_error(format_exception($e), new Writer(), 'create-writer-failed');
        }

        //Check if Career was created successfully
        if (!$writer || $writer == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-writer-successful';
        $auditMessage = $msg ?? 'You have successfully added a new Writer: '.$writer->fullname.' with password: '.$password;

        log_activity($auditMessage, $writer, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $writer;

        return $this->response;
    }

    /**
     * Find the Application Writer by id
     *
     * @param int $id
     *
     * @return Writer
     */
    public function findWriterById(int $id): Writer
    {
        return $this->writerRepo->findWriterById($id);
    }

    /**
     * Update Writer
     *
     * @param array $data
     *
     * @param $writer
     * @return Response
     */
    public function updateWriter(array $data, Writer $writer)
    {
        //Declaration
        $result = new Response();
        $res = false;

        //Process Request
        try {
            if (isset($data['username'])){
                $data['username'] = Str::slug($data['username']);
            }
            $res = $this->writerRepo->updateWriter($data, $writer);
            $writer->services()->sync($data['writers']);
        } catch (\Exception $e) {
            log_error(format_exception($e), $writer, "update-writer-failed");
        }

        //Check if Writer was updated successfully
        if (!$res)
        {
            $result->status = ResponseType::ERROR;
            $result->message = ResponseMessage::DEFAULT_ERROR;
            return $result;
        }

        //Audit Trail
        $logAction = "update-writer-successful";
        $auditMessage = 'You have successfully updated Writer: '.$writer->fullname;

        log_activity($auditMessage, $writer, $logAction);

        $result->status = ResponseType::SUCCESS;
        $result->message = $auditMessage;
        $result->data = $writer;

        return $result;
    }

    /**
     * @param Writer $writer
     * @return Response
     */
    public function resetPassword(Writer $writer)
    {
        //Declaration
        $res = false;
        $password = Str::random(10);

        //Process Request
        try {
            $res = $this->writerRepo->updateWriter([
                'password' => $password,
                'ask_password_reset' => 1,
                'last_password_reset' => Carbon::now()
            ], $writer);

            if (Constants::SEND_PASSWORD_RESET_MAIL){
                $writer->password = $password;
                send_mail(PasswordResetMail::class, $writer, $writer);
            }
        } catch (\Exception $e) {
            log_error(format_exception($e), $writer, "reset-writer-password-failed");
        }

        //Check if Writer was updated successfully
        if (!$res)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;
            return $this->response;
        }

        //Audit Trail
        $logAction = "reset-writer-password-successful";
        $auditMessage = "Successfully changed password for " . $writer->fullname;

        log_activity($auditMessage, $writer, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = "You have successfully reset this Writer's password to <b>" . $password . "</b>";

        return $this->response;
    }

    public function changePassword(array $params, Writer $writer)
    {
        //Declaration
        $res = false;

        //Process Request
        try {
            if (!(Hash::check($params['current-password'], $writer->password))) {
                // The passwords matches
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "Your current password does not match with the password you provided.";
                return $this->response;
            }

            if(strcmp($params['current-password'],$params['new-password']) == 0){
                //Current password and new password are same
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "New Password cannot be same as your current password.";
                return $this->response;
            }

            $res = $this->writerRepo->updateWriter([
                'password' => $params['new-password'],
                'ask_password_reset' => 0,
                'last_password_reset' => Carbon::now()
            ], $writer);
        } catch (\Exception $e) {
            log_error(format_exception($e), $writer, "change-writer-password-failed");
        }

        //Check if Writer was updated successfully
        if (!$res)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;
            return $this->response;
        }

        //Audit Trail
        $logAction = "change-writer-password-successful";
        $auditMessage = "Password successfully changed for " . $writer->fullname;

        log_activity($auditMessage, $writer, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param bool $status
     * @param Writer $writer
     * @return Response
     */
    public function changeStatus(bool $status, Writer $writer)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->writerRepo->updateWriter(['status' => $status], $writer);

        } catch (\Exception $e) {
            log_error(format_exception($e), $writer, "change-writer-status-failed");
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        $changeAction = $status?"activated":"deactivated";

        //Audit Trail
        $logAction = "change-writer-status-successful";
        $auditMessage = 'You have successfully '.$changeAction.' a Writer with name: '.$writer->fullname;

        log_activity($auditMessage, $writer, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }


    public function confirmAccount(Writer $writer)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->writerRepo->updateWriter(['account_verified_at' => Carbon::now()], $writer);

            send_mail(AccountConfirmedMail::class, $writer, $writer);

        } catch (\Exception $e) {
            log_error(format_exception($e), $writer, "confirm-writer-failed");
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = "confirm-writer-successful";
        $auditMessage = 'You have successfully confirmed a Writer with name: '.$writer->fullname;

        log_activity($auditMessage, $writer, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param Writer $writer
     * @return Response
     */
    public function deleteWriter(Writer $writer)
    {
        //Declaration
        $res = false;

        try{
            $res = $this->writerRepo->delete($writer->id);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $writer, "delete-Writer");
        }

        if (!$res)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Writer
        $logAction = "delete-writer-successful";
        $auditMessage = 'You have successfully deleted a Writer with name '.$writer->fullname;

        log_activity($auditMessage, $writer, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param array $request
     * @return array
     */
    public function getCreateWriter(array $request){
        $writer = null;
        $writers = $this->listWriters();

        if (isset($request["writerId"]) && $request["writerId"] != null)
        {
            $writer = $writers->firstWhere("id", "==", $request["writerId"]);
        }

        return [
            'gender' => Constants::GENDER,
            'writers' => $writers,
            'writer' => $writer??$this->listWriters()->first()??new Writer(),
            'services' => $this->serviceTypeRepo->listServiceTypes()->where("status", "==", 1),
            'serviceArrayIds' => [],
        ] ;
    }

}
