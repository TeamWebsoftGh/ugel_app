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
    const DATE_FORMAT = 'd-M-Y';

    const SEND_NEW_ACCOUNT_MAIL = true;
    const TOTAL_RATING = 10;
    const WORDS_PER_PAGE = 300;
    const ITEMS_PER_PAGE = 25;
    const TIER1 = (13.5/100);
    const SSNIT_TIER_1 = 13.5;
    const TIER2 = (5/100);
    const SSNIT_TIER_2 = 5;
    const BASIC_SALARY = 1;
    const INCOME_TAX = 1;
    const SSNIT = [1,2,3];
    const RETIREMENT_AGE = 62;
    const SEND_PASSWORD_RESET_MAIL = true;
    const NOTIFICATIONS_MEDIUM = ['mail'];
    const BONUS_AMOUNT = 10;
    const HRM = true;
    const PAYROLL = true;
    const ACCOUNT = true;
    const FINANCE_URL = '127.0.0.1:60';
    const CRM = true;
    const PAYSLIP_COLOR = "#297ff9";
    const DEVELOPER = "Websoft";
    const DEFAULT_CSS = "/public/css/style.red.css";
    const COMPANY_LOGO  = "/logo/logo.png";
    const MANAGEMENT_ID  = [1];

    public const DELETE_PROMPT = 'Are you sure you want to delete this record?';
    public const REMOVE_PROMPT = 'Are you sure you want to remove this element?';
    public const SAVE_PROMPT = 'Save this record?';
    public const ROLLBACK_SCHEDULE_PROMPT = 'Are you sure you want to rollback this Schedule?';

    public const HOME = '/customer';
    public const ADMIN = '/admin';

    public const CANSETFOROTHERS = true;


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
        'VODAFONE' => 'VODAFONE CASH',
        'AIRTELTIGO' => 'AIRTELTIGO CASH'
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

    public const PAYROLL_IMPORT_TYPES = [
        'allowance' => 'Allowance',
        'deduction' => 'Deduction',
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

    public const SECTORS = [
        '01' => 'MISCELLANEOUS',
        '10' => 'AGRICULTURE AND FORESTRY',
        '20' => 'MINING AND QUARRING',
        '30' => 'MANUFACTURING',
        '40' => 'CONSTRUCTION',
        '50' => 'ELECTRICITY GAS & WATER',
        '60' => 'COMMERCE & FINANCE',
        '70' => 'TRANSPORT STORAGE & COMMUNICATION',
        '80' => 'SERVICES',
    ];


    public const ACCOUNT_CHANNEL = [
        'mtn' => 'MTN',
        'vodafone' => 'Telecel',
        'airtel' => 'AirtelTigo',
    ];

    public const TRANSFER_TYPE = [
        'bill' => 'Bill Payment',
        'internal' => 'Internal Fund Transfer',
        'external' => 'External Fund Transfer',
        'momo' => 'Mobile Money',
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


    public const STAFF_TYPES = [
        '1' => 'Local',
        '0' => 'Expat',
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
        'employee' => 'Employee',
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

    public const CB_REPORT_TYPES = [
        '14515' => 'Consumer Product',
        '14516' => 'Commercial Product',
        '14820' => 'Consumer Basic Profile',
        '14819' => 'Commercial Basic Profile',
    ];

    public const CB_SUBJECT_TYPES = [
        '1' => 'Consumer',
        '2' => 'Commercial',
    ];

    public const CB_PURPOSE_OF_ENQUIRY = [
        '1' => 'New Credit Application',
        '2' => 'New Guarantor',
        '3' => 'Consumer Basic Profile',
        '4' => 'Commercial Basic Profile',
    ];

    public const CORE_BANKING_RESPONSES = [
        0 => 'SUCCESSFUL',
        1 => 'INVALID APPLICATION ID/PASSWORD',
        2 => 'INVALID TRANSACTION ID',
        3 => 'INSUFFICIENT FUNDS',
        4 => 'INVALID USER CREDENTIAL',
        5 => 'USER ID IS LOCKED',
        6 => 'USER ID IS EXPIRED',
        7 => 'USER TIMED OUT',
        8 => 'INVALID ACCOUNT',
        9 => 'FUNCTION NOT AVAILABLE',
        10 => 'UNSPECIFIED ERROR',
        11 => 'CHANGE PASSWORD(NEW USER)',
        12 => 'CHANGE PASSWORD(PASSWORD EXPIRED)',
        13 => 'INVALID INPUT DATA',
        14 => 'RECORD ALREADY EXIST',
        15 => 'INVALID CUSTOMER ID(CUSTOMER RECORD IS PENDING AUTHORIZATION)',
        16 => 'INVALID CUSTOMER ID(CUSTOMER ID DOES NOT EXIST)',
        17 => 'PIN is locked',
        18 => 'PIN has expired',
        19 => 'Invalid PIN',
        20 => 'Debit Limit exceeded'
    ];

}
