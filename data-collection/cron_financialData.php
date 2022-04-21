<?php
include('../classes/Db.php');
include('../classes/Company.php');
include('../data-collection/simple_html_dom.php');

function scrapeBVP($code){
    if(@get_headers("https://www.wsj.com/market-data/quotes/AU/XASX/$code/financials")[0] == 'HTTP/1.1 200 OK' || @get_headers("https://www.wsj.com/market-data/quotes/AU/XASX/$code/financials")[18] == 'HTTP/1.1 200 OK'){
        $html = file_get_html("https://www.wsj.com/market-data/quotes/AU/XASX/$code/financials");
        foreach ($html->find('div.cr_income_data') as $ul) {
            if (strpos($ul, 'Book Value Per Share') == true) {
                $st1 = explode("Book Value Per Share ", Strip_tags($ul->find('tr', -1)));
                $st2 = explode(" -", $st1[1]);
                $bvp = floatval(trim($st2[0]));
            }
        }
        if(is_numeric($bvp)) {
            return $bvp;
        }else{
            return null;
        }
    }else{
        return null;
    }
}
function scrapePE($code){
    if(@get_headers("https://www.wsj.com/market-data/quotes/AU/XASX/$code/financials")[0] == 'HTTP/1.1 200 OK' || @get_headers("https://www.wsj.com/market-data/quotes/AU/XASX/$code/financials")[18] == 'HTTP/1.1 200 OK'){
        $html = file_get_html("https://www.wsj.com/market-data/quotes/AU/XASX/$code/financials");
        foreach ($html->find('table.cr_sub_valuation') as $ul) {
            if (strpos($ul, 'P/E Ratio') == true) {
                $st1 = explode("P/E Ratio ", Strip_tags($ul->find('tr', 0)));
                $st2 = explode("(TTM) ", $st1[1]);
                $pe_ratio = floatval(trim($st2[1]));
            }
        }
        if(is_numeric($pe_ratio)) {
            return $pe_ratio;
        }else{
            return null;
        }
    }else{
        return null;
    }
}
function scrapeEPS($code){
    if(@get_headers("https://www.wsj.com/market-data/quotes/AU/XASX/$code/financials")[0] == 'HTTP/1.1 200 OK' || @get_headers("https://www.wsj.com/market-data/quotes/AU/XASX/$code/financials")[18] == 'HTTP/1.1 200 OK'){
        $html = file_get_html("https://www.wsj.com/market-data/quotes/AU/XASX/$code/financials");
        foreach ($html->find('table.cr_sub_valuation') as $ul) {
            if (strpos($ul, 'EPS') == true) {
                $st1 = explode("EPS ", Strip_tags($ul->find('tr', -1)));
                $st2 = explode("(diluted) ", $st1[1]);
                $eps = floatval(trim($st2[1]));
            }
        }
        if(is_numeric($eps)) {
            return $eps;
        }else{
            return null;
        }
    }else{
        return null;
    }
}

$company = new Company();
$companyList = $company->getCompanyList();

$scrapeGroup = [];
for ($x = 1; $x <= 20; $x++) {
    $scrapeGroup[$x] = array_splice($companyList,0,10);
}

$groupToScrape = intval(date('d'));

foreach($scrapeGroup[$groupToScrape] as $company){

    $c = new Company();

    $bvps = scrapeBVP($company);
    $pe = scrapePE($company);
    $eps = scrapeEPS($company);

    $c = new Company($company);
    $c->saveFinancialData($company, $bvps, $pe, $eps);
}

echo "Completed.";