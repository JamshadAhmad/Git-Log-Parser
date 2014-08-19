<?php
namespace Git_Log_Parser;

/**
 * Git_Log_Parser can be used to extract extra info from git log.
 * 
 * Parses Git Log and get some useful info out of it.
 * Takes Git Log and extracts information about contributors and also 
 * shows git log in  better format
 * 
 * @package Git-Log-Parser
 * @author  Jamshad Ahmad
 * @version 3.0
 * 
 */
class GitLogParser
{
    /**
     * @var string dir for path to the directory
     */
    public $dir;
    
    /**
     * Shows info about contributors
     * 
     * Gets git log and parse it into some usefull insights.It uses 
     * two other helper functions.
     * 
     * @access public
     *
     */
    public function showInsights($arg)
    {
        $forceShow = false;
        $isPathProvided = false;
        
        if($arg!=null){
            if($arg=="--help"){
                echo "You reached the help section of gitlog";
                echo "\n\n";
                echo "Running command without any argument shows Git Insights";
                echo "\n";
                echo "then it will ask for an input to show Git Log";
                echo "\n\n";
                echo "Running command with -s argument will show Git Log";
                echo "\n";
                echo "without asking for any additional input";
                echo "\n";
                echo "eg. :> php gitlog.php -s";
                echo "\n\n";
		echo "You can provide path infront of the command";
		echo "\n";
		echo "eg. :> php gitlog.php /path/to/your/repository";
		echo "\n\n";
                
                return;
            }
            else if($arg=="-s"){
                echo "Autoshow of Commit Log is Enabled";
                echo "\n";
                
                $forceShow = true;
            }
            else if(strpos($arg, '/') !== false){
                $isPathProvided = true;
                
                $this->dir = trim($arg);
            }
            else{
                echo "$arg is Not a valid command argument, try --help";
                echo "\n";
                
                return;
            }
        }
        
        if($isPathProvided==false){
            echo "Please enter the path of your local repository:";
            echo "\n";
            
            $this->dir = realpath(trim(fread(STDIN, 80)));
            
            while (!is_dir($this->dir)) {
                echo "$this->dir is not a valid directory";
                echo "\n";
                echo "Please enter absolute path:";
                echo "\n";
                
                $this->dir = realpath(trim(fread(STDIN, 80)));
            }
        }
        
        $output = array();
        $uniqueAuthors = array();
        $totalAuthors = array();
        $commitCount = 0;
        
        exec("cd $this->dir; git log ",$output);
        
        foreach($output as $line){
            if(strpos($line, 'Author')===0){ 
                $commitCount++;
                //Here starts parsing magic
                if(!empty($line)){
                    $tmp = explode(":", $line);
                    $tmp2 = explode("<", $tmp[1]);
                    $tmp2[0]= trim($tmp2[0]);
                    
                    array_push($totalAuthors, $tmp2[0]); //tmp2[0] contains Author name
                    
                    if(!in_array($tmp2[0],$uniqueAuthors)){ //only if already not added
                        array_push($uniqueAuthors, $tmp2[0]); 
                    }
                }
            }
        }
        
        if($commitCount===0){
            echo "You don't have any commits yet";
            echo "\n";
            echo "Make sure that you're providing valid git directory";
            echo "\n";
            
            return;//No need to show contribution list
        }
        
        echo "\nTotal commits by all users :  $commitCount";
        echo "\n\n";
        
        echo "Here is the contributors list\n\n";
        
        foreach ($uniqueAuthors as $value){
            printf("%-33s  ",$value);
            echo " || Commit Count: ";
            printf("%3d",$this->occCount($totalAuthors, $value));
            echo "  ||  Contrib. : ";
            printf("%02.2f",($this->occCount($totalAuthors, $value))*100/($commitCount));
            echo "%\n";
        }
        
        if($forceShow==true){
            $this->printGitLog();
        }
        else{
            echo "\nEnter 'S' to show all commits or any other key to exit : ";
            
            $c = fgetc(STDIN);
            
            if(strtolower($c) === 's'){
                $this->printGitLog();
            }
        }
    }
    
    /**
     * Shows git log in new format
     *
     * Shows git log saperated by commits and with new format. Found it on 
     * github. Its original author is Ngo Minh Nam but now it is little edited
     *
     */
    public function printGitLog()
    {
        $output=array();
        $history = array();
        
        exec("cd $this->dir; git log ",$output);
        
        foreach($output as $line){
            if(strpos($line, 'commit')===0){
                if(!empty($commit)){
                    array_push($history, $commit);
                    
                    unset($commit);
                }
                
                $commit['Hash']   = substr($line, strlen('commit'));
            }
            else if(strpos($line, 'Author')===0){
                $commit['Author'] = substr($line, strlen('Author:'));
            }
            else if(strpos($line, 'Date')===0){
                $commit['Date']   = substr($line, strlen('Date:'));
            }
            else if(strlen($line)>1){
                $commit['Message']  = $line;
            }
        }
        
        print_r($history);
        echo "\n";
    }
    
    /**
     * Returns count of occurences.
     *
     * This function searches a particular key from an array and returns its count.
     *
     * @param  string  recieves $array and a $str which is a key to search.
     * 
     * @return integer returns counted integer which is from 0 to integer limit.
     */
    protected function occCount($array,$str)
    {
        $count=0;
        
        foreach($array as $line){
            if($line==$str){
                $count++;
            }
        }
        
        return $count;
    }
}
