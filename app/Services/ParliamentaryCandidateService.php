<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Election\ParliamentaryCandidate;
use App\Traits\UploadableTrait;
use App\Repositories\Interfaces\IParliamentaryCandidateRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\IParliamentaryCandidateService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ParliamentaryCandidateService extends ServiceBase implements IParliamentaryCandidateService
{
    use UploadableTrait;

    private IParliamentaryCandidateRepository $parliamentaryCandidateRepo;

    public function __construct(IParliamentaryCandidateRepository $parliamentaryCandidateRepository)
    {
        parent::__construct();
        $this->parliamentaryCandidateRepo = $parliamentaryCandidateRepository;
    }

    public function listParliamentaryCandidates(array $filter = null, string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        return $this->parliamentaryCandidateRepo->listParliamentaryCandidates($filter, $order, $sort);
    }

    public function createParliamentaryCandidate(array $params)
    {
        $parliamentaryCandidate = null;

        try {
            $pc = ParliamentaryCandidate::firstWhere(['election_id' => $params['election_id'], 'political_party_id' => $params['political_party_id'], 'type' => $params['type']]);
            if(isset($pc))
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "Candidate already added for selected party.";

                return $this->response;
            }
            $parliamentaryCandidate = $this->parliamentaryCandidateRepo->createParliamentaryCandidate($params);
        } catch (\Exception $e) {
            log_error(format_exception($e), new ParliamentaryCandidate(), 'create-candidate-failed');
        }

        if (!$parliamentaryCandidate)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_CREATE;

            return $this->response;
        }

        log_activity(ResponseMessage::DEFAULT_SUCCESS_CREATE, $parliamentaryCandidate, 'create-candidate-successful');
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = ResponseMessage::DEFAULT_SUCCESS_CREATE;
        $this->response->data = $parliamentaryCandidate;

        return $this->response;
    }

    public function findParliamentaryCandidateById(int $id): ParliamentaryCandidate
    {
        return $this->parliamentaryCandidateRepo->findParliamentaryCandidateById($id);
    }

    public function updateParliamentaryCandidate(array $params, ParliamentaryCandidate $parliamentaryCandidate)
    {
        $result = false;

        try {
            $pc = ParliamentaryCandidate::firstWhere(['election_id' => $params['election_id'], 'political_party_id' => $params['political_party_id'], 'type' => $parliamentaryCandidate->type]);
            if(isset($pc) && $pc->id != $parliamentaryCandidate->id)
            {
                $this->response->status = ResponseType::ERROR;
                $this->response->message = "Candidate already added for selected party.";

                return $this->response;
            }
            $result = $this->parliamentaryCandidateRepo->updateParliamentaryCandidate($params, $parliamentaryCandidate);
        } catch (\Exception $e) {
            log_error(format_exception($e), $parliamentaryCandidate, 'update-candidate-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_UPDATE;

            return $this->response;
        }

        log_activity('You have successfully updated Candidate ' . $parliamentaryCandidate->name, $parliamentaryCandidate, 'update-parliamentary-candidate-successful');
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = 'You have successfully updated Candidate ' . $parliamentaryCandidate->name;
        $this->response->data = $parliamentaryCandidate;

        return $this->response;
    }

    public function deleteParliamentaryCandidate(ParliamentaryCandidate $parliamentaryCandidate)
    {
        $result = false;
        try{
            $result = $this->parliamentaryCandidateRepo->deleteParliamentaryCandidate($parliamentaryCandidate);
        }catch (\Exception $ex)
        {
            log_error(format_exception($ex), $parliamentaryCandidate, 'delete-candidate-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERR_DELETE;

            return $this->response;
        }

        log_activity('You have successfully deleted Candidate ' . $parliamentaryCandidate->name, $parliamentaryCandidate, 'delete-candidate-successful');
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = 'You have successfully deleted Candidate ' . $parliamentaryCandidate->name;

        return $this->response;
    }
}
