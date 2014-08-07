<?php


/**
 * Description of gitlogparser
 * 
 * @package Git-Log-Parser
 * @author Jamshad Ahmad
 * @version 1.0
 * 
 */
class GitLogParser {
    
    /**
     * @var string dir for path to the directory
     */
    public $dir = "~/repos/Coeus-Tasks";//Default Path
    
    
    /**
     * Constructor
     * This constructor is to initilize path to local repository
     */
    
    public function GitLogParser() {
       
    }
    
    /**
     * Insights
     *
     * This is the function which gets git log and parse it into some usefull insights.
     *
     * @param this function not receiving any parameter
     * @return this function is not returning anything
     */
    public function Insights() {
        
        //chdir($this->dir);
        
        $output = array();
        $U_Authors = array();
        $T_Authors = array();
        $commit_count = 0;
        exec("cd $this->dir; git log ",$output);
        
        foreach($output as $line){
            if(strpos($line, 'Author')===0){ //only if line starts from Author
                $commit_count=$commit_count+1;
                
                if(!empty($line)){
                    $tmp = array();
                    $tmp = explode(":", $line); //spliting
                    $tmp2 = explode("<", $tmp[1]);
                    $tmp2[0]= trim($tmp2[0]);
                    array_push($T_Authors, $tmp2[0]);
                    if($this->is_in($U_Authors, $tmp2[0])===0){ //only if already not added
                        array_push($U_Authors, $tmp2[0]); //Here tmp2[0] contains email address
                    }
                    
                }
                
            }
        }
        echo "\nTotal commits by all users :  $commit_count\n\n";
        echo "Here is the contributors list\n\n";
        foreach ($U_Authors as $value) {
            echo "$value  || Commit Count: ".$this->occ_count($T_Authors, $value)."  ||  Contrib. : ".round(($this->occ_count($T_Authors, $value))*100/($commit_count),2)."%\n";
        }
        echo "\n";
       
    }
    /**
     * dump
     *
     * This will dump git log saperated by commits and with new format. I found it online 
     * its original author is Ngo Minh Nam but now it is little edited
     *
     * @param this function not receiving any parameter
     * @return this function is not returning anything
     */
    function dump(){
        $output=array();
        exec("cd $this->dir; git log ",$output);
        $history = array();
        foreach($output as $line){
            if(strpos($line, 'commit')===0){
                if(!empty($commit)){
                    array_push($history, $commit);	
                    unset($commit);
                }
                $commit['hash']   = substr($line, strlen('commit'));
            }
            else if(strpos($line, 'Author')===0){
                $commit['author'] = substr($line, strlen('Author:'));
            }
            else if(strpos($line, 'Date')===0){
                $commit['date']   = substr($line, strlen('Date:'));
            }
            else{		
                $commit['message']  .= $line;
            }
        }

        print_r($history);
    }
    /**
     * is_in
     *
     * This function searches a particular key from an array and returns 1 if it is present.
     *
     * @param this function recieves $array and a $str which is a key to search.
     * @return this function returns either 1 or 0
     */
    function is_in($array,$str){
        
        foreach($array as $line){
            if($line==$str){
                return 1;
            }
        }
        return 0;
    }
    /**
     * is_in
     *
     * This function searches a particular key from an array and returns its count.
     *
     * @param this function recieves $array and a $str which is a key to search.
     * @return this function returns counted integer which is from 0 to integer limit.
     */
    function occ_count($array,$str){
        $count=0;
        foreach($array as $line){
            if($line==$str){
                $count++;
            }
        }
        return $count;
    }
    
    
}

?>
