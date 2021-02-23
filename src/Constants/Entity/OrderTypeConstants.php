<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Constants\Entity;

final class OrderTypeConstants
{
    //Alpaca OrderInfo Types
    const MARKET_ORDER = 1;
    const LIMIT_ORDER = 2;
    const STOP_ORDER = 3;
    const STOP_LIMIT = 4;
    const TRAILING_STOP = 5;

    //Td Ameritrade OrderInfo Types
    const TRADE = 6;
    const RECEIVE_AND_DELIVER = 7;
    const DIVIDEND_OR_INTEREST = 8;
    const ACH_RECEIPT = 9;
    const ACH_DISBURSEMENT = 10;
    const CASH_RECEIPT = 11;
    const CASH_DISBURSEMENT = 12;
    const ELECTRONIC_FUND = 13;
    const WIRE_OUT = 14;
    const WIRE_IN = 15;
    const JOURNAL = 16;
    const MEMORANDUM = 17;
    const MARGIN_CALL = 18;

    const BUY = 1;
    const SELL = 2;

    const NAMES = [
        self::MARKET_ORDER => 'market',
        self::LIMIT_ORDER => 'limit',
        self::STOP_ORDER => 'stop',
        self::STOP_LIMIT => 'stop_limit',
        self::TRAILING_STOP => 'trailing_stop',
        self::TRADE => 'trade',
        self::RECEIVE_AND_DELIVER => 'receive_and_deliver',
        self::DIVIDEND_OR_INTEREST => 'dividend_or_interest',
        self::ACH_RECEIPT => 'ach_receipt',
        self::ACH_DISBURSEMENT => 'ach_disbursement',
        self::CASH_RECEIPT => 'cash_receipt',
        self::CASH_DISBURSEMENT => 'cash_disbursement',
        self::ELECTRONIC_FUND => 'electronic_fund',
        self::WIRE_OUT => 'wire_out',
        self::WIRE_IN => 'wire_in',
        self::JOURNAL => 'journal',
        self::MEMORANDUM => 'memorandum',
        self::MARGIN_CALL => 'margin_call',
     ];

    public static function getTypes()
    {
        return self::NAMES;
    }
}
