<?php

class Company{
    var $code;
    var $name;
    var $industry;
    var $marketCap;
    var $weight;
    var $bookValuePerShare;
    var $peRatio;
    var $earningsPerShare;
    var $lastPrice;
    var $cronFinancialDataLastUpdated;
    var $cronCompanyDataLastUpdated;
    var $cronPriceDataLastUpdated;
    var $currentStatus;
    var $statusLastUpdated;
    var $intrinsicValue;
    var $potential;
    var $priceToBookRatio;

    function __construct($code = NULL, $name = NULL, $industry = NULL, $marketCap = NULL, $weight = NULL, $bookValuePerShare = NULL, $peRatio = NULL, $earningsPerShare = NULL, $lastPrice = NULL, $cronFinancialDataLastUpdated = NULL, $cronCompanyDataLastUpdated = NULL, $cronPriceDataLastUpdated = NULL, $currentStatus = NULL, $statusLastUpdated = NULL, $intrinsicValue = NULL, $potential = NULL, $priceToBookRatio = NULL){
        $this->set_code($code);
        $this->set_name($name);
        $this->set_industry($industry);
        $this->set_marketCap($marketCap);
        $this->set_weight($weight);
        $this->set_bookValuePerShare($bookValuePerShare);
        $this->set_peRatio($peRatio);
        $this->set_earningsPerShare($earningsPerShare);
        $this->set_lastPrice($lastPrice);
        $this->set_cronFinancialDataLastUpdated($cronFinancialDataLastUpdated);
        $this->set_cronCompanyDataLastUpdated($cronCompanyDataLastUpdated);
        $this->set_cronPriceDataLastUpdated($cronPriceDataLastUpdated);
        $this->set_currentStatus($currentStatus);
        $this->set_statusLastUpdated($statusLastUpdated);
        $this->set_intrinsicValue($intrinsicValue);
        $this->set_potential($potential);
        $this->set_priceToBookRatio($priceToBookRatio);
    }

    function get_code(){
        return $this->code;
    }
    function get_name(){
        return $this->name;
    }
    function get_industry(){
        return $this->industry;
    }
    function get_marketCap(){
        return $this->marketCap;
    }
    function get_weight(){
        return $this->weight;
    }
    function get_bookValuePerShare(){
        return $this->bookValuePerShare;
    }
    function get_peRatio(){
        return $this->peRatio;
    }
    function get_earningsPerShare(){
        return $this->earningsPerShare;
    }
    function get_lastPrice(){
        return $this->lastPrice;
    }
    function get_cronFinancialDataLastUpdated(){
        return $this->cronFinancialDataLastUpdated;
    }
    function get_cronCompanyDataLastUpdated(){
        return $this->cronCompanyDataLastUpdated;
    }
    function get_cronPriceDataLastUpdated(){
        return $this->cronPriceDataLastUpdated;
    }
    function get_currentStatus(){
        return $this->currentStatus;
    }

    function get_statusLastUpdated(){
        return $this->statusLastUpdated;
    }
    function get_intrinsicValue(){
        return $this->intrinsicValue;
    }
    function get_potential(){
        return $this->potential;
    }
    function get_priceToBookRatio(){
        return $this->priceToBookRatio;
    }

    function set_code($code){
        $this->code = $code;
    }
    function set_name($name){
        $this->name = $name;
    }
    function set_industry($industry){
        $this->industry = $industry;
    }
    function set_marketCap($marketCap){
        $this->marketCap = $marketCap;
    }
    function set_weight($weight){
        $this->weight = $weight;
    }
    function set_bookValuePerShare($bookValuePerShare){
        $this->bookValuePerShare = $bookValuePerShare;
    }
    function set_peRatio($peRatio){
        $this->peRatio = $peRatio;
    }
    function set_earningsPerShare($earningsPerShare){
        $this->earningsPerShare = $earningsPerShare;
    }
    function set_lastPrice($lastPrice){
        $this->lastPrice = $lastPrice;
    }
    function set_cronFinancialDataLastUpdated($cronFinancialDataLastUpdated){
        $this->cronFinancialDataLastUpdated = $cronFinancialDataLastUpdated;
    }
    function set_cronCompanyDataLastUpdated($cronCompanyDataLastUpdated){
        $this->cronCompanyDataLastUpdated = $cronCompanyDataLastUpdated;
    }
    function set_cronPriceDataLastUpdated($cronPriceDataLastUpdated){
        $this->cronPriceDataLastUpdated = $cronPriceDataLastUpdated;
    }
    function set_currentStatus($currentStatus){
        $this->currentStatus = $currentStatus;
    }
    function set_statusLastUpdated($statusLastUpdated){
        $this->statusLastUpdated = $statusLastUpdated;
    }
    function set_intrinsicValue($intrinsicValue){
        $this->intrinsicValue = $intrinsicValue;
    }
    function set_potential($potential){
        $this->potential = $potential;
    }
    function set_priceToBookRatio($priceToBookRatio){
        $this->priceToBookRatio = $priceToBookRatio;
    }

    function saveCompanyData(){
        $db = new Db();

        $dateTime = date('Y-m-d H:i:s');

        $company = $db->query("
            SELECT 
                  *
            FROM `company`
            WHERE `code` = '$this->code'
        ");

        if($company === false) {
            return false;
        }

        if($company->num_rows > 0){
            $db -> query("
                UPDATE company SET
                    `code` = '$this->code', `name` = '$this->name', `industry` = '$this->industry', `market_cap` = '$this->marketCap', `weight` = '$this->weight', `cron_company_data_last_updated` = '$dateTime'
                WHERE `code` = '$this->code'
            ");
        }else{
            $db -> query("
                INSERT INTO company 
                    (`code`, `name`, `industry`, `market_cap`, `weight`) 
                VALUES 
                    ('$this->code', '$this->name', '$this->industry', '$this->marketCap', '$this->weight')
            ");
        }

        return true;
    }

    function saveCurrentStatus($status){
        $db = new Db();

        $lastUpdated = $this->statusLastUpdated;
        $dateTime = date('Y-m-d H:i:s');

        if($status != $this->currentStatus){
            $newTime = $dateTime;
        }else{
            $newTime = $lastUpdated;
        }

        $db -> query("
                UPDATE company SET
                    `current_status` = '$status', `status_last_updated` = '$newTime'
                WHERE `code` = '$this->code'
            ");

        return true;
    }

    function savePriceData($closePrice, $closeDate){
        $db = new Db();

        $db -> query("UPDATE company SET 
        `last_price` = '$closePrice',
        `cron_price_data_last_updated` = '$closeDate'
        WHERE code = '$this->code'");

        return true;
    }

    function saveFinancialData($company, $bvps, $pe, $eps){
        $db = new Db();

        $dateTime = date('Y-m-d H:i:s');

        $db -> query("UPDATE company SET 
        `book_value_per_share` = '$bvps',
        `pe_ratio` = '$pe',
        `earnings_per_share` ='$eps',
        `cron_financial_data_last_updated` = '$dateTime'
        WHERE code = '$company'");

        return true;
    }

    function saveIntrinsicValue($value){
        $db = new Db();

        $potential = number_format(((($value - $this->lastPrice) / $this->lastPrice) * 100), 2, '.', ' ');

        $db -> query("
                UPDATE company SET
                    `intrinsic_value` = '$value', `potential` = '$potential'
                WHERE `code` = '$this->code'
            ");

        return true;
    }

    function savePriceToBookRatio(){
        $db = new Db();

        $ptbr = number_format(($this->lastPrice / $this->bookValuePerShare), 2, '.', ' ');

        $db -> query("UPDATE company SET 
        `price_to_book_ratio` = '$ptbr'
        WHERE code = '$this->code'");

        return true;
    }

    function intrinsicValue($pe, $eps, $bvps, $cp){
        $value = sqrt($pe * $eps * $bvps);

        $this->saveIntrinsicValue($value);
        $this->savePriceToBookRatio();

        if($value > $cp){
            return "undervalued";
        }else{
            return "neutral/overvalued";
        }
    }

    function setCompany($code = null){

        if($code){
            $companyCode = $code;
        }else{
            $companyCode = $this->code;
        }

        $db = new Db();

        $company = $db->query("
            SELECT 
                  *
            FROM `company`
            WHERE `code` = '$companyCode'
        ");

        if($company === false) {
            return false;
        }

        while ($row = $company->fetch_assoc()) {
            $this->set_code($row['code']);
            $this->set_name($row['name']);
            $this->set_industry($row['industry']);
            $this->set_marketCap($row['market_cap']);
            $this->set_weight($row['weight']);
            $this->set_bookValuePerShare($row['book_value_per_share']);
            $this->set_peRatio($row['pe_ratio']);
            $this->set_earningsPerShare($row['earnings_per_share']);
            $this->set_lastPrice($row['last_price']);
            $this->set_cronFinancialDataLastUpdated($row['cron_financial_data_last_updated']);
            $this->set_cronCompanyDataLastUpdated($row['cron_company_data_last_updated']);
            $this->set_cronPriceDataLastUpdated($row['cron_price_data_last_updated']);
            $this->set_currentStatus($row['current_status']);
            $this->set_statusLastUpdated($row['status_last_updated']);
            $this->set_intrinsicValue($row['intrinsic_value']);
            $this->set_potential($row['potential']);
            $this->set_priceToBookRatio($row['price_to_book_ratio']);
        }

        return true;
    }


    function getCompanyList(){
        $db = new Db();

        $company = $db->query("
            SELECT 
                  *
            FROM `company`
            WHERE 1
        ");

        if($company === false) {
            return false;
        }

        $companyList = array();

        while ($row = $company->fetch_assoc()) {
            $companyList[] = $row['code'];
        }

        return $companyList;
    }

    function getUndervaluedStocks(){
        $db = new Db();

        $company = $db->query("
            SELECT 
                  *
            FROM `company`
            WHERE `current_status` = 'undervalued'
            ORDER BY `status_last_updated` DESC
        ");

        if($company === false) {
            return false;
        }

        $stockList = array();

        while ($row = $company->fetch_assoc()) {
            $stockList[] = array(
                "code" => $row['code'],
                "name" => $row['name'],
                "industry" => $row['industry'],
                "market_cap" => $row['market_cap'],
                "weight" => $row['weight'],
                "book_value_per_share" => $row['book_value_per_share'],
                "pe_ratio" => $row['pe_ratio'],
                "earnings_per_share" => $row['earnings_per_share'],
                "last_price" => $row['last_price'],
                "cron_financial_data_last_updated" => date('d/m/Y', strtotime($row['cron_financial_data_last_updated'])),
                "cron_company_data_last_updated" => date('d/m/Y', strtotime($row['cron_company_data_last_updated'])),
                "cron_price_data_last_updated" => date('d/m/Y', strtotime($row['cron_price_data_last_updated'])),
                "current_status" => $row['current_status'],
                "status_last_updated" => date('Y-m-d', strtotime($row['status_last_updated'])),
                "intrinsic_value" => $row['intrinsic_value'],
                "potential" => $row['potential'],
                "price_to_book_ratio" => $row['price_to_book_ratio']
            );
        }

        return $stockList;
    }

}