<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Constants\Brokerage;

final class AlpacaConstants
{
    // Api Version
    const API_VERSION = '/v2';
    const API_ENDPOINT = 'https://api.alpaca.markets';
    const PAPER_API_ENDPOINT = 'https://paper-api.alpaca.markets';

    // Api Endpoints
    const ACCOUNT_ENDPOINT = self::API_VERSION.'/account';
    const ACCOUNT_CONFIG_ENDPOINT = self::ACCOUNT_ENDPOINT.'/configurations';
    const ASSETS_ENDPOINT = self::API_VERSION.'/assets';
    const ORDERS_ENDPOINT = self::API_VERSION.'/orders';
    const POSITIONS_ENDPOINT = self::API_VERSION.'/positions';

    const ORDER_HISTORY_DEFAULT_PAGE_LIMIT = 500;
    // AlpacaAccountInfoEntity Class
    const ACCOUNT_INFO_ENTITY_CLASS = 'App\DTO\Brokerage\Alpaca\Account';
    const ACCOUNT_CONFIGURATION_ENTITY_CLASS = 'App\DTO\Brokerage\Alpaca\AccountConfiguration';

    // AlpacaOrderInfoEntity Class
    const ORDER_INFO_ENTITY_CLASS = 'App\DTO\Brokerage\Alpaca\Order\OrderInfo';

    const POSITION_INFO_ENTITY_CLASS = 'App\DTO\Brokerage\Alpaca\Position';

    // Brokerage Name
    const BROKERAGE_NAME = 'Alpaca Trader';
    const BROKERAGE_GUID = '9e13594c-0172-45b4-a9db-ed11db638601';
    const BROKERAGE_DESCRIPTION = 'Alpaca Trade Api';
    const BROKERAGE_CONTEXT = 'alpaca';
    const BROKERAGE_URL = 'https://alpaca.markets/';
    const BROKERAGE_API_DOCUMENT_URL = 'https://alpaca.markets/docs/api-documentation/api-v2/';

    // Request Constants
    const REQUEST_HEADER_API_KEY = 'APCA-API-KEY-ID';
    const REQUEST_HEADER_API_SECRET_KEY = 'APCA-API-SECRET-KEY';
    const REQUEST_RETURN_DATA_TYPE = 'json';

    const ACCOUNT_INFO_SERIALIZATION_CONFIG = '/opt/app-root/src/config/serialization/alpaca_account_info.yml';
    const ACCOUNT_CONFIGURATION__SERIALIZATION_CONFIG = '/opt/app-root/src/config/serialization/alpaca_account_info.yml';
    const ORDER_INFO_SERIALIZATION_CONFIG = '/opt/app-root/src/config/serialization/alpaca_order_info.yml';
    const ORDER_INFO_UNIQUE_KEY = 'client_order_id';
    const ORDER_POSITION_INFO_SERIALIZATION_CONFIG = '/opt/app-root/src/config/serialization/alpaca_position_info.yml';
    const TICKER_INFO_ENTITY_CLASS = 'App\DTO\Brokerage\Alpaca\Ticker';

    // Filter Enums
    const ORDERS_STATUS_ENUM = ['open', 'closed', 'all'];
    const ORDERS_DIRECTION_ENUM = ['asc', 'desc'];

    const ORDERS_FILTERS_DATATYPE = [
        'nested' => \FILTER_VALIDATE_BOOLEAN,
        'limit' => \FILTER_VALIDATE_INT,
    ];

    // Enums
    const ORDER_CLASS_TYPES = [
        'simple' => 'default',
        'bracket' => 'A bracket order is a chain of three orders that can be used to manage your position entry and exit. It is a common use case of an OTOCO (One Triggers OCO {One Cancels Other}) order.',
        'oco' => 'OCO (One-Cancels-Other) is another type of advanced order type. This is a set of two orders with the same side (buy/buy or sell/sell) and currently only exit order is supported. In other words, this is the second part of the bracket orders where the entry order is already filled, and you can submit the take-profit and stop-loss in one order submission.',
        'oto' => 'OTO (One-Triggers-Other) is a variant of bracket order. It takes one of the take-profit or stop-loss order in addition to the entry order. For example, if you want to set only a stop-loss order attached to the position, without a take-profit, you may want to consider OTO orders.',
    ];
    const ORDER_STATUSES = [
        'new' => 'The order has been received by Alpaca, and routed to exchanges for execution. This is the usual initial state of an order.',
        'partially_filled' => 'The order has been partially filled.',
        'filled' => 'The order has been filled, and no further updates will occur for the order.',
        'done_for_day' => 'The order is done executing for the day, and will not receive further updates until the next trading day.',
        'canceled' => 'The order has been canceled, and no further updates will occur for the order. This can be either due to a cancel request by the user, or the order has been canceled by the exchanges due to its time-in-force.',
        'expired' => 'The order has expired, and no further updates will occur for the order.',
        'replaced' => 'The order was replaced by another order, or was updated due to a market event such as corporate action.',
        'pending_cancel' => 'The order is waiting to be canceled.',
        'pending_replace' => 'The order is waiting to be replaced by another order. The order will reject cancel request while in this state.',
        'accepted' => 'The order has been received by Alpaca, but hasn\'t yet been routed to the execution venue. This could be seen often out side of trading session hours.',
        'pending_new' => 'The order has been received by Alpaca, and routed to the exchanges, but has not yet been accepted for execution. This state only occurs on rare occasions.',
        'accepted_for_bidding' => 'The order has been received by exchanges, and is evaluated for pricing. This state only occurs on rare occasions.',
        'stopped' => 'The order has been stopped, and a trade is guaranteed for the order, usually at a stated price or better, but has not yet occurred. This state only occurs on rare occasions.',
        'rejected' => 'The order has been rejected, and no further updates will occur for the order. This state occurs on rare occasions and may occur based on various conditions decided by the exchanges.',
        'suspended' => 'The order has been suspended, and is not eligible for trading. This state only occurs on rare occasions.',
        'calculated' => 'The order has been completed for the day (either filled or done for day), but remaining settlement calculations are still pending. This state only occurs on rare occasions.',
    ];
    const ORDER_TYPES = [
        'market' => 'A market order is a request to buy or sell a security at the currently available market price. It provides the most likely method of filling an order. Market orders fill nearly instantaneously',
        'limit' => 'A limit order is an order to buy or sell at a specified price or better. A buy limit order (a limit order to buy) is executed at the specified limit price or lower (i.e., better). Conversely, a sell limit order (a limit order to sell) is executed at the specified limit price or higher (better). Unlike a market order, you have to specify the limit price parameter when submitting your order.',
        'stop' => 'A stop (market) order is an order to buy or sell a security when its price moves past a particular point, ensuring a higher probability of achieving a predetermined entry or exit price. Once the market price crosses the specified stop price, the stop order becomes a market order. Alpaca converts buy stop orders into stop limit orders with a limit price that is 4% higher than a stop price < $50 (or 2.5% higher than a stop price >= $50). Sell stop orders are not converted into stop limit orders.',
        'stop_limit' => 'A stop-limit order is a conditional trade over a set time frame that combines the features of a stop order with those of a limit order and is used to mitigate risk. The stop-limit order will be executed at a specified limit price, or better, after a given stop price has been reached. Once the stop price is reached, the stop-limit order becomes a limit order to buy or sell at the limit price or better.',
        'trailing_stop' => 'Trailing stop orders allow you to continuously and automatically keep updating the stop price threshold based on the stock price movement. You request a single order with a dollar offset value or percentage value as the trail and the actual stop price for this order changes as the stock price moves in your favorable way, or stay at the last level otherwise. This way, you don’t need to monitor the price movement and keep sending replace requests to update the stop price close to the latest market movement.',
    ];
    const ORDER_SIDE_TYPES = [
        'buy' => 'Buy an asset',
        'sell' => 'Sell an asset',
    ];
    const POSITION_SIDE_TYPES = [
        'long' => 'default',
    ];
}
