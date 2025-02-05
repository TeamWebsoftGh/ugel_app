<?php

namespace App\Constants;

abstract class ResponseMessage {
    const DEFAULT_SUCCESS = 'Operation successful';
    const DEFAULT_SUCCESS_CREATE = 'Record successfully added!';
    const DEFAULT_SUCCESS_UPDATE = 'Record successfully updated!';
    const DEFAULT_SUCCESS_DELETE = 'Record successfully deleted!';
    const DEFAULT_SUCCESS_UPLOAD = 'Record successfully uploaded!';
    const DEFAULT_SUCCESS_PASSWORD_RESET = 'Password successfully reset!';

    const DEFAULT_ERROR = 'Error! An error occurred while processing request.';
    const DEFAULT_CORE_BANKING_ERROR = 'Error! Invalid response from core banking.';
    const DEFAULT_DUPLICATE_ERROR = 'Error! Record exists.';
    const NO_ELEMENT_DETAILS = 'This element can\'t have details.';
    const DEFAULT_ERR_CREATE = 'Error! Unable to save record.';
    const DEFAULT_ERR_UPDATE = 'Error! Unable to update record.';
    const DEFAULT_ERR_DELETE = 'Error! Unable to delete record';
    const DEFAULT_ERR_UPLOAD = 'Error! Unable to upload record';
    const DEFAULT_ERR_PASSWORD_RESET = 'Error! Unable to reset password.';
    const DEFAULT_NOT_AUTHORIZED = 'Error! You are not authorized to perform this action.';
    const NO_RE = 'Error! You are not authorized to perform this action.';

    const DEFAULT_SUCCESS_SEARCH_FOUND = 'Search results found for the given request';
    const DEFAULT_SUCCESS_SEARCH_NOT_FOUND = 'No search results found for the given request!';
    const DEFAULT_SUCCESS_TRANSACTION_ROLLBACK_RESET = 'Transaction successfully rolled back.';
    const DEFAULT_CANNOT_DELETE = 'This record can\'t be deleted.';
}
