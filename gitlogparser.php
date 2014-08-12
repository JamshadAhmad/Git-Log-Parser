<?php
namespace Git_Log_Parser;

/**
 * Git_Log_Parser can be used to extract extra info from git log.
 * 
 * This script can be used to parse Git Log and get some useful info out of it.
 * It takes Git Log and extracts information about contributors and also 
 * shows git log in  better format
 * 
 * @package Git-Log-Parser
 * @author  Jamshad Ahmad
 * @version 2.0
 * 
 */

class GitLogParser
{
    
    /**
     * @var string dir for path to the directory
     */
    public $dir = "~/repos/Coeus-Tasks";//Default Path if you don't give any,you can set yours
    
    
    /**
     * Shows info about contributors
     * 
     * This is the function which gets git log and parse it into some usefull insights.It uses 
     * two other helper functions.
     * 
     * @access public
     *
     */
    public function showInsights()
    {
        
        //chdir($this->dir); //this command gives warning sometimes
        echo "Please enter the path of your local repository:\n";

        $this->dir = trim(fread(STDIN, 80));
        
        $output = array();
        $U_Authors = array();
        $T_Authors = array();
        $commit_count = 0;
        exec("cd $this->dir; git log ",$output);
        
        foreach($output as $line)
        {
            if(strpos($line, 'Author')===0)
            { //only if line starts from Author
                $commit_count=$commit_count+1;
                //Here starts parsing magic
                if(!empty($line))
                {
                    $tmp = array();
                    $tmp = explode(":", $line); //spliting
                    $tmp2 = explode("<", $tmp[1]);
                    $tmp2[0]= trim($tmp2[0]);
                    array_push($T_Authors, $tmp2[0]);
                    if($this->isIn($U_Authors, $tmp2[0])===0)
                    { //only if already not added
                        array_push($U_Authors, $tmp2[0]); //Here tmp2[0] contains email address
                    }
                    
                }
                
            }
        }
        echo "\nTotal commits by all users :  $commit_count\n\n";
        if($commit_count===0)
        {
            return;//No need to show contribution list
        }
        echo "Here is the contributors list\n\n";
        foreach ($U_Authors as $value)
        {
            printf("%-33s  ",$value);
            echo " || Commit Count: ";
            printf("%3d",$this->occCount($T_Authors, $value));
            echo "  ||  Contrib. : ";
            printf("%02.2f",($this->occCount($T_Authors, $value))*100/($commit_count));
            echo "%\n";
        }
        /*//Uncomment this code to show git log with insights
        echo "\nEnter 'S' to show all commits or any other key to exit : ";
        $c = fgetc(STDIN);
        if($c==='s' || $c==='S'){
            $this->gitLog();
        }
        */
        
    }
    
    /**
     * Shows git log in new format
     *
     * This will dump git log saperated by commits and with new format. I found it on 
     * github. Its original author is Ngo Minh Nam but now it is little edited
     *
     */
    function gitLog()
    {
        $output=array();
        exec("cd $this->dir; git log ",$output);
        
        $history = array();
        foreach($output as $line)
        {
            if(strpos($line, 'commit')===0)
            {
                if(!empty($commit))
                {
                    array_push($history, $commit);	
                    unset($commit);
                }
                $commit['Hash']   = substr($line, strlen('commit'));
            }
            else if(strpos($line, 'Author')===0)
            {
                $commit['Author'] = substr($line, strlen('Author:'));
            }
            else if(strpos($line, 'Date')===0)
            {
                $commit['Date']   = substr($line, strlen('Date:'));
            }
            else
            {		
                $commit['Message']  .= $line;
            }
        }

        print_r($history);
        echo "\n";
    }
    
    /**
     * Returns true if element is in the container
     * 
     * Searches a particular key from an array and returns 1 if it is present.
     *
     * @param       this function recieves $array and a $str which is a key to search.
     * @return bool this function returns either 1 or 0.
     */
    function isIn($array,$str)
    {
        
        foreach($array as $line)
        {
            if($line==$str)
            {
                return 1;
            }
        }
        return 0;
    }
    
    /**
     * Returns count of occurences.
     *
     * This function searches a particular key from an array and returns its count.
     *
     * @param          this function recieves $array and a $str which is a key to search.
     * @return integer this function returns counted integer which is from 0 to integer limit.
     */
    function occCount($array,$str)
    {
        $count=0;
        foreach($array as $line)
        {
            if($line==$str)
            {
                $count++;
            }
        }
        return $count;
    }
    
    
}

?>
