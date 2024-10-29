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

    public const BLOOD_GROUPS = [
        'A+' => 'A+',
        'A-' => 'A-',
        'B+' => 'B+',
        'B-' => 'B-',
        'AB+' => 'AB+',
        'AB-' => 'AB-',
        'O+' => 'O+',
        'O-' => 'O-',
    ];

    public const MOMO_TYPES = [
        'MTN' => 'MTN MOBILE MONEY',
        'VODAFONE' => 'VODAFONE CASH',
        'AIRTELTIGO' => 'AIRTELTIGO CASH'
    ];

    public const VISITOR_REASONS = [
        'official' => 'Official',
        'personal' => 'Personal',
        'interview' => 'Interview',
        'meeting' => 'Meeting',
        'delivery' => 'Delivery'
    ];

    public const PAYMENT_TYPES = [
        'manual' => 'Manual',
        'momo' => 'MOBILE MONEY',
        'visa' => 'Visa Card',
    ];


    public const DURATION_TYPES = [
        'hour' => 'hours',
        'day' => 'days',
        'week' => 'weeks',
        'month' => 'months'
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
    public const TITLE = [
        'mr' => 'MR',
        'mrs' => 'MRS',
        'ms' => 'MS',
        'dr' => 'DR',
        'prof' => 'Prof',
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

    public const LEAVE_CATEGORIES = [
        'annual' => 'Annual',
        'compassionate' => 'Compassionate',
        'study-leave' => 'Study Leave with Pay',
        'study-leave-no-pay' => 'Study Leave without Pay',
        'maternal' => 'Maternal',
        'sick' => 'Sick',
        'other' => 'Other',
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

    public const HRM_IMPORT_TYPES = [
        'employees' => 'Employees',
        'contact-persons' => 'Contact Person',
    ];

    public const POSITION_TYPES = [
        'general-manager' => 'General Manager',
        'hod' => 'Head of Department',
        'hr-manager' => 'HR Manager',
        'finance-manager' => 'Finance Manager',
        'unit-head' => 'Unit Head',
        'supervisor' => 'Supervisor',
        'employee' => 'Employee',
        'reliever' => 'Reliever',
        'branch-manager' => 'Branch Manager',
        'dmd' => 'Deputy Managing Director',
        'ceo' => 'CEO/MD',
    ];

    public const CONTACT_TYPES = [
        'emergency-contact' => 'Emergency Contact',
        'beneficiary' => 'Beneficiary',
        'next-of-kin' => 'Next of Kin',
        'referee' => 'Referee',
        'guarantor' => 'Guarantor',
    ];

    public const SSNIT_REPORT_TYPES = [
        'ssnit_tier_1' => 'SSNIT TIER 1',
        'ssnit_tier_2' => 'SSNIT TIER 2',
        'ssnit_tier_1_2' => 'SSNIT TIER 1 & TIER 2',
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
    const APPRAISAL_PROCESS_STAGES = [
        'PERFORMANCE_CONTRACT' => 'PERFORMANCECONTRACT',
        'MID_YEAR_REVIEW' => 'APPRAISAL-MID-YEAR-REVIEW',
        'END_YEAR_REVIEW' => 'APPRAISAL-END-YEAR-REVIEW',
        'Q1_REVIEW' => 'APPRAISAL-Q1-REVIEW',
        'Q2_REVIEW' => 'APPRAISAL-Q2-REVIEW',
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
