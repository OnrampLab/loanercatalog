<?php
/**
 *  Dowmload class
 */
 
require_once 'config.php';

class parserHelper
{
    protected $filterRange = APPLICATION_FILTER_RANGE;
    
    public function loadCsv($file)
    {
        $rows = array_map('str_getcsv', file($file));
        $header = array_shift($rows);
        $output = array();
        foreach ($rows as $row) {
            $output[] = array_combine($header, $row);
        }
        return $output;
    }
    
    public function filterDress($dresses, $bust = null, $waist = null, $hips = null, $hh = null, $closest = null)
    {   
        $output = array();
        foreach ($dresses as $data){
            if($data['bust'] <= $bust)
                continue;
            if($data['waist'] <= $waist)
                continue;
            if($data['hips'] <= $hips)
                continue;
            if($data['H-H'] <= $hh)
                continue;
            if(isset($closest) && $closest != null){
                if($data['closest size'] <= $closest)
                    continue;
            }
            
            //Sort key
            $data['sortkey'] = $data['bust'] - $bust + $data['waist'] - $waist + $data['hips'] - $hips + $data['H-H'] - $hh;
            array_push($output, $data);
        }
        return $output;
    }
    
    public function sortDress($dresses)
    {
        $sortary = array();
        foreach ($dresses as $data){
            array_push($sortary, $data['sortkey']);
        }
        array_multisort($sortary, $dresses);
        return $dresses;
    }
}