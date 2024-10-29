<?php

namespace App\Services;

use App\Constants\ResponseMessage;
use App\Constants\ResponseType;
use App\Models\Settings\Currency;
use App\Traits\UploadableTrait;
use App\Repositories\Interfaces\ICurrencyRepository;
use App\Services\Helpers\Response;
use App\Services\Interfaces\ICurrencyService;
use Illuminate\Support\Collection;

class CurrencyService extends ServiceBase implements ICurrencyService
{
    use UploadableTrait;

    private $currencyRepo;

    /**
     * FacilitatorService constructor.
     *
     * @param ICurrencyRepository $currencyRepository
     */
    public function __construct(ICurrencyRepository $currencyRepository){
        parent::__construct();
        $this->currencyRepo = $currencyRepository;
    }

    /**
     * List all the Contact Us Messages
     *
     * @param string $order
     * @param string $sort
     *
     * @return Collection
     */
    public function listCurrencies(string $order = 'updated_at', string $sort = 'desc'): Collection
    {
        return $this->currencyRepo->listCurrencies($order, $sort);
    }

    /**
     * Create the Contact Us
     *
     * @param array $params
     * @return Response
     */
    public function createCurrency(array $params)
    {
        //Declaration
        $currency = null;

        //Process Request
        try {
            $currency = $this->currencyRepo->createCurrency($params);

        } catch (\Exception $e) {
            log_error(format_exception($e), new Currency(), 'create-currency-failed');
        }

        //Check if Currency was created successfully
        if (!$currency)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'create-currency-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_CREATE;

        log_activity($auditMessage, $currency, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;
        $this->response->data = $currency;

        return $this->response;
    }

    /**
     * Find the Currency form by id
     *
     * @param int $id
     *
     * @return Currency
     */
    public function findCurrencyById(int $id): Currency
    {
        return $this->currencyRepo->findCurrencyById($id);
    }

    /**
     * Update Currency
     *
     * @param array $params
     * @param Currency $currency
     * @return Response
     */
    public function updateCurrency(array $params, Currency $currency)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            if($params['is_default'])
            {
                Currency::where('is_default', 1)->update(['is_default' => 0]);
            }
            $result = $this->currencyRepo->updateCurrency($params, $currency);

        } catch (\Exception $e) {
            log_error(format_exception($e), $currency, 'update-currency-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'update-currency-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_UPDATE;

        log_activity($auditMessage, $currency, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * Update Currency
     *
     * @param bool $status
     * @param Currency $currency
     * @return Response
     */
    public function changeStatus(bool $status, Currency $currency)
    {
        //Declaration
        $result = false;

        //Process Request
        try {
            $result = $this->currencyRepo->updateCurrency(['is_active' => $status], $currency);

        } catch (\Exception $e) {
            log_error(format_exception($e), $currency, 'update-currency-failed');
        }

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        $changeAction = $status?"activated":"deactivated";

        //Audit Trail
        $logAction = 'update-currency-successful';
        $auditMessage = 'You have successfully '.$changeAction.' currency with name: '.$currency->currency;

        log_activity($auditMessage, $currency, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }

    /**
     * @param Currency $currency
     * @return Response
     */
    public function deleteCurrency(Currency $currency)
    {
        //Declaration
        $result = false;

        $result = $this->currencyRepo->delete($currency->id);

        if (!$result)
        {
            $this->response->status = ResponseType::ERROR;
            $this->response->message = ResponseMessage::DEFAULT_ERROR;

            return $this->response;
        }

        //Audit Trail
        $logAction = 'delete-currency-successful';
        $auditMessage = ResponseMessage::DEFAULT_SUCCESS_DELETE;

        log_activity($auditMessage, $currency, $logAction);
        $this->response->status = ResponseType::SUCCESS;
        $this->response->message = $auditMessage;

        return $this->response;
    }
}
