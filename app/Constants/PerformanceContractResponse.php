<?php

namespace App\Constants;

class PerformanceContractResponse
{
    public const UNAUTHORIZED = 'UNAUTHORIZED';
    public const NOTFOUND = 'NOTFOUND';
    public const ERROR = 'ERROR';
    public const SUCCESS = 'SUCCESS';

    public $types = [self::UNAUTHORIZED, self::NOTFOUND, self::ERROR, self::SUCCESS];

    public const CONTRACT_ALREADY_SIGNED = [
        "MESSAGE" => "Performance Contract Already Signed",
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];

    public const CONTRACT_LD_BY_SUPERVISOR = [
        "MESSAGE" => "Development Plan can only be added by the supervisor",
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];

    public const CONTRACT_LD_BY_EMPLOYEES = [
        "MESSAGE" => "Development Plan can only be added by the employee",
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];
       /**
     * Review Statuses
     */
    public const REVIEW_STATUSES =
    [
        'NOT STARTED' => 'NOT STARTED', // EMPLOYEE DIDN'T START CAPTURING HIS/HER PERFORMANCE CONTRACT.
        'PENDING' => 'PENDING', // Pending Employee. Contract not yet signed/submitted.
        'SIGNED' => 'SIGNED', // Signed by Employee
        'SUBMITTED' => 'SUBMITTED', // Submitted by employee to Reviewer.
        'ACCEPTED' => 'ACCEPTED', // Supervisor accepted.
    ];




























    
    public const DEFAULT_SUCCESS = [
        "MESSAGE" => 'Operation successful',
        "STATUS_CODE" => 200,
        "RESPONSE_TYPE" => self::SUCCESS,
    ];
     
    public const DEFAULT_SUCCESS_CREATE = [
        "MESSAGE" => "Record successfully added!",
        "STATUS_CODE" => 200,
        "RESPONSE_TYPE" => self::SUCCESS,
    ];
  
    public const DEFAULT_SUCCESS_UPDATE = [
        "MESSAGE" => "Record successfully updated!",
        "STATUS_CODE" => 200,
        "RESPONSE_TYPE" => self::SUCCESS,
    ];
    public const DEFAULT_SUCCESS_DELETE = [
        "MESSAGE" => "Record successfully deleted!",
        "STATUS_CODE" => 200,
        "RESPONSE_TYPE" => self::SUCCESS,
    ];
    public const DEFAULT_SUCCESS_UPLOAD = [
        "MESSAGE" => "Record successfully uploaded!",
        "STATUS_CODE" => 200,
        "RESPONSE_TYPE" => self::SUCCESS,
    ];
    public const DEFAULT_SUCCESS_PASSWORD_RESET = [
        "MESSAGE" => "Password successfully reset!",
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];
    public const DEFAULT_ERROR = [
        "MESSAGE" =>    'Error! An error occurred while processing request.',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];
  
    public const DEFAULT_DUPLICATE_ERROR = [
        "MESSAGE" =>  'Error! Record exists.',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];
 
    public const NO_ELEMENT_DETAILS = [
        "MESSAGE" =>    'This element can\'t have details.',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];
  
    public const DEFAULT_ERR_CREATE = [
        "MESSAGE" =>  'Error! Unable to save record.',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];
    
    public const DEFAULT_ERR_UPDATE = [
        "MESSAGE" =>  'Error! Unable to update record.',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];
    
    public const DEFAULT_ERR_DELETE = [
        "MESSAGE" =>  'Error! Unable to delete record',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];
    
    public const DEFAULT_ERR_UPLOAD = [
        "MESSAGE" =>  'Error! Unable to upload record',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];
    
    public const DEFAULT_ERR_PASSWORD_RESET = [
        "MESSAGE" =>  'Error! Unable to reset password.',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];

    public const DEFAULT_NOT_AUTHORIZED = [
        "MESSAGE" =>  'Error! You are not authorized to perform this action.',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];
    public const DEFAULT_SUCCESS_SEARCH_FOUND = [
        "MESSAGE" =>      'Search results found for the given request',
        "STATUS_CODE" => 200,
        "RESPONSE_TYPE" => self::SUCCESS,
    ];
    public const DEFAULT_CONTRACT_SUCCESSFULLY_SIGNED = [
        "MESSAGE" =>  'CONTRACT SUCCESSFULLY SIGNED',
        "STATUS_CODE" => 200,
        "RESPONSE_TYPE" => self::SUCCESS,
    ];
    
    public const DEFAULT_CONTRACT_SUCCESSFULLY_APPROVED = [
        "MESSAGE" =>  'CONTRACT SUCCESSFULLY APPROVED',
        "STATUS_CODE" => 200,
        "RESPONSE_TYPE" => self::SUCCESS,
        "REDIRECT" => true,
    ];
    public const DEFAULT_SEARCH_NOT_FOUND = [
        "MESSAGE" =>  'No search results found for the given request!',
        "STATUS_CODE" => 421,
        "RESPONSE_TYPE" => self::SUCCESS,
    ];
    
    public const DEFAULT_SUCCESS_TRANSACTION_ROLLBACK_RESET = [
        "MESSAGE" => 'Transaction successfully rolled back.',
        "STATUS_CODE" => 200,
        "RESPONSE_TYPE" => self::SUCCESS,
    ];

    public const DEFAULT_SUCCESS_REQUEST_SUBMIT = [
        "MESSAGE" => 'Request successfully submitted',
        "STATUS_CODE" => 200,
        "RESPONSE_TYPE" => self::SUCCESS,
    ];
    public const DEFAULT_SUCCESS_REQUEST_APPROVED = [
        "MESSAGE" => 'Request successfully Approved',
        "STATUS_CODE" => 200,
        "RESPONSE_TYPE" => self::SUCCESS,
        "REDIRECT" => true,
    ];

    public const DEFAULT_SUCCESS_REQUEST_REJECTED = [
        "MESSAGE" => 'Request successfully Rejected',
        "STATUS_CODE" => 200,
        "RESPONSE_TYPE" => self::SUCCESS,
        "REDIRECT" => true,
    ];
     
    public const DEFAULT_CANNOT_DELETE = [
        "MESSAGE" => 'This record can\'t be deleted.',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];
    
    public const DEFAULT_EMPLOYEE_NOT_FOUND = [
        "MESSAGE" => 'Employee not found',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];

    public const DEFAULT_REVIEWER_NOT_FOUND = [
        "MESSAGE" => 'Performance contract reviewer not found',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];

    public const DEFAULT_CONTRACT_REQUEST_NOT_FOUND = [
        "MESSAGE" => 'Performance contract request not found or request already approved',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
        "REDIRECT" => true,
    ];

    public const DEFAULT_PERFORMANCE_APPRAISAL_NOT_FOUND = [
        "MESSAGE" => 'Contract not found',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];

    public const DEFAULT_PERFORMANCE_EMPLOYEE_NOT_FOUND = [
        "MESSAGE" => 'Employee not found, kindly update your employee email.',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];

    public const DEFAULT_PERFORMANCE_CONTRACT_NOT_FOUND = [
        "MESSAGE" => 'Performance contract not found',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];

    public const DEFAULT_PERFORMANCE_CONTRACT_REVIEW_NOT_AVAILABLE = [
        "MESSAGE" => 'Appraisal reviews not available for this contract',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];

    public const DEFAULT_PERFORMANCE_CONTRACT_REVIEW_NOT_ACTIVE = [
        "MESSAGE" => ' review not active',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];

    public const DEFAULT_PERFORMANCE_REVIEWER_NOT_FOUND = [
        "MESSAGE" => 'Reviewer information not found',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];
    public const DEFAULT_CONTRACT_ALREADY_SIGNED = [
        "MESSAGE" => 'Performance contract already signed',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];
    public const DEFAULT_CONTRACT_ALREADY_ACCEPTED = [
        "MESSAGE" => 'Performance contract already accepted',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];

    public const DEFAULT_INCOMPLETE_CONTRACT_TARGET = [
        "MESSAGE" => 'Incomplete contract target.',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];
    public const DEFAULT_CONTRACT_TARGET_EXCEEDED = [
        "MESSAGE" => 'contract target exceeded',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];
    public const DEFAULT_CONTRACT_NOT_FOUND = [
        "MESSAGE" => 'Appraisal / Contract not found',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];

    public const DEFAULT_CAN_NOT_EDIT_SUP_BONUS = [
        "MESSAGE" => 'Sorry, you can not edit or create supervisor bonus',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];

    public const DEFAULT_CONTRACT_DATE_ENDED = [
        "MESSAGE" => 'Sorry, current date not in the contract date range',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];

    public const DEFAULT_PERFORMANCE_CONTRACT_NOT_ACCEPTED = [
        "MESSAGE" => 'Sorry Performance Contract not accepted',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];

    public const DEFAULT_REQUEST_DETAIL_NOT_FOUND = [
        "MESSAGE" => 'Approval request not found, contact support for help',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];

    public const DEFAULT_NOT_IMPLEMENTOR = [
        "MESSAGE" => 'Sorry, you are not the implementor for the this request.',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];
    public const SCORE_NOT_NUMERIC = [
        "MESSAGE" => 'Score is not numeric',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];

    public const DEFAULT_REQUEST_NOT_FOUND = [
        "MESSAGE" => 'Sorry, request not available',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];

    public const DEFAULT_NO_COMPANY = [
        "MESSAGE" => 'Employee not found in current company',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];

    public const DEFAULT_NO_COMPANY_APPRAISAL = [
        "MESSAGE" => 'Current company does not have active performance contract',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];

    public const DEFAULT_WIEGHT_EXCEEDED = [
        "MESSAGE" => 'Weight for this section has exceeded',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];

    public const DEFAULT_CONTRACT_STAGE_NOT_FOUND = [
        "MESSAGE" => 'Can not find contract stage',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];

    public const ERR_MAX_OBJECTIVE_SCORE_EXCEEDED = [
        "MESSAGE" => 'score more than the kpi weight',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];
     
    public const REVIEW_ALREADY_ACCEPTED = [
        "MESSAGE" => 'review already accepted',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];

    public const DEFAULT_ERR_UNAUTHORIZED_UPDATE = [
        "MESSAGE" =>  'Error! you can not review this contract',
        "STATUS_CODE" => 404,
        "RESPONSE_TYPE" => self::ERROR,
    ];




}
