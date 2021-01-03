<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Constants\Brokerage;

use App\Constants\Entity\UserConstants;

/**
 * Class BrokerageConstants.
 */
final class BrokerageConstants
{
    const BROKERAGE_SERVICE_NOT_FOUND = 'Brokerage Service not found';

    const BROKERAGE_KEY = 'brokerage';
    const BROKERAGE_GUID_KEY = 'guid';
    const BROKERAGE_NAME_KEY = 'name';
    const BROKERAGE_CONTEXT_KEY = 'context';
    const BROKERAGE_CREATED_BY_KEY = 'created_by';
    const BROKERAGE_MODIFIED_BY_KEY = 'modified_by';
    const BROKERAGE_DESCRIPTION_KEY = 'description';
    const BROKERAGE_ORDER_CLASSES = 'order_class_type';
    const BROKERAGE_ORDER_STATUSES_KEY = 'order_status_type';
    const BROKERAGE_ORDER_TYPES_KEY = 'order_type';
    const BROKERAGE_POSITION_SIDE_TYPES_KEY = 'position_side_type';
    const BROKERAGE_URL = 'url';
    const BROKERAGE_API_DOCUMENT_URL_KEY = 'api_document_url';
    const BROKERAGES = [
        [
            self::BROKERAGE_KEY => [
                self::BROKERAGE_GUID_KEY => AlpacaConstants::BROKERAGE_GUID,
                self::BROKERAGE_NAME_KEY => AlpacaConstants::BROKERAGE_NAME,
                self::BROKERAGE_DESCRIPTION_KEY => AlpacaConstants::BROKERAGE_DESCRIPTION,
                self::BROKERAGE_CONTEXT_KEY => AlpacaConstants::BROKERAGE_CONTEXT,
                self::BROKERAGE_URL => AlpacaConstants::BROKERAGE_URL,
                self::BROKERAGE_API_DOCUMENT_URL_KEY => AlpacaConstants::BROKERAGE_API_DOCUMENT_URL,
                self::BROKERAGE_CREATED_BY_KEY => UserConstants::SYSTEM_USER_USERNAME,
                self::BROKERAGE_MODIFIED_BY_KEY => UserConstants::SYSTEM_USER_USERNAME,
            ],
//            self::BROKERAGE_ORDER_CLASSES => AlpacaConstants::ORDER_CLASS_TYPES,
            self::BROKERAGE_ORDER_STATUSES_KEY => AlpacaConstants::ORDER_STATUSES,
            self::BROKERAGE_ORDER_TYPES_KEY => AlpacaConstants::ORDER_TYPES,
            self::BROKERAGE_POSITION_SIDE_TYPES_KEY => AlpacaConstants::POSITION_SIDE_TYPES,
        ],
//        [
//            self::BROKERAGE_NAME => AlpacaConstants::BROKERAGE_NAME,
//            self::BROKERAGE_DESCRIPTION_KEY => AlpacaConstants::BROKERAGE_DESCRIPTION,
//            self::BROKERAGE_URL => AlpacaConstants::BROKERAGE_URL,
//            self::BROKERAGE_API_DOCUMENT_URL_KEY => AlpacaConstants::BROKERAGE_API_DOCUMENT_URL,
//            self::BROKERAGE_ORDER_CLASSES => AlpacaConstants::ORDER_CLASS_TYPES,
//            self::BROKERAGE_ORDER_STATUSES_KEY => AlpacaConstants::ORDER_STATUSES,
//            self::BROKERAGE_ORDER_TYPES_KEY => AlpacaConstants::ORDER_TYPES,
//            self::BROKERAGE_POSITION_SIDE_TYPES_KEY => AlpacaConstants::POSITION_SIDE_TYPES,
//        ],
    ];

    /**
     * @return array[]
     */
    public static function getBrokerages()
    {
        return self::BROKERAGES;
    }
}
