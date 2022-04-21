<?php
include('../classes/Db.php');
include('../classes/Company.php');

$company = new Company();
$companyList = $company->getCompanyList();

foreach($companyList as $c){
    $company = new Company($c);
    $company->setCompany();

    $intrinsicValue = $company->intrinsicValue($company->peRatio, $company->earningsPerShare, $company->bookValuePerShare, $company->lastPrice);

    if($intrinsicValue == 'undervalued'){
        $company->saveCurrentStatus('undervalued');
    }else{
        $company->saveCurrentStatus('');
    }
}

echo "Completed.";