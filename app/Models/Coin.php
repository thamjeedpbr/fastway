<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    use HasFactory;

    public static function collect()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.coingecko.com/api/v3/coins/list?include_platform=true',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cookie: __cf_bm=IgioVKRW7LQHhB6rGBT7hAP9mXuyoX1mNSzh5sKSXBM-1686747173-0-AUB+sK5DJmIBB1a/qEiaxWhj/hhU9qb9kfnjaxzDkJO4biKCmwiM2MDkbVSKBj1SaRlMLh5OEUABj0KZ85mPdrA=',
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $received_coins = json_decode($response);

        foreach ($received_coins as $no => $received_coin) {
            dump(++$no ." coin added with id-".$received_coin->id);
            $collect_coin = new Coin();
            $collect_coin->coingecko_id = $received_coin->id;
            $collect_coin->symbol = $received_coin->symbol;
            $collect_coin->name = $received_coin->name;
            if ($collect_coin->save()) {
                foreach ((array) $received_coin->platforms as $key => $platform) {
                    $wallet = new CoinWallet();
                    $wallet->coin_id = $collect_coin->id;
                    $wallet->wallet_name = $key;
                    $wallet->wallet_address = $platform;
                    $wallet->save();
                }
            }
        }
        dump('Collected All ' . $no . ' Coins');
    }
}
