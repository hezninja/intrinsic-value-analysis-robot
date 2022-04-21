<?php
include('../classes/Db.php');
include('../classes/Company.php');

$date = date('Ymd');
$csvLocation = "https://www.asx200list.com/uploads/csv/$date-asx200.csv";

$ch = curl_init($csvLocation);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_exec($ch);
$fileStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if($fileStatus == "200"){

    $csv = fopen($csvLocation, 'r');
    $listing = array();
    $row = 1;

    while (($line = fgetcsv($csv)) !== FALSE) {
        if($row > 2){
            $listing[] = array("code" => $line[0],"name" => $line[1],"industry" => $line[2],"market_cap" => intval(str_replace(",","",$line[3])),"weight" => floatval($line[4]));
        }
        $row++;
    }
    fclose($csv);

    foreach($listing as $key => $val){
        $company = new Company($val['code'], $val['name'], $val['industry'], $val['market_cap'], $val['weight']);
        $company->saveCompanyData();
    }

    echo "Completed.";
}else {
    echo "CSV Location does not exist. Did not update.";
}