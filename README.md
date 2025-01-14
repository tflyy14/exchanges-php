### Introduction
This SDK brings together the APIs of the most traded exchanges currently, allowing developers to focus only on the business layer. It is currently only a simple support for purchases, sales, inquiries, account balances and positions. Later authors will improve more APIs.

This SDK is based on the basic API of [Bitmex](https://github.com/zhouaini528/bitmex-php) [Okex](https://github.com/zhouaini528/okex-php) [Huobi](https://github.com/zhouaini528/huobi-php) [Binance](https://github.com/zhouaini528/binance-php). It has the advantages of supporting multiple platforms, supporting unified parameter input and output, and supporting native parameter input. Simple quantitative trading fully meets your needs. Even if you have special requirements, you can use the [getPlatform()](https://github.com/zhouaini528/exchanges-php/blob/master/README.md#the-original-object) method to return an instance and call the underlying API.

All submitted parameters and return as long as the first character for the underlined ` _ ` all for custom parameters.

Many interfaces are not yet complete, and users can continue to extend them based on my design. Welcome to improve it with me

[中文文档](https://github.com/zhouaini528/exchanges-php/blob/master/README_CN.md)

### Other exchanges API

[Bitmex](https://github.com/zhouaini528/bitmex-php) [More Tests](https://github.com/zhouaini528/bitmex-php/tree/master/tests)

[Okex](https://github.com/zhouaini528/okex-php) [More Tests](https://github.com/zhouaini528/okex-php/tree/master/tests)

[Huobi](https://github.com/zhouaini528/huobi-php) [More Tests](https://github.com/zhouaini528/huobi-php/tree/master/tests)

[Binance](https://github.com/zhouaini528/binance-php) [More Tests](https://github.com/zhouaini528/binance-php/tree/master/tests)

[Exchanges](https://github.com/zhouaini528/exchanges-php) [More Tests](https://github.com/zhouaini528/exchanges-php/tree/master/tests) This is all the aggregation

#### Install
```
composer require linwj/exchanges:dev-master

If something goes wrong add composer.json "minimum-stability":"dev"
```

#### Exchanges initialization
```php
$exchanges=new Exchanges('binance',$key,$secret);
$exchanges=new Exchanges('bitmex',$key,$secret,$host);
$exchanges=new Exchanges('okex',$key,$secret,$passphrase,$host);
$exchanges=new Exchanges('huobi',$key,$secret,$account_id,$host);
```
[Get Huobi Spot $account_id](https://github.com/zhouaini528/exchanges-php/blob/master/tests/huobi.php#L73)

#### Uniform parameter return
```php
/**
 * Buy()   Sell()   Show() Uniform parameter return
 * @return [
 *      ***Return to original data
 *      ...
 *      ...
 *      ***Returns custom data in a uniform return parameter format with '_' underscore
 *      _status=>NEW  PART_FILLED  FILLED  CANCELING  CANCELLED  FAILURE
 *      _filled_qty=>Number of transactions completed
 *      _price_avg=>Average transaction price
 *      _filed_amount=>transaction price
 *      _order_id=>system ID
 *      _client_id=>custom ID
 * ]
 *
 * */
 
 /**
 * System error
 * http request code 400 403 500 503
 * @return [
 *      _error=>[
 *          ***Return to original data
 *          ...
 *          ...
 *          ***Returns custom data in a uniform return parameter format with '_' underscore
 *          _method => POST
 *          _url => https://testnet.bitmex.com/api/v1/order
 *          _httpcode => 400
 *      ]
 * ]
 * */
```
Buy and sell query uniform parameter return [detail](https://github.com/zhouaini528/exchanges-php/blob/master/src/Api/Trader.php#L59)

System error unified parameter return [binance](https://github.com/zhouaini528/exchanges-php/blob/master/tests/binance.php#L33)
[okex](https://github.com/zhouaini528/exchanges-php/blob/master/tests/okex.php#L35)
[huobi](https://github.com/zhouaini528/exchanges-php/blob/master/tests/huobi.php#L35)
[bitmex](https://github.com/zhouaini528/exchanges-php/blob/master/tests/bitmex.php#L35)

Local development sets the proxy [More](https://github.com/zhouaini528/exchanges-php/blob/master/tests/okex.php#L53)
```php
//If you are developing locally and need an agent, you can set this
$exchanges->setProxy();

//More flexible Settings
$exchanges->setProxy([
    'http'  => 'http://127.0.0.1:12333',
    'https' => 'http://127.0.0.1:12333',
]);
```

#### Spot Trader
##### Market
```php
//binance
$exchanges->trader()->buy([
    '_symbol'=>'BTCUSDT',
    '_number'=>'0.01',
]);
//Support for original parameters
$exchanges->trader()->buy([
    'symbol'=>'BTCUSDT',
    'type'=>'MARKET',
    'quantity'=>'0.01',
]);

//okex
$exchanges->trader()->buy([
    '_symbol'=>'BTC-USDT',
    '_price'=>'10',
]);
//Support for original parameters
$exchanges->trader()->buy([
    'instrument_id'=>'btc-usdt',
    'type'=>'market',
    'notional'=>'10'
]);

//huobi
$exchanges->trader()->buy([
    '_symbol'=>'btcusdt',
    '_price'=>'10',
]);
//Support for original parameters
$exchanges->trader()->buy([
    'account-id'=>$account_id,
    'symbol'=>'btcusdt',
    'type'=>'buy-market',
    'amount'=>10
]);

```
##### Limit
```php
//binance
$exchanges->trader()->buy([
    '_symbol'=>'BTCUSDT',
    '_number'=>'0.01',
    '_price'=>'2000',
]); 
//Support for original parameters
$exchanges->trader()->buy([
    'symbol'=>'BTCUSDT',
    'type'=>'LIMIT',
    'quantity'=>'0.01',
    'price'=>'2000',
    'timeInForce'=>'GTC',
]);

//okex
$exchanges->trader()->buy([
    '_symbol'=>'BTC-USDT',
    '_number'=>'0.001',
    '_price'=>'2000',
]);
//Support for original parameters
$exchanges->trader()->buy([
    'instrument_id'=>'btc-usdt',
    'price'=>'100',
    'size'=>'0.001',
]);

//huobi
$exchanges->trader()->buy([
    '_symbol'=>'btcusdt',
    '_number'=>'0.001',
    '_price'=>'2000',
]);
//Support for original parameters
$exchanges->trader()->buy([
    'account-id'=>$account_id,
    'symbol'=>'btcusdt',
    'type'=>'buy-limit',
    'amount'=>'0.001',
    'price'=>'2001',
]);
```
#### Future Trader
##### Market
```php
//bitmex
$exchanges->trader()->buy([
    '_symbol'=>'XBTUSD',
    '_number'=>'1',
]);
//Support for original parameters
$exchanges->trader()->buy([
    'symbol'=>'XBTUSD',
    'orderQty'=>'1',
    'ordType'=>'Market',
]);

//okex
$exchanges->trader()->buy([
    '_symbol'=>'BTC-USD-190628',
    '_number'=>'1',
    '_entry'=>true,//open long
]);
//Support for original parameters
$exchanges->trader()->buy([
    'instrument_id'=>'BTC-USD-190628',
    'size'=>1,
    'type'=>1,//1:open long 2:open short 3:close long 4:close short
    //'price'=>2000,
    'leverage'=>10,//10x or 20x leverage
    'match_price' => 1,
    'order_type'=>0,
]);
```
##### Limit
```php
//bitmex
$exchanges->trader()->buy([
    '_symbol'=>'XBTUSD',
    '_number'=>'1',
    '_price'=>100
]);
//Support for original parameters
$exchanges->trader()->buy([
    'symbol'=>'XBTUSD',
    'price'=>'100',
    'orderQty'=>'1',
    'ordType'=>'Limit',
]);

//okex
$exchanges->trader()->buy([
    '_symbol'=>'BTC-USD-190628',
    '_number'=>'1',
    '_price'=>'2000',
    '_entry'=>true,//open long
]);
//Support for original parameters
$exchanges->trader()->buy([
    'instrument_id'=>'BTC-USD-190628',
    'size'=>1,
    'type'=>1,//1:open long 2:open short 3:close long 4:close short
    'price'=>2000,
    'leverage'=>10,//10x or 20x leverage
    'match_price' => 0,
    'order_type'=>0,
]);
```

#### Get Order Details
```php
//binance
$exchanges->trader()->show([
    '_symbol'=>'BTCUSDT',
    '_order_id'=>'324314658',
    //'_client_id'=>'1bc3e974577a6ad9ce730006eafb5522',
]);

//bitmex
$exchanges->trader()->show([
    '_symbol'=>'XBTUSD',
    '_order_id'=>'7d03ac2a-b24d-f48c-95f4-2628e6411927',
    //'_client_id'=>'1bc3e974577a6ad9ce730006eafb5522',
]);

//okex
$exchanges->trader()->show([
    '_symbol'=>'BTC-USDT',
    '_order_id'=>'2671215997495296',
    //'_client_id'=>'1bc3e974577a6ad9ce730006eafb5522',
]);
$exchanges->trader()->show([
    '_symbol'=>'BTC-USD-190927',
    '_order_id'=>'2671566274710528',
    //'_client_id'=>'1bc3e974577a6ad9ce730006eafb5522',
]);
$exchanges->trader()->show([
    '_symbol'=>'BTC-USD-SWAP',
    '_order_id'=>'2671566274710528',
    //'_client_id'=>'1bc3e974577a6ad9ce730006eafb5522',
]);

//huobi spot
$exchanges->trader()->show([
    '_order_id'=>'29897313869',
    //'_client_id'=>'1bc3e974577a6ad9ce730006eafb5522',
]);
//huobi future
$exchanges->trader()->show([
    '_symbol'=>'XRP190927',
    '_order_id'=>'2715696586',
    //'_client_id'=>'1bc3e974577a6ad9ce730006eafb5522',
]);

```

#### Get accounts or positions
```php
//binance
//Get current account information.
$exchanges->account()->get();

//bitmex
//bargaining transaction
$exchanges->account()->get([
    //Default return all
    //'_symbol'=>'XBTUSD'
]);

//okex  spot
//This endpoint supports getting the balance, amount available/on hold of a token in spot account.
$exchanges->account()->get([
    '_symbol'=>'BTC',
]);

//okex future
//Get the information of holding positions of a contract.
$exchanges->account()->get([
    '_symbol'=>'BTC-USD-190628',
]);

//okex swap
$exchanges->account()->get([
    '_symbol'=>'BTC-USD-SWAP',
]);

//huobi spot
$exchanges->account()->get([
    '_symbol'=>'btcusdt',
]);

//huobi future
$exchanges->account()->get([
    '_symbol'=>'BTC190927',
]);
```

#### Support for original parameters
```php
//binance
$exchanges->getPlatform()->trade()->postOrder([
    'symbol'=>'BTCUSDT',
    'side'=>'BUY',
    'type'=>'LIMIT',
    'quantity'=>'0.01',
    'price'=>'2000',
    'timeInForce'=>'GTC',
]);


//bitmex
$exchanges->getPlatform()->order()->post([
    'symbol'=>'XBTUSD',
    'price'=>'100',
    'side'=>'Buy',
    'orderQty'=>'1',
    'ordType'=>'Limit',
]);


//okex
$exchanges->getPlatform('spot')->order()->post([
    'instrument_id'=>'btc-usdt',
    'side'=>'buy',
    'price'=>'100',
    'size'=>'0.001',
    //'type'=>'market',
    //'notional'=>'100'
]);
$exchanges->getPlatform('future')->order()->post([
    'instrument_id'=>'btc-usd-190628',
    'type'=>'1',
    'price'=>'100',
    'size'=>'1',
]);
$result=$exchanges->getPlatform('swap')->order()->post([
    'instrument_id'=>'BTC-USD-SWAP',
    'type'=>'1',
    'price'=>'5000',
    'size'=>'1',
]);


//huobi
$exchanges->getPlatform('spot')->order()->postPlace([
    'account-id'=>$account_id,
    'symbol'=>'btcusdt',
    'type'=>'buy-limit',
    'amount'=>'0.001',
    'price'=>'100',
]);

$exchanges->getPlatform('future')->contract()->postOrder([
    'symbol'=>'XRP',//string    false   "BTC","ETH"...
    'contract_type'=>'quarter',//   string  false   Contract Type ("this_week": "next_week": "quarter":)
    'contract_code'=>'XRP190927',// string  false   BTC180914
    'price'=>'0.3',//   decimal true    Price
    'volume'=>'1',//    long    true    Numbers of orders (amount)
    'direction'=>'buy',//   string  true    Transaction direction
    'offset'=>'open',// string  true    "open", "close"
    'order_price_type'=>'limit',//"limit", "opponent"
    'lever_rate'=>20,//int  true    Leverage rate [if“Open”is multiple orders in 10 rate, there will be not multiple orders in 20 rate
    //'client_order_id'=>'',//long  false   Clients fill and maintain themselves, and this time must be greater than last time
]);

```

[More Tests](https://github.com/zhouaini528/exchanges-php/tree/master/tests)

[More API](https://github.com/zhouaini528/exchanges-php/tree/master/src/Api)


