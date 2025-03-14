### 前言
这SDK集合了目前交易量最大的几个交易所的API，让开发人员只关注业务层。它目前只是简单的支持买卖、查询、账号余额与仓位等，后期作者会集合更多的API。

这SDK是基于[Bitmex](https://github.com/zhouaini528/bitmex-php) [Okex](https://github.com/zhouaini528/okex-php) [Huobi](https://github.com/zhouaini528/huobi-php) [Binance](https://github.com/zhouaini528/binance-php) 这些底层API再次封装，它的优点同时支持多平台，支持统一参数输入与输出，也支持原生参数输入，简单的量化交易完全满足你的需求。就算你有特殊的需求你可以单独通过该方法[getPlatform()](https://github.com/zhouaini528/exchanges-php/blob/master/README_CN.md#%E6%94%AF%E6%8C%81%E6%9B%B4%E5%BA%95%E5%B1%82api%E5%AF%B9%E8%B1%A1%E8%AF%B7%E6%B1%82)返回实例，调用底层API。

所有提交参数与返回参数只要第一个字符为下划线的"_"全部为自定义参数。

很多接口还未完善，使用者可以根据我的设计方案继续扩展，欢迎与我一起改进它。

### 其他交易所API

[Bitmex](https://github.com/zhouaini528/bitmex-php) [More Tests](https://github.com/zhouaini528/bitmex-php/tree/master/tests)

[Okex](https://github.com/zhouaini528/okex-php) [More Tests](https://github.com/zhouaini528/okex-php/tree/master/tests)

[Huobi](https://github.com/zhouaini528/huobi-php) [More Tests](https://github.com/zhouaini528/huobi-php/tree/master/tests)

[Binance](https://github.com/zhouaini528/binance-php) [More Tests](https://github.com/zhouaini528/binance-php/tree/master/tests)

[Exchanges](https://github.com/zhouaini528/exchanges-php) [More Tests](https://github.com/zhouaini528/exchanges-php/tree/master/tests)是以上所有交易所集合成的SDK

[Exchanges Tools](https://github.com/dytf/exchanges-tools) 模拟数据测试工具

#### 安装方式
```
composer require linwj/exchanges:dev-master

如果安装中出问题composer.json 添加 "minimum-stability":"dev"
```

#### 交易所初始化
```php
$exchanges=new Exchanges('binance',$key,$secret);
$exchanges=new Exchanges('bitmex',$key,$secret,$host);
$exchanges=new Exchanges('okex',$key,$secret,$passphrase,$host);
$exchanges=new Exchanges('huobi',$key,$secret,$account_id,$host);
```
[火币获取$account_id方式](https://github.com/zhouaini528/exchanges-php/blob/master/tests/huobi.php#L73)

#### 统一参数返回
```php
/**
 * Buy()   Sell()   Show() 三个方法都返回相同参数
 * @return [
 *      ***返回原始数据
 *      ...
 *      ...
 *      ***返回自定义数据，带'_'下划线的是统一返回参数格式。
 *      _status=>NEW 进行中   PART_FILLED 部分成交   FILLED 完全成交  CANCELING:撤销中   CANCELLED 已撤销   FAILURE 下单失败
 *      _filled_qty=>已交易完成数量
 *      _price_avg=>成交均价
 *      _filed_amount=>交易价格
 *      _order_id=>系统ID
 *      _client_id=>自定义ID
 * ]
 *
 * */
 
 /**
 * 系统错误
 * http request code 400 403 500 503
 * @return [
 *      _error=>[
 *          ***返回原始数据
 *          ...
 *          ...
 *          ***返回自定义数据，带'_'下划线的是统一返回参数格式。
 *          _method => POST
 *          _url => https://testnet.bitmex.com/api/v1/order
 *          _httpcode => 400
 *      ]
 * ]
 * */
```
Buy Sell 方法默认有[2秒](https://github.com/zhouaini528/exchanges-php/blob/master/src/Config/Exchanges.php)的等待查询，因为交易所是撮合交易所以查询需要等待。

买卖查询统一参数返回 [详情](https://github.com/zhouaini528/exchanges-php/blob/master/src/Api/Trader.php#L36)

系统错误统一参数返回 
[binance](https://github.com/zhouaini528/exchanges-php/blob/master/tests/binance.php#L33)
[okex](https://github.com/zhouaini528/exchanges-php/blob/master/tests/okex.php#L35)
[huobi](https://github.com/zhouaini528/exchanges-php/blob/master/tests/huobi.php#L35)
[bitmex](https://github.com/zhouaini528/exchanges-php/blob/master/tests/bitmex.php#L35)

支持本地开发代理设置 [More](https://github.com/zhouaini528/exchanges-php/blob/master/tests/okex.php#L53)
```php
//如果您正在本地开发需要代理，您可以这样设置
$exchanges->setProxy();

//更灵活的代理设置
$exchanges->setProxy([
    'http'  => 'http://127.0.0.1:12333',
    'https' => 'http://127.0.0.1:12333',
]);
```

#### 现货交易
##### 市价交易
```php
//binance
//统一提交参数
$exchanges->trader()->buy([
    '_symbol'=>'BTCUSDT',
    '_number'=>'0.01',
]);
//也支持原生参数，与上等同
$exchanges->trader()->buy([
    'symbol'=>'BTCUSDT',
    'type'=>'MARKET',
    'quantity'=>'0.01',
]);

//okex
//统一提交参数
$exchanges->trader()->buy([
    '_symbol'=>'BTC-USDT',
    '_price'=>'10',
]);
//也支持原生参数，与上等同
$exchanges->trader()->buy([
    'instrument_id'=>'btc-usdt',
    'type'=>'market',
    'notional'=>'10'
]);

//huobi
//统一提交参数
$exchanges->trader()->buy([
    '_symbol'=>'btcusdt',
    '_price'=>'10',
]);
//也支持原生参数，与上等同
$exchanges->trader()->buy([
    'account-id'=>$account_id,
    'symbol'=>'btcusdt',
    'type'=>'buy-market',
    'amount'=>10
]);

```
##### 限价交易
```php
//binance
//统一提交参数
$exchanges->trader()->buy([
    '_symbol'=>'BTCUSDT',
    '_number'=>'0.01',
    '_price'=>'2000',
]); 
//也支持原生参数，与上等同
$exchanges->trader()->buy([
    'symbol'=>'BTCUSDT',
    'type'=>'LIMIT',
    'quantity'=>'0.01',
    'price'=>'2000',
    'timeInForce'=>'GTC',
]);

//okex
//统一提交参数
$exchanges->trader()->buy([
    '_symbol'=>'BTC-USDT',
    '_number'=>'0.001',
    '_price'=>'2000',
]);
//也支持原生参数，与上等同
$exchanges->trader()->buy([
    'instrument_id'=>'btc-usdt',
    'price'=>'100',
    'size'=>'0.001',
]);

//huobi
//统一提交参数
$exchanges->trader()->buy([
    '_symbol'=>'btcusdt',
    '_number'=>'0.001',
    '_price'=>'2000',
]);
//也支持原生参数，与上等同
$exchanges->trader()->buy([
    'account-id'=>$account_id,
    'symbol'=>'btcusdt',
    'type'=>'buy-limit',
    'amount'=>'0.001',
    'price'=>'2001',
]);
```
#### 期货交易
##### 市价交易
```php
//bitmex
//统一提交参数
$exchanges->trader()->buy([
    '_symbol'=>'XBTUSD',
    '_number'=>'1',
]);
//也支持原生参数，与上等同
$exchanges->trader()->buy([
    'symbol'=>'XBTUSD',
    'orderQty'=>'1',
    'ordType'=>'Market',
]);

//okex
//统一提交参数
$exchanges->trader()->buy([
    '_symbol'=>'BTC-USD-190628',
    '_number'=>'1',
    '_entry'=>true,//open long
]);
//也支持原生参数，与上等同
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
##### 限价交易
```php
//bitmex
//统一提交参数
$exchanges->trader()->buy([
    '_symbol'=>'XBTUSD',
    '_number'=>'1',
    '_price'=>100
]);
//也支持原生参数，与上等同
$exchanges->trader()->buy([
    'symbol'=>'XBTUSD',
    'price'=>'100',
    'orderQty'=>'1',
    'ordType'=>'Limit',
]);

//okex
//统一提交参数
$exchanges->trader()->buy([
    '_symbol'=>'BTC-USD-190628',
    '_number'=>'1',
    '_price'=>'2000',
    '_entry'=>true,//open long
]);
//也支持原生参数，与上等同
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

#### 订单查询
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

#### 账号余额与仓位获取
```php
//binance
//Get current account information.
$exchanges->account()->get();

//bitmex
//bargaining transaction
$exchanges->account()->get([
    //不填写默认返回所有仓位
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

#### 支持更底层API对象请求
使用前建议先去看看这些[Bitmex](https://github.com/zhouaini528/bitmex-php) [Okex](https://github.com/zhouaini528/okex-php) [Huobi](https://github.com/zhouaini528/huobi-php) [Binance](https://github.com/zhouaini528/binance-php)底层已经封装过的SDK。

以下是调用底层API的发起一个新的订单实例
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


更多用例请查看 [more](https://github.com/zhouaini528/exchanges-php/tree/master/tests)

更多API请查看 [more](https://github.com/zhouaini528/exchanges-php/tree/master/src/Api)


