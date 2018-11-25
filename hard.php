<?php
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_PORT => "8080",
    CURLOPT_URL => "http://192.168.190.131:8080/user-tracking-service/report/get",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "{\r\n\t\"loginTimeFrom\": 1542794400000,\r\n\t\"loginTimeTo\":1542808800000\r\n}",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/json",
        "postman-token: 059608f5-fe36-02cf-53a8-7756f5b92d29"
    ),
));

$response = json_decode(curl_exec($curl));
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    foreach ($response as $item) {
        foreach ($item as $test) {
            $ipaddress = false;
            $member = $test->lcMemberId . "<br>";
            $events = $test->events;
            foreach ($events as $event) {
                if ($event->ip == "91.213.84.56") {
                    $ipaddress = true;
                }
            }
            if ($ipaddress) {
                var_dump($test);
            }
        }
    }
}