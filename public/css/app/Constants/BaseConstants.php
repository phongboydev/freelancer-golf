<?php
/**
 * Created by PhpStorm.
 * User: anhnguyen
 * Date: 13/11/2018
 * Time: 14:55
 */
namespace App\Constants ;

class BaseConstants
{
    //PERMISSION
    public const CREATE_PERMISSION   = 1;
    public const READ_PERMISSION     = 2;
    public const UPDATE_PERMISSION   = 4;
    public const DELETE_PERMISSION   = 8;

    // General Status
    public const PAYMENT_WAITING       = 0;
    public const PAYMENT_SUCCESS       = 1;
    public const PAYMENT_CANCEL        = 2;
    public const ACTIVE                = 1;
    public const INACTIVE              = 0;
    public const STATUS_ACTIVE         = 0;
    public const STATUS_INACTIVE       = 1;
    public const STATUS_DELETE         = 2;
    public const STATUS_ACTIVE_TEXT    = 'active';
    public const STATUS_ACCEPT_TEXT    = 'accepted';
    public const DEFAULT_LANGUAGE      = 'en';
    public const DB_TRUE = 2;
    public const DB_FALSE = 1;
    public const FLAG_MANUAL   = 1;
    public const FLAG_PROGRAM  = 2;
    public const SUPPLIER_ELECTRIC_APPLIANCE  = 1;
    public const SUPPLIER_HOUSE_WARE  = 5;
    public const SUPPLIER_LEARNING_TOOL_1  = 3;
    public const SUPPLIER_LEARNING_TOOL_2  = 3;
    public const VIETNAM_COUNTRY_ID   = 240;
    public const OTHER_CATEGORY_ID  = 175;

    // Request Method
    public const METHOD_GET            = 'GET';
    public const METHOD_POST           = 'POST';
    public const METHOD_PUT            = 'PUT';
    public const METHOD_DELETE         = 'DELETE';

    // Environment Setting
    public const WHITELISTED_ENV       = ['local', 'develop', 'test', 'stage'];

    /*
     *  Error Key
     */
    // General ERROR
    public const PERMISSION_DENIED             = 'PERMISSION_DENIED';

    public const TYPE_EXISTS                   = 1;
    public const TYPE_DOES_NOT_EXIST           = 2;
    public const TYPE_CAN_NOT_BE_DELETED       = 3;
    public const TYPE_NAME_EXISTS              = 4;
    public const TYPE_CAN_NOT_BE_DEACTIVATE    = 5;
    public const TYPE_IS_LOCKED                = 6;
    public const ITEM_EXISTS                   = '_EXISTS';
    public const ITEM_DOES_NOT_EXIST           = '_DOES_NOT_EXIST';
    public const ITEM_CAN_NOT_BE_DELETED       = '_CAN_NOT_BE_DELETED';
    public const ITEM_CAN_NOT_BE_DEACTIVATE    = '_CAN_NOT_BE_DEACTIVATE';
    public const ITEM_NAME_EXISTS              = '_NAME_EXISTS';
    public const ITEM_IS_LOCKED                = '_IS_LOCKED';
    public const ERROR_CODES                   = [
        'data_not_found'                                => 10000,
        'invalid_request_parameters'                    => 10001,
        'same_old_password_and_new_password'            => 10002,
        'data_mismatch'                                 => 10003,
        'unauthorized_access'                           => 10004,
        'query_error'                                   => 10005,
    ];

    //Language
    public const LANGUAGE_CODE_EXISTS          = 'LANGUAGE_CODE_EXISTS';
    public const LANGUAGE_ID_DOES_NOT_EXIST    = 'LANGUAGE_ID_DOES_NOT_EXIST';
    public const LANGUAGE_CAN_NOT_DELETE       = 'LANGUAGE_CAN_NOT_DELETE';
    public const LANGUAGE_ENGLISH              = 'en';


    //IP Rule
    public const IP_ADDRESS_EXISTS             = 'IP_ADDRESS_EXISTS';
    public const IP_ID_DOES_NOT_EXIST          = 'IP_ID_DOES_NOT_EXIST';

    //Role
    public const ROLE_NAME_EXISTS              = 'ROLE_NAME_EXISTS';
    public const ROLE_ID_DOES_NOT_EXIST        = 'ROLE_ID_DOES_NOT_EXIST';
    public const ROLE_CAN_NOT_DELETE           = 'ROLE_CAN_NOT_DELETE';
    public const ROLES_CAN_NOT_DELETE          = 'ROLES_CAN_NOT_DELETE';
    public const ROLE_IS_LOCKED                = 'ROLE_IS_LOCKED';

    //User Management
    public const EMAIL_EXISTS                  = 'EMAIL_EXISTS';
    public const USERNAME_EXISTS               = 'USERNAME_EXISTS';

    //Player
    public const REGISTERED_BY_MOBILE          = 'mobile';
    public const REGISTERED_BY_WEBSITE         = 'website';
    public const REGISTRATION_SOURCE_DEFAULT   = self::REGISTRATION_SOURCE_ORGANIC;
    public const PLATFORM = [
        'android' => 1,
        'ios' => 2,
        'website' => 3
    ];

    public const APP_TYPE_ANDROID  = 1;
    public const APP_TYPE_IOS      = 2;
    public const APP_TYPE_WEB      = 3;
    public const APP_TYPE_ANDROID_TEXT = 'Android App';
    public const APP_TYPE_IOS_TEXT = 'iOS App';
    public const APP_TYPE_WEB_TEXT = 'Web';

    //Game Type
    public const GAME_TYPE_ID_DOES_NOT_EXIST   = 'GAME_TYPE_ID_DOES_NOT_EXIST';

    //Payment Account
    public const PAYMENT_ACCOUNT_ID_DOES_NOT_EXIST   = 'PAYMENT_ACCOUNT_ID_DOES_NOT_EXIST';

    //Game Description
    public const GAME_DESCRIPTION_ID_DOES_NOT_EXIST = 'GAME_DESCRIPTION_ID_DOES_NOT_EXIST';

    //Game API Provider
    public const GAME_PROVIDER_CODE_EXIST = "GAME_PROVIDER_CODE_EXIST";

    //Function
    public const FUNCTION_CODE_EXISTS          = 'FUNCTION_CODE_EXISTS';

    // Bonus
    public const BONUS_CODE_EXISTS          = 'BONUS_CODE_EXISTS';
    public const PROMO_CODE_EXISTS          = 'PROMO_CODE_EXISTS';

    // User type

    // JWT Response Code
    public const JWT_OK                = 200;
    public const JWT_COMMON_ERROR      = 400;
    public const JWT_EXPIRED           = 408;

    public const PLAYER_RESPONSE_FAIL = 0;
    public const PLAYER_RESPONSE_SUCCESS = 1;

    public const REQUEST_HEADER_COOKIE = 'Cookie';
    public const REQUEST_HEADER_LANG = 'lang';
    public const REQUEST_HEADER_UA = 'User-Agent';
    public const REQUEST_SRV_VARIABLE_REFERRER = 'HTTP_REFERER';

    public const UNKNOWN_OS = "Unknown OS";
    public const UNKNOWN_DEVICE = "Unknown Device";

    // Open API docs
    public const OPEN_API_TAG_FE = "Front-end";
    public const OPEN_API_TAG_BO = "Back-office";

    public const SUPER_ADMIN_ROLE_ID   = 1;
    public const VAL_TRUE              = 1;
    public const VAL_FALSE             = 0;
    public const BOOL_TRUE             = true;
    public const BOOL_FALSE            = false;

    // message key public constants
    public const MUST_DEFINE                    = "MUST_DEFINE";
    // 1xx Informational
    public const CONTINUE                       = "CONTINUE";
    // 2xx Success
    public const OK                             = "OK";
    public const CREATED                        = "CREATED";
    public const NO_CONTENT                     = "NO_CONTENT";
    // 3xx Redirection
    public const NOT_MODIFIED                   = "NOT_MODIFIED";

    // 4xx Client Error
    public const BAD_REQUEST                    = "BAD_REQUEST";
    public const UNAUTHORIZED                   = "UNAUTHORIZED";
    public const FORBIDDEN                      = "FORBIDDEN";
    public const NOT_FOUND                      = "NOT_FOUND";
    public const HTTP_METHOD_NOT_ALLOWED        = "HTTP_METHOD_NOT_ALLOWED";
    public const CONFLICT                       = "CONFLICT";
    public const PRECONDITION_FAILED            = "PRECONDITION_FAILED";
    public const UNSUPPORTED_MEDIA_TYPE         = "UNSUPPORTED_MEDIA_TYPE";
    public const UNPROCESSABLE                  = "UNPROCESSABLE";

    // 5xx Server Error
    public const INTERNAL_ERROR                 = "INTERNAL_ERROR";
    public const NOT_IMPLEMENTED                = "NOT_IMPLEMENTED";

    public const CONFIG_PAYMENT_USERNAME_REQUIRED = [];

    public const STATUS_SETTLED = 1; //never change, should in game_logs
    public const STATUS_PENDING = 2; //waiting settle
    public const STATUS_ACCEPTED = 3; //accepted bet
    public const STATUS_REJECTED = 4; //rejected bet
    public const STATUS_CANCELLED = 5; //cancel
    public const STATUS_VOID = 6;
    public const STATUS_REFUND = 7;
    public const STATUS_UNSETTLE = 8;

    public const STORE_REDIS               = 'redis';
    public const STORE_REDIS_PRIVATE       = 'redis_private';
    public const STORE_REDIS_ES            = 'redis_es';
    public const DISK_REDIS_DEFAULT        = 'default';
    public const DISK_REDIS_PRIVATE        = 'private';
    public const DISK_REDIS_ES             = 'es';
    public const LOCK_TIMEOUT_1_SECOND     = 1; // seconds
    public const LOCK_TIMEOUT_2_SECOND     = 2;
    public const LOCK_TIMEOUT_3_SECOND     = 3;
    public const LOCK_TIMEOUT_5_SECOND     = 5;
    public const LOCK_TIMEOUT_10_SECOND    = 10;
    public const LOCK_TIMEOUT_15_SECOND    = 15;
    public const LOCK_TIMEOUT_20_SECOND    = 20;
    public const LOCK_TIMEOUT_25_SECOND    = 25;
    public const LOCK_TIMEOUT_30_SECOND    = 30;
    public const LOCK_TIMEOUT_45_SECOND    = 45;
    public const LOCK_TIMEOUT_50_SECOND    = 50;
    public const LOCK_TIMEOUT_1_MINUTE     = 60;
    public const LOCK_TIMEOUT_5_MINUTE     = 60*5;
    public const LOCK_TIMEOUT_10_MINUTE    = 60*10;
    public const LOCK_TIMEOUT_15_MINUTE    = 60*15;
    public const LOCK_TIMEOUT_30_MINUTE    = 60*30;
    public const LOCK_TIMEOUT_1_HOUR       = 60*60;
    public const LOCK_TIMEOUT_2_HOUR       = 60*60*2;
    public const LOCK_TIMEOUT_3_HOUR       = 60*60*3;
    public const LOCK_TIMEOUT_5_HOUR       = 60*60*5;
    public const LOCK_TIMEOUT_6_HOUR       = 60*60*6;
    public const LOCK_TIMEOUT_12_HOUR      = 60*60*12;
    public const LOCK_TIMEOUT_18_HOUR      = 60*60*18;
    public const LOCK_TIMEOUT_24_HOUR      = 60*60*24;
    public const CACHE_EXPIRE_1_MINUTE         = 60;
    public const CACHE_EXPIRE_5_MINUTE         = 60*5;
    public const CACHE_EXPIRE_10_MINUTE        = 60*10;
    public const CACHE_EXPIRE_15_MINUTE        = 60*15;
    public const CACHE_EXPIRE_30_MINUTE        = 60*30;
    public const CACHE_EXPIRE_1_HOUR           = 60*60;
    public const CACHE_EXPIRE_2_HOUR           = 60*60*2;
    public const CACHE_EXPIRE_3_HOUR           = 60*60*3;
    public const CACHE_EXPIRE_5_HOUR           = 60*60*5;
    public const CACHE_EXPIRE_6_HOUR           = 60*60*6;
    public const CACHE_EXPIRE_12_HOUR          = 60*60*12;
    public const CACHE_EXPIRE_18_HOUR          = 60*60*18;
    public const CACHE_EXPIRE_24_HOUR          = 60*60*24;

    public const TRANSACTION_TYPE = [
        1 => 'deposit',
        2 => 'withdraw',
        5 => 'fund_transfer_to_sub_wallet',
        6 => 'fund_transfer_to_main_wallet',
        7 => 'manual_add_balance',
        8 => 'manual_subtract_balance',
        9 => 'add_bonus',
        10 => 'cancel_bonus_issuance'
    ];

    public const COUNTRY_LIST = [
        'Afghanistan' => 'Afghanistan', 'Albania' => 'Albania', 'Algeria' => 'Algeria', 'Andorra' => 'Andorra',
        'Angola' => 'Angola', 'Antigua and Barbuda' => 'Antigua and Barbuda', 'Argentina' => 'Argentina',
        'Armenia' => 'Armenia', 'Australia' => 'Australia', 'Austria' => 'Austria', 'Azerbaijan' => 'Azerbaijan',
        'Bahamas' => 'Bahamas', 'Bahrain' => 'Bahrain', 'Bangladesh' => 'Bangladesh', 'Barbados' => 'Barbados',
        'Belarus' => 'Belarus', 'Belgium' => 'Belgium', 'Belize' => 'Belize', 'Benin' => 'Benin', 'Bhutan' =>
            'Bhutan', 'Bolivia' => 'Bolivia', 'Bosnia and Herzegovina' => 'Bosnia and Herzegovina',
        'Botswana' => 'Botswana', 'Brazil' => 'Brazil', 'Brunei' => 'Brunei', 'Bulgaria' => 'Bulgaria',
        'Burkina Faso' => 'Burkina Faso', 'Burundi' => 'Burundi', 'Cambodia' => 'Cambodia', 'Cameroon' => 'Cameroon',
        'Canada' => 'Canada', 'Cape Verde' => 'Cape Verde',
        'Central African Republic' => 'Central African Republic', 'Chad' => 'Chad', 'Chile' => 'Chile',
        'China' => 'China', 'Colombia' => 'Colombia', 'Comoros' => 'Comoros',
        "Congo (Brazzaville)" => "Congo (Brazzaville)", 'Congo' => 'Congo',
        'Costa Rica' => 'Costa Rica', "Cote d'Ivoire" => "Cote d'Ivoire",
        'Croatia' => 'Croatia', 'Cuba' => 'Cuba', 'Cyprus' => 'Cyprus', 'Czech Republic' => 'Czech Republic',
        'Denmark' => 'Denmark', 'Djibouti' => 'Djibouti', 'Dominica' => 'Dominica',
        'Dominican Republic' => 'Dominican Republic', 'East Timor (Timor Timur)' => 'East Timor (Timor Timur)',
        'Ecuador' => 'Ecuador', 'Egypt' => 'Egypt', 'El Salvador' => 'El Salvador',
        'Equatorial Guinea' => 'Equatorial Guinea', 'Eritrea' => 'Eritrea', 'Estonia' => 'Estonia',
        'Ethiopia' => 'Ethiopia', 'Fiji' => 'Fiji', 'Finland' => 'Finland', 'France' => 'France',
        'Gabon' => 'Gabon', 'Gambia, The' => 'Gambia, The', 'Georgia' => 'Georgia', 'Germany' => 'Germany',
        'Ghana' => 'Ghana', 'Greece' => 'Greece', 'Grenada' => 'Grenada', 'Guatemala' => 'Guatemala',
        'Guinea' => 'Guinea', 'Guinea-Bissau' => 'Guinea-Bissau', 'Guyana' => 'Guyana', 'Haiti' => 'Haiti',
        'Honduras' => 'Honduras', 'Hungary' => 'Hungary', 'Iceland' => 'Iceland', 'India' => 'India',
        'Indonesia' => 'Indonesia', 'Iran' => 'Iran', 'Iraq' => 'Iraq', 'Ireland' => 'Ireland',
        'Israel' => 'Israel', 'Italy' => 'Italy', 'Jamaica' => 'Jamaica', 'Japan' => 'Japan',
        'Jordan' => 'Jordan', 'Kazakhstan' => 'Kazakhstan', 'Kenya' => 'Kenya', 'Kiribati' => 'Kiribati',
        'Korea, North' => 'Korea, North', 'Korea, South' => 'Korea, South', 'Kuwait' => 'Kuwait',
        'Kyrgyzstan' => 'Kyrgyzstan', 'Laos' => 'Laos', 'Latvia' => 'Latvia', 'Lebanon' => 'Lebanon',
        'Lesotho' => 'Lesotho', 'Liberia' => 'Liberia', 'Libya' => 'Libya', 'Liechtenstein' => 'Liechtenstein',
        'Lithuania' => 'Lithuania', 'Luxembourg' => 'Luxembourg', 'Macedonia' => 'Macedonia',
        'Madagascar' => 'Madagascar', 'Malawi' => 'Malawi', 'Malaysia' => 'Malaysia',
        'Maldives' => 'Maldives', 'Mali' => 'Mali', 'Malta' => 'Malta', 'Marshall Islands' => 'Marshall Islands',
        'Mauritania' => 'Mauritania', 'Mauritius' => 'Mauritius', 'Mexico' => 'Mexico', 'Micronesia' => 'Micronesia',
        'Moldova' => 'Moldova', 'Monaco' => 'Monaco', 'Mongolia' => 'Mongolia', 'Morocco' => 'Morocco',
        'Mozambique' => 'Mozambique', 'Myanmar' => 'Myanmar', 'Namibia' => 'Namibia', 'Nauru' => 'Nauru',
        'Nepal' => 'Nepal', 'Netherlands' => 'Netherlands', 'New Zealand' => 'New Zealand', 'Nicaragua' => 'Nicaragua',
        'Niger' => 'Niger', 'Nigeria' => 'Nigeria', 'Norway' => 'Norway', 'Oman' => 'Oman', 'Pakistan' => 'Pakistan',
        'Palau' => 'Palau', 'Panama' => 'Panama', 'Papua New Guinea' => 'Papua New Guinea', 'Paraguay' => 'Paraguay',
        'Peru' => 'Peru', 'Philippines' => 'Philippines', 'Poland' => 'Poland', 'Portugal' => 'Portugal',
        'Qatar' => 'Qatar', 'Romania' => 'Romania', 'Russia' => 'Russia', 'Rwanda' => 'Rwanda',
        'Saint Kitts and Nevis' => 'Saint Kitts and Nevis', 'Saint Lucia' => 'Saint Lucia',
        'Saint Vincent' => 'Saint Vincent', 'Samoa' => 'Samoa', 'San Marino' => 'San Marino',
        'Sao Tome and Principe' => 'Sao Tome and Principe', 'Saudi Arabia' => 'Saudi Arabia',
        'Senegal' => 'Senegal', 'Serbia and Montenegro' => 'Serbia and Montenegro',
        'Seychelles' => 'Seychelles', 'Sierra Leone' => 'Sierra Leone', 'Singapore' => 'Singapore',
        'Slovakia' => 'Slovakia', 'Slovenia' => 'Slovenia', 'Solomon Islands' => 'Solomon Islands',
        'Somalia' => 'Somalia', 'South Africa' => 'South Africa', 'Spain' => 'Spain', 'Sri Lanka' =>
            'Sri Lanka', 'Sudan' => 'Sudan', 'Suriname' => 'Suriname', 'Swaziland' => 'Swaziland',
        'Sweden' => 'Sweden', 'Switzerland' => 'Switzerland', 'Syria' => 'Syria', 'Taiwan' => 'Taiwan',
        'Tajikistan' => 'Tajikistan', 'Tanzania' => 'Tanzania', 'Thailand' => 'Thailand', 'Togo' => 'Togo',
        'Tonga' => 'Tonga', 'Trinidad and Tobago' => 'Trinidad and Tobago', 'Tunisia' => 'Tunisia',
        'Turkey' => 'Turkey', 'Turkmenistan' => 'Turkmenistan', 'Tuvalu' => 'Tuvalu', 'Uganda' => 'Uganda',
        'Ukraine' => 'Ukraine', 'United Arab Emirates' => 'United Arab Emirates', 'United Kingdom' => 'United Kingdom',
        'United States' => 'United States', 'Uruguay' => 'Uruguay', 'Uzbekistan' => 'Uzbekistan',
        'Vanuatu' => 'Vanuatu', 'Vatican City' => 'Vatican City', 'Venezuela' => 'Venezuela', 'Vietnam' => 'Vietnam',
        'Yemen' => 'Yemen', 'Zambia' => 'Zambia', 'Zimbabwe' => 'Zimbabwe'
    ];

    public const COUNTRY_CALLING_CODES = [
        //"EN" => "+44",
        "AF" => "+93",
        "AX" => "+358",
        "AL" => "+355",
        "DZ" => "+213",
        "AS" => "+1684",
        "AD" => "+376",
        "AO" => "+244",
        "AI" => "+1264",
        "AQ" => "+6721",
        "AG" => "+1268",
        "AR" => "+54",
        "AM" => "+374",
        "AW" => "+297",
        "AU" => "+61",
        "AC" => "+247",
        "AT" => "+43",
        "AZ" => "+994",
        "BS" => "+1242",
        "BH" => "+973",
        "BD" => "+880",
        "BB" => "+1246",
        "BY" => "+375",
        "BE" => "+32",
        "BZ" => "+501",
        "BJ" => "+229",
        "BM" => "+1441",
        "BT" => "+975",
        "BO" => "+591",
        "BA" => "+387",
        "BW" => "+267",
        "BV" => "+47",
        "BR" => "+55",
        "VG" => "+1284",
        "IO" => "+246",
        "BN" => "+673",
        "BG" => "+359",
        "BF" => "+226",
        "BI" => "+257",
        "KH" => "+855",
        "CM" => "+237",
        "CA" => "+1",
        "CV" => "+238",
        "KY" => "+1345",
        "CF" => "+236",
        "TD" => "+235",
        "CL" => "+56",
        "CN" => "+86",
        "CX" => "+61",
        "CC" => "+61",
        "CO" => "+57",
        "KM" => "+269",
        "CG" => "+242",
        "CD" => "+243",
        "CK" => "+682",
        "CR" => "+506",
        "CI" => "+225",
        "HR" => "+385",
        "CU" => "+53",
        "CW" => "+599",
        "CY" => "+357",
        "CZ" => "+420",
        "DK" => "+45",
        "DJ" => "+253",
        "DM" => "+1767",
        "DO" => "+1809",
        "DO1" => "+1829",
        "DO2" => "+1849",
        "TP" => "+670",
        "EC" => "+593",
        "EG" => "+20",
        "SV" => "+503",
        "GQ" => "+240",
        "ER" => "+291",
        "EE" => "+372",
        "ET" => "+251",
        "FK" => "+500",
        "FO" => "+298",
        "FJ" => "+679",
        "FI" => "+358",
        "FR" => "+33",
        "GF" => "+594",
        "PF" => "+689",
        "TF" => "+262",
        "GA" => "+241",
        "GM" => "+220",
        "PS" => "+970",
        "GE" => "+995",
        "DE" => "+49",
        "GH" => "+233",
        "GI" => "+350",
        "GR" => "+30",
        "GL" => "+299",
        "GD" => "+1473",
        "GP" => "+590",
        "GU" => "+1671",
        "GT" => "+502",
        "GG" => "+44",
        "GN" => "+224",
        "GW" => "+245",
        "GY" => "+592",
        "VA" => "+379",
        "HT" => "+509",
        "HN" => "+504",
        "HK" => "+852",
        "HU" => "+36",
        "IS" => "+354",
        "IN" => "+91",
        "ID" => "+62",
        "IQ" => "+964",
        "IR" => "+98",
        "IE" => "+4428",
        "IM" => "+44",
        "IL" => "+972",
        "IT" => "+39",
        "JM" => "+1876",
        "JP" => "+81",
        "JE" => "+44",
        "JO" => "+962",
        "KZ" => "+7",
        "KE" => "+254",
        "KP" => "+850",
        "KR" => "+82",
        "KI" => "+686",
        "XK" => "+383",
        "KW" => "+965",
        "KG" => "+996",
        "LA" => "+856",
        "LV" => "+371",
        "LB" => "+961",
        "LS" => "+266",
        "LR" => "+231",
        "LY" => "+218",
        "LI" => "+423",
        "LT" => "+370",
        "LU" => "+352",
        "MO" => "+853",
        "MK" => "+389",
        "MG" => "+261",
        "MW" => "+265",
        "MY" => "+60",
        "MV" => "+960",
        "ML" => "+223",
        "MT" => "+356",
        "MH" => "+692",
        "MQ" => "+596",
        "MR" => "+222",
        "MU" => "+230",
        "YT" => "+262",
        "MX" => "+52",
        "FM" => "+691",
        "MD" => "+373",
        "MC" => "+377",
        "MN" => "+976",
        "ME" => "+382",
        "MS" => "+1664",
        "MA" => "+212",
        "MZ" => "+258",
        "MM" => "+95",
        "NA" => "+264",
        "NR" => "+674",
        "NP" => "+977",
        "NL" => "+31",
        "AN" => "+599",
        "NC" => "+687",
        "NZ" => "+64",
        "NI" => "+505",
        "NE" => "+227",
        "NG" => "+234",
        "NU" => "+683",
        "NF" => "+6723",
        "MP" => "+1670",
        "NO" => "+47",
        "OM" => "+968",
        "PK" => "+92",
        "PW" => "+680",
        "PA" => "+507",
        "PG" => "+675",
        "PY" => "+595",
        "PE" => "+51",
        "PH" => "+63",
        "PN" => "+64",
        "PL" => "+48",
        "PT" => "+351",
        "PR" => "+1787",
        "PR1" => "+1939",
        "QA" => "+974",
        "RO" => "+40",
        "RU" => "+7",
        "RW" => "+250",
        "RE" => "+262",
        "BL" => "+590",
        "SH" => "+290",
        "KN" => "+1869",
        "MF" => "+590",
        "LC" => "+1758",
        "PM" => "+508",
        "VC" => "+1784",
        "WS" => "+685",
        "SM" => "+378",
        "ST" => "+239",
        "SA" => "+966",
        "SN" => "+221",
        "RS" => "+381",
        "SC" => "+248",
        "SL" => "+232",
        "SX" => "+1721",
        "SG" => "+65",
        "SK" => "+421",
        "SI" => "+386",
        "SB" => "+677",
        "SO" => "+252",
        "SS" => "+211",
        "GS" => "+500",
        "ES" => "+34",
        "ZA" => "+27",
        "LK" => "+94",
        "SD" => "+249",
        "SR" => "+597",
        "SZ" => "+268",
        "SE" => "+46",
        "CH" => "+41",
        "SY" => "+963",
        "TW" => "+886",
        "TJ" => "+992",
        "TZ" => "+255",
        "TH" => "+66",
        "TG" => "+228",
        "TL" => "+670",
        "TK" => "+690",
        "TO" => "+676",
        "TT" => "+1868",
        "TN" => "+216",
        "TR" => "+90",
        "TM" => "+993",
        "TC" => "+1649",
        "TV" => "+688",
        "UG" => "+256",
        "UA" => "+380",
        "AE" => "+971",
        "GB" => "+44",
        "US" => "+1",
        "UY" => "+598",
        "UZ" => "+998",
        "VU" => "+678",
        "VE" => "+58",
        "VN" => "+84",
        "VI" => "+1340",
        "WF" => "+681",
        "EH" => "+970",
        "YE" => "+967",
        "ZM" => "+260",
        "ZW" => "+263"
    ];

    public const DEFAULT_TOPIC_USER = 'default_user';
    public const DEFAULT_TOPIC_DRIVER = 'default_driver';

    //Paginate
    const PAGINATE_LIMIT = 10;
}
