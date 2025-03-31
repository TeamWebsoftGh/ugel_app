<?php

namespace App\Services\Auth;

use App\Constants\Constants;
use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Mail\Admin\AccountCreatedMail;
use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Auth\UserOtp;
use App\Repositories\Auth\Interfaces\IUserRepository;
use App\Repositories\Auth\UserRepository;
use App\Services\Auth\Interfaces\IUserService;
use App\Services\Helpers\Response;
use App\Services\ServiceBase;
use App\Traits\SmsTrait;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService extends ServiceBase implements IUserService
{
    use SmsTrait;
    private IUserRepository $userRepo;

    /**
     * UserService constructor.
     *
     * @param IUserRepository $user
     */
    public function __construct(IUserRepository $user)
    {
        parent::__construct();
        $this->userRepo = $user;
    }

    /**
     * List all the Application Users
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listUsers(string $order = 'id', string $sort = 'desc'): Collection
    {
        return $this->userRepo->listUsers($order, $sort);
    }

    /**
     * Create the User
     *
     * @param array $data
     *
     * @return Response
     */
    public function createUser(array $data)
    {
        //Declaration
        $user = null;
        $password = Str::password(14);

        //Process Request
        try {
            if (!isset($data['password']))
                $data['password'] = $password;
            $user = $this->userRepo->createUser($data);
            if (isset($data['role']))
                $user->syncRoles($data['role']);


            if (settings("send_mail_new_account", 1) && !empty($data['email'])){
                $user->password = $data['password'];
                send_mail(AccountCreatedMail::class, $user, $user);
            }

        } catch (\Exception $e) {
            log_error(format_exception($e), new User(), 'create-user-failed');
        }

        //Check if User was created successfully
        if (!$user)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-user-successful';
        $auditMessage = 'A new user account has been created successfully.';

        log_activity($auditMessage, $user, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $user;

        return $this->response;
    }

    /**
     * Find the Application user by id
     *
     * @param int $id
     *
     * @return User
     */
    public function findUserById(int $id): User
    {
        return $this->userRepo->findUserById($id);
    }

    /**
     * Update User
     *
     * @param array $data
     *
     * @param $user
     * @return Response
     */
    public function updateUser(array $data, User $user)
    {
        //Declaration
        $res = false;

        //Process Request
        try {
//            if (isset($data['username'])){
//                $data['username'] = Str::slug($data['username']);
//            }
            $res = $this->userRepo->updateUser($data, $user);

            $userRepo = new UserRepository($user);
            if (isset($data['role']))
                $user->syncRoles(array($data['role']));

            //Add Permissions
            if (isset($data['permissions']))
                $userRepo->syncPermissions($data['permissions']);

        } catch (\Exception $e) {
            log_error(format_exception($e), $user, "update-user-failed");
        }

        //Check if User was updated successfully
        if (!$res)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;
            return $this->response;
        }

        //Audit Trail
        $logAction = "update-user-successful";
        $auditMessage = 'User profile updated successfully.';

        log_activity($auditMessage, $user, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $user;

        return $this->response;
    }



    /**
     * @param User $user
     * @return Response
     */
    public function resetPassword(User $user)
    {
        //Declaration
        $res = false;
        $password = Str::password(14);

        if($user->email == null)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = "User must have an email for password reset.";
            return $this->response;
        }

        //Process Request
        try {
            $res = $this->userRepo->updateUser([
                'password' => $password,
                'ask_password_reset' => 1,
                'last_password_reset' => Carbon::now()
            ], $user);

            if (Constants::SEND_PASSWORD_RESET_MAIL){
                $user->password = $password;
                send_mail(AccountCreatedMail::class, $user, $user);
            }
        } catch (\Exception $e) {
            log_error(format_exception($e), $user, "reset-user-password-failed");
        }

        //Check if User was updated successfully
        if (!$res)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;
            return $this->response;
        }

        //Audit Trail
        $logAction = "reset-user-password-successful";
        $auditMessage = "Successfully changed password for " .$user->fullname;

        log_activity($auditMessage, $user, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = "You have successfully reset this user's password to <b>".$password. "</b>";

        return $this->response;
    }

    /**
     * @return Response
     */
    public function bulkPasswordReset(): Response
    {
        $users =  $this->listUsers()->where('is_active', '==', 1);
        $total = count($users);
        $valid = 0;
        foreach ($users as $user)
        {
            if(isset($user) && $user->last_login_date == null && isset($user->email))
            {
                try {
                    $password = Str::password(14);

                    $res = $this->userRepo->updateUser([
                        'password' => $password,
                        'ask_password_reset' => 1,
                        'last_password_reset' => Carbon::now()
                    ], $user);
                    $this->updateFinanceUser($user->email, ['password' => Hash::make($password)]);
                    $user->password = $password;
                    send_mail(AccountCreatedMail::class, $user, $user);
                    $valid++;

                }catch (\Exception $e) {
                    log_error(format_exception($e), $user, "reset-user-password-failed");
                }
            }
        }

        //Audit Trail
        $logAction = "reset-user-password-successful";
        $auditMessage = $valid." out of " . $total." successful.";

        log_activity($auditMessage, new User(), $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    public function changePassword(array $params, User $user)
    {
        //Declaration
        $res = false;

        //Process Request
        try {
            if (!(Hash::check($params['current-password'], $user->password)))
            {
                //The passwords match
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

            $res = $this->userRepo->updateUser([
                'password' => $params['new-password'],
                'ask_password_reset' => 0,
                'last_password_reset' => Carbon::now()
            ], $user);
            $this->updateFinanceUser($user->email, ['password' => Hash::make($params['new-password'])]);
        } catch (\Exception $e) {
            log_error(format_exception($e), $user, "change-user-password-failed");
        }

        //Check if User was updated successfully
        if (!$res)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;
            return $this->response;
        }

        //Audit Trail
        $logAction = "change-user-password-successful";
        $auditMessage = "Password successfully changed for " . $user->name;

        log_activity($auditMessage, $user, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @return Collection
     */
    public function listRoles(): Collection
    {
        return $this->userRepo->listRoles()->skip(1);
    }


    /**
     * @param bool $status
     * @param User $user
     * @return Response
     */
    public function changeStatus(bool $status, User $user)
    {
        //Declaration
        $response = false;

        //Process Request
        try {
            $response = $this->userRepo->updateUser(['is_active' => $status], $user);
            $this->updateFinanceUser($user->email, ['enabled' => $status]);

        } catch (\Exception $e) {
            log_error(format_exception($e), $user, "change-status-failed");
        }

        if (!$response)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        $changeAction = $status?"activated":"deactivated";

        //Audit Trail
        $logAction = "change-status-successful";
        $auditMessage = 'You have successfully '.$changeAction.' a user with name: '.$user->name;

        log_activity($auditMessage, $user, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param User $user
     * @return Response
     */
    public function deleteUser(User $user)
    {
        //Declaration
        $res = false;

        try{
            if($user->id == user()->id){
                //Current password and new password are same
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "You cannot delete your account.";
                return $this->response;
            }
            $res = $this->userRepo->delete($user->id);
        }catch (\Exception $ex){
            log_error(format_exception($ex), $user, "delete-user");
        }

        if (!$res)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        //Audit User
        $logAction = "delete-user-successful";
        $auditMessage = 'You have successfully deleted a user with name '.$user->name;

        log_activity($auditMessage, $user, $logAction);

        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * Find the Client by id
     *
     * @param User $user
     * @param null $phone_number
     * @return Response
     * @throws GuzzleException
     */
    public function sendOtp(User $user, $phone_number=null)
    {
        try {
            $userOtp = UserOtp::where('user_id', $user->id)->latest()->first();
            $now = now();
            $opt_expired_minutes = !empty(settings('opt_expired_minutes'))?settings('opt_expired_minutes'):10;
            if ($userOtp && $now->isBefore($userOtp->expire_at)) {
                $message = 'Your OTP is '.$userOtp->otp;
            } else {
                $otp = generate_otp();
                UserOtp::create([
                    'user_id' => $user->id,
                    'otp' => $otp,
                    'medium' => "sms",
                    'expire_at' => $now->addMinutes($opt_expired_minutes)
                ]);
                $message = 'Your OTP is '.$otp;
            }

            $phone_number = $phone_number ?? $user->phone_number;

            $this->sendSms($phone_number, $message);

            // Audit Trail
            $auditMessage = "OTP sent to ******".Str::substr($phone_number, -4);
            log_activity($auditMessage, $user, 'send-otp-successful');

            $this->response->status = ResponseType::SUCCESS;
            $this->response->message = $auditMessage;
        } catch (\Exception $ex) {
            log_error(format_exception($ex), $user, 'send-otp-failed');

            $this->response->status = ResponseType::ERROR;
            $this->response->message = "OTP not sent";
        }

        return $this->response;
    }

    /**
     * Verify OTP
     *
     * @param User $user
     * @param string $otp
     * @return Response
     */
    public function verifyOtp(User $user, string $otp)
    {
        /* User Does not Have Any Existing OTP */
        try {
            /* Validation Logic */
            $userOtp   = UserOtp::where('user_id', $user->id)->where('otp', $otp)->first();

            $now = now();
            if ($userOtp && $now->isBefore($userOtp->expire_at))
            {
                $userOtp->update([
                    'expire_at' => now()
                ]);
                $this->response->status = ResponseType::SUCCESS;
                $this->response->message = "OTP verified.";
                return $this->response;
            }

            $this->response->status = ResponseType::ERROR;
            $this->response->message = "Invalid Otp or OTP has expired.";

            return $this->response;
        }catch (\Exception $ex){
            log_error(format_exception($ex), $user, 'verify-code-failed');
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }
    }

    /**
     * @param array $request
     * @return array
     */
    public function getCreateUser(array $request){
        $user = null;
        $users = $this->listUsers();

        if (isset($request["userId"]) && $request["userId"] != null)
        {
            $user = $users->firstWhere("id", "==", $request["userId"]);
        }

        return [
            'gender' => Constants::GENDER,
            'users' => $users,
            'user' => $user??$this->listUsers()->first()??new User(),
            'roles' => Role::all(),
            'permissions' => Permission::all(),
        ] ;
    }
}
