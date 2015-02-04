<?php
#!/usr/bin/php -q
ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);
ini_set('memory_limit', '2048M');
ini_set('max_execution_time', 1200);

require_once 'downloadHelper.php';
require_once 'parserHelper.php';
require_once 'config.php';

router();


function router()
{
    $output = '';
    $rendObj = new stdClass;
    $rendObj->page = $_POST['page'];
    
    if($_GET['action'] == 'download'){
        $downloadHelper = new downloadHelper();
        $downloadHelper->dowmloadCsv();
        return;
    }
    
    switch ($_POST['page']){
        case 'wg':
            $parserHelper = new parserHelper();
            $rendObj->dresses = $parserHelper->loadCsv(dirname(__FILE__) . '/data/wg.csv');
            $rendObj->dresses = $parserHelper->filterDress($rendObj->dresses, $_POST['bust'], $_POST['waist'], $_POST['hips'], $_POST['H-H']);
            $rendObj->dresses = $parserHelper->sortDress($rendObj->dresses);
            $rendObj->post = $_POST;
            break;
            
        case 'bm':
            $parserHelper = new parserHelper();
            $rendObj->dresses = $parserHelper->loadCsv(dirname(__FILE__) . '/data/bm.csv');
            $rendObj->dresses = $parserHelper->filterDress($rendObj->dresses, $_POST['bust'], $_POST['waist'], $_POST['hips'], $_POST['H-H'], $_POST['closest']);
            $rendObj->dresses = $parserHelper->sortDress($rendObj->dresses);
            $rendObj->post = $_POST;
            break;
        
        default:
            $rendObj->page = 'default';
    }
    render($rendObj);
}

function render($rendObj) 
{ 
    ob_start(); 
    include 'template_header.phtml';
    if($rendObj->page == 'wg')
        include 'template_wg.phtml';
    else if($rendObj->page == 'bm')
        include 'template_bm.phtml';
    include 'template_footer.phtml';    
    return ob_end_flush(); 
} 