<?php
include('../classes/Db.php');
include('../classes/Company.php');

function saveLastPrice($code){
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => "https://www.asx.com.au/asx/1/share/$code/prices?interval=daily&count=1",
        CURLOPT_USERAGENT => 'ASX Data'
    ));

    $resp = curl_exec($curl);
    curl_close($curl);

    $closePrice = json_decode($resp, true)['data'][0]['close_price'];
    $closeDate = date('Y-m-d', strtotime(json_decode($resp, true)['data'][0]['close_date']));

    $company = new Company($code);
    $company->savePriceData($closePrice, $closeDate);

    return true;
}

$company = new Company();

foreach($company->getCompanyList() as $company){
    saveLastPrice($company);
}

echo "Completed.";