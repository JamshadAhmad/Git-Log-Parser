<?php
namespace Git_Log_Parser;

/**
 * Driver gitlogparser
 * This is driver code.This code here will trigger Insights function of GitLogParser class
 * <code>
 * $obj=new GitLogParser();
 * $obj->Insights();
 * </code> 
 */

include 'gitlogparser.php';

$obj=new GitLogParser();

$obj->showInsights();

?>
