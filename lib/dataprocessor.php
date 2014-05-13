<?php 

class dataprocessor{
  
  //Set datafile variable
  private $datafile;
    
    //initialize class and pass datafile path
    function __construct($datafile = "../data/datafile"){
        $this->datafile = $datafile;
        //check if the file exists
        if(file_exists($this->datafile)){
            //proccess the data in the file if file exists
            $this->processdata();
        }else{
            //show message if file does not exist
            echo 'The file does not exist';
        }
        
    }
    
    private function processdata(){
        //open the datafile in read only form
        $file = fopen($this->datafile,'r');
        //set all counter to 0
        $c = 0;
        $start = 0;
        $end = 0;
        //create empty array variables for the csv 
        $data = array();
        //Create array to rearrage the csv array 
        $rdata = array();
        //open/create new file to store csv
        $newfile = fopen('data/csv'.rand(11,98).'.csv','w');
        //open/create new file to store formated data
        $newdatafile = fopen('data/datafile'.rand(11,98).'.txt','w');
        //loop each line of thedatafile
        while(!feof($file)){
            //get a line from the datafile
            $line = fgets($file);
            //split the sting where 'ITEMS ON RESERVE FOR' exists
            $splitfline = explode('ITEMS ON RESERVE FOR',$line);
            //count the number of variables in the splited line array
            $cntf = count($splitfline);
            //split the string anywhere that there is a space
            $splitbline = explode(' ',$line);
            //count the number of variable in the splited string array
            $cntb = count($splitbline);
            if($cntf > 1){
                //if this line is an header, set the end counter to the number of lines of the header and other counters to 0
                $end = 5;
                $start = 0;
                $c = 0;
                
            }elseif($cntb == 1){
                //if this line is an blank/empty, set the end counter to 0 and the start counter to 3,(the number of lines per data) and write it to file
                $end = 0;
                $start = 3;
                fwrite($newdatafile,$line);
            }else{
                //process all lines that is not blank
                if($end > 0){
                    //reduce the end counter by 1
                    $end = $end - 1;
                    //split the string by 12 spaces
                    $splitstring = explode('            ',$line);
                    //filter the array to remove all empty array elements
                    $filter = array_filter($splitstring);
                    //Write the data to datafile
                    fwrite($newdatafile,trim($filter[1])."\n");
                    //if the end counter is 0, set start counter to 3
                    if($end == 0){
                        $start = 3;
                    }
                }
                //process the data if start counter is greater than 0
                if($start > 0){
                    //reduce start counter by one
                        $start = $start - 1;
                        //split the string where there is two spaces
                        $splitstring2 = explode('  ',$line);
                        //filter the resulting array to remove empty elements
                        $filter2 = array_filter($splitstring2);
                        //rearrange the array keys numerically
                        $fine = array_values($filter2);
                        
                        if($start == 2){
                            //get first line and write it to file
                            $data[$c]['title'] = trim($fine[0]);
                            $data[$c]['author'] = trim($fine[1]);
                            fwrite($newdatafile,$line);
                        }
                        if($start == 1){
                            //get second line and write it to file
                            $data[$c]['shelfmark'] = trim($fine[0]);
                            $data[$c]['location code'] = trim($fine[1]);
                            fwrite($newdatafile,$line);
                        }
                        if($start == 0){
                            //get third line and write it to file
                            $data[$c]['barcode'] = trim($fine[0]);
                            $data[$c]['checkouts'] = trim($fine[1]);
                            $data[$c]['location description'] = trim($fine[2]);
                            $data[$c]['running number'] = $c + 1;
                            $c++;
                            fwrite($newdatafile,$line);
                        }
                }
            }
            
            
        }
        //rearrange data to the requested format and save to the rdata array
        foreach($data as $key => $value){
            $rdata[$key]['Running number'] = $data[$key]['running number'];
            $rdata[$key]['Title'] = $data[$key]['title'];
            $rdata[$key]['Author'] = $data[$key]['author'];
            $rdata[$key]['Shelfmark'] = $data[$key]['shelfmark'];
            $rdata[$key]['Barcode'] = $data[$key]['barcode'];
            $rdata[$key]['Location code'] = $data[$key]['location code'];
            $rdata[$key]['Location description'] = $data[$key]['location description'];
            $rdata[$key]['Checkouts'] = $data[$key]['checkouts'];
        }
        //write processed data to csv
        fputcsv($newfile,array_keys($rdata[0]));
        foreach($rdata as $value){
            fputcsv($newfile,$value);
            }
        fclose($newdatafile);
        fclose($newfile);
        fclose($file);
        
        
    }
    
    
    
}

?>