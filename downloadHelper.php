<?php
/**
 *  Dowmload class
 */
 
require_once 'config.php';

class downloadHelper
{
    protected $uploadDir = APPLICATION_UPLOAD_DIR;
    
    protected $googleSheetKey = APPLICATION_GOOGLE_SHEET_KEY;
    protected $googleWgGid = APPLICATION_GOOGLE_WG_GID;
    protected $googleBmGid = APPLICATION_GOOGLE_BM_GID;
    
    public function dowmloadCsv()
    {
        $this->downloadProcess($this->googleSheetKey, $this->googleWgGid, 'wg.csv');
        $this->downloadProcess($this->googleSheetKey, $this->googleBmGid, 'bm.csv');
        return;
    }
    
    protected function downloadProcess($key, $id, $file)
    {
        $url = 'docs.google.com/feeds/download/spreadsheets/Export?key=' . $key . '&exportFormat=csv&gid=' . $id;
        $output = $this->downloadGoogleCurl($url);
        $this->parserCsv($output, $file);
        
        echo date("Y-m-d H:i:s", time()) . '  Download ' . $file . "/n";
    }
    
    protected function parserCsv($data, $file)
    {
        $output = array();
        $lines = explode(PHP_EOL, $data);
        $output = array();
        foreach ($lines as $line) {
            $output[] = str_getcsv($line);
        }
        
        $file = dirname(__FILE__) . '/data/' . $file;
        $fp = fopen($file, 'w');
        foreach($output as $data){
            fputcsv($fp, $data);
        }
        fclose($fp);
    }
    
    protected function downloadGoogleCurl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSLVERSION,3);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,3);
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64; rv:19.0) Gecko/20100101 Firefox/19.0 FirePHP/0.4");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}