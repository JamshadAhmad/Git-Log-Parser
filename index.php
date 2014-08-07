<?php

/**
 * Description of GitLogParser
 * 
 * this file is main/startup file which uses GitLogParser.php
 * @package Git-Log-Parser
 * @author Jamshad Ahmad
 * @version 1.0
 * 
 */
 
include_once 'GitLogParser.php';

$obj=new GitLogParser();

$obj->Insights();
 
?>