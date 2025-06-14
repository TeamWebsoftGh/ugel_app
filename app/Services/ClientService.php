<?php

namespace App\Services;

use App\Constants\Constants;
use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Events\NewClientEvent;
use App\Models\Auth\Role;
use App\Models\Client\Client;
use App\Models\Client\ClientType;
use App\Models\Common\NumberGenerator;
use App\Repositories\Auth\Interfaces\IUserRepository;
use App\Repositories\Interfaces\IClientRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IClientService;
use App\Traits\UploadableTrait;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientService extends ServiceBase implements IClientService
{
    use UploadableTrait;

    private IClientRepository $clientRepo;
    private IUserRepository $userRepo;

    /**
     * ClientService constructor.
     *
     * @param IClientRepository $ClientRepository
     */
    public function __construct(IClientRepository $ClientRepository, IUserRepository $userRepository)
    {
        parent::__construct();
        $this->clientRepo = $ClientRepository;
        $this->userRepo = $userRepository;
    }

    /**
     * List all the Guests
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listClients(array $filter = [], string $order = 'updated_at', string $sort = 'desc')
    {
        return $this->clientRepo->listClients($filter, $order, $sort);
    }


    /**
     * Create the Guests
     *
     * @param array $params
     * @return Response
     */
    public function createClient(array $data): Response
    {
        //Declaration
        $password = Str::password(12);
        $client= null;
        $created_user=null;

        //Process Request
        try {
            $data['is_active'] = 1;
            DB::beginTransaction();
            $client = $this->clientRepo->create($data);

            if($client == null)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

                return $this->response;
            }

            if(!isset($data['password'])){
                $data['password'] = $password;
            }

            if (isset($data['photo']) && $data['photo'] instanceof UploadedFile)
            {
                $photo = $data['photo'];
                $new_user = Str::slug($data['username']);
                $data['profile_photo'] = $this->uploadPublic($photo, $new_user, "profile-photos");
            }

            $data['client_id'] = $client->id;
            if (!isset($data['client_number'])){
                $data['client_number'] = NumberGenerator::gen(Client::class);
            }
            if (!isset($data['username'])){
                $data['username'] = $data['client_number'];
            }
            $created_user = $this->userRepo->createUser($data);
            $created_user->syncRoles(array(optional(Role::firstWhere('name', 'customer'))->id));

            // Send the email verification notification
            //$created_user->sendEmailVerificationNotification();

            DB::commit();
            $created_user->password = $data['password'];
            event(new NewClientEvent($client));

        } catch (\Exception $e) {
            DB::rollback();
            log_error(format_exception($e), new Client(), 'create-client-failed');
        }

        //Audit Trail
        return $this->buildCreateResponse($client);
    }

    /**
     * Find the Client by id
     *
     * @param int $id
     *
     * @return Client
     */
    public function findClientById(int $id): Client
    {
        return $this->clientRepo->findOneOrFail($id);
    }

    /**
     * Update Guest
     *
     * @param array $data
     * @param Client $client
     * @return Response
     */
    public function updateClient(array $data, Client $client)
    {
        //Declaration
        $result = false;

        //Process Request
        try {

            DB::beginTransaction();
            $result = $this->clientRepo->update($data, $client->id);

            if(!$result)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;

                return $this->response;
            }

            if($client->clientType->category == "individual")
            {
                $user = $client->users()->first();
                $user->update($data);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            log_error(format_exception($e), $client, 'update-client-failed');
        }

        return $this->buildUpdateResponse($client, $result);
    }

    /**
     * @param Client $client
     * @return Response
     */
    public function deleteClient(Client $client)
    {
        //Declaration
        $result = new Response();

        $client->users()->delete();
        $result = $this->clientRepo->delete($client->id);

        return $this->buildDeleteResponse($result);
    }

    public function getCreateClient()
    {
        return [
            'gender' => Constants::GENDER,
            'client_types' => ClientType::where('is_active', 1)->get(['id', 'name', 'category']),
        ] ;
    }
}
