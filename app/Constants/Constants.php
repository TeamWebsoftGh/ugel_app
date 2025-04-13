<?php


namespace App\Constants;

class Constants
{
    /**
     * ---------------------------------------------------------------
     *  Printer Settings
     * ---------------------------------------------------------------
     */
    /**
     * 512Kb data to be read at a time
     */
    public const PRINT_BUFFER_SIZE = 1024 * 512;

    /**
     * Default date format
     */
    const ITEMS_PER_PAGE = 25;
    const NOTIFICATIONS_MEDIUM = ['mail'];
    const BONUS_AMOUNT = 10;
    const PAYSLIP_COLOR = "#153e6f";
    const DEVELOPER = "Websoft";
    const DEFAULT_CSS = "/public/css/style.red.css";
    const COMPANY_LOGO  = "/logo/logo.png";
    public const DELETE_PROMPT = 'Are you sure you want to delete this record?';
    public const REMOVE_PROMPT = 'Are you sure you want to remove this element?';
    public const SAVE_PROMPT = 'Save this record?';
    public const ROLLBACK_SCHEDULE_PROMPT = 'Are you sure you want to rollback this Schedule?';

    public const HOME = '/customer';
    public const ADMIN = '/admin';


    /**
     * { item_description }
     * Messaging
     * -------------------------------------------------------------------
     */
    /**
     *  APPROVAL MESSAGES
     */
    public const  DEFAULT_REQUEST_STATUS_MSG = [
        'REJECTED' => 'Request has successfully been rejected.',
        'APPROVED' => 'Request has successfully been approved.',
        'DECLINED' => 'Request has successfully been declined.',
        'RESOLVED' => 'Request has successfully been resolved.',
        'ESCALATED' => 'Request has successfully been escalated.',
    ] ;

    public const GENDER = [
        'Male' => 'Male',
        'Female' => 'Female',
        'others' => 'Others'
    ];

    public const MOMO_TYPES = [
        'MTN' => 'MTN MOBILE MONEY',
        'VODAFONE' => 'Telecel CASH',
        'AIRTELTIGO' => 'AT CASH'
    ];

    public const PAYMENT_TYPES = [
        'manual' => 'Manual',
        'momo' => 'MOBILE MONEY',
        'visa' => 'Visa Card',
    ];


    public const DURATION_TYPES = [
        // 'hours' => 'Hours',
        'days' => 'Days',
        'weeks' => 'Weeks',
        'months' => 'Months',
        'years' => 'Years'
    ];


    public const SERVICE_CATEGORIES = [
        'ACADEMIC' => 'ACADEMIC',
        'BUSINESS' => 'BUSINESS',
        'OTHERS' => 'OTHERS'
    ];

    /**
     * Marital statuses
     */
    public const MARITAL_STATUS = [
        'single' => 'Single',
        'married' => 'Married',
        'divorced' => 'Divorced',
        'separated' => 'Separated',
        'widowed' => 'Widowed',
        'others' => 'Others'
    ];

    /**
     * Marital statuses
     */
    public const TITLES = [
        '001' => 'MR',
        '002' => 'MRS',
        '003' => 'DR',
        '004' => 'PROF',
        '005' => 'REV',
        '006' => 'MADAM',
        '007' => 'MISS',
        '008' => 'OTHERS',
        '009' => 'SGT',
    ];

    public const ID_TYPES = [
        '100' => 'PASSPORT',
        '200' => 'STUDENT ID',
        '300' => 'NATIONAL ID',
        '400' => 'NHIS',
        '500' => 'DRIVERS LICENSE',
        '600' => 'VOTERS ID',
    ];

    public const ACCOUNT_CHANNEL = [
        'mtn' => 'MTN',
        'vodafone' => 'Telecel',
        'airtel' => 'AirtelTigo',
    ];

    public const RELATIONSHIP_TYPES = [
        'sibling' => 'Sibling',
        'parent' => 'Parent',
        'spouse' => 'Spouse',
        'child' => 'Child',
        'uncle' => 'Uncle',
        'aunt' => 'Aunt',
        'in-law' => 'In Law',
        'cousin' => 'Cousin',
        'other' => 'Other',
    ];

    public const TRAVEL_MODES = [
        'plane' => 'By Plane',
        'bus' => 'By Bus',
        'taxi' => 'By Taxi',
        'rented-car' => 'By Rental Car',
        'train' => 'By Train',
        'other' => 'Other',
    ];

    public const STATUSES_YN = [
        '1' => 'Yes',
        '0' => 'No',
    ];

    public const THEMES = [
        'style.default.css' => 'Default',
        'style.red.css' => 'Red',
        'style.green.css' => 'Green',
    ];

    public const POSITION_TYPES = [
        'general-manager' => 'General Manager',
        'hod' => 'Head of Department',
        'hr-manager' => 'HR Manager',
        'finance-manager' => 'Finance Manager',
        'unit-head' => 'Unit Head',
        'supervisor' => 'Supervisor',
        'assignees' => 'Assignees',
        'employee' => 'Employee',
        'team' => 'Team',
        'cfo' => 'CFO',
        'ceo' => 'CEO'
    ];

    public const CONTACT_TYPES = [
        'emergency-contact' => 'Emergency Contact',
        'beneficiary' => 'Beneficiary',
        'next-of-kin' => 'Next of Kin',
        'referee' => 'Referee',
        'guarantor' => 'Guarantor',
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
        'APPROVED' => 'APPROVED',
        'REJECTED' => 'REJECTED',
    ];

       /**
     * Appraisal Process Stages
     */
    const CLIENT_TYPES = [
        'individual' => 'Individual',
        'corporate' => 'Corporate',
    ];

    public const DATE_FORMATS = [
        'd-m-Y' => 'dd-mm-yyyy(23-05-2021)',
        'Y-m-d' => 'yyyy-mm-dd(2021-05-23)',
        'm/d/Y' => 'mm/dd/yyyy(05/23/2021)',
        'Y/m/d' => 'yyyy/mm/dd(2021/05/23)',
        'Y-M-d' => 'yyyy-MM-dd(2021-May-23)',
        'M-d-Y' => 'MM-dd-yyyy(May-23-2021)',
        'd-M-Y' => 'dd-MM-yyyy(23-May-2021)',
    ];
}
