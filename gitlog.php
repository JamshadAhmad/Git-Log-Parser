<?php
namespace Git_Log_Parser;

/**
 * Driver code for Git-Log-Parser class.
 * 
 * This is driver code.This code here will trigger Insights function of
 * GitLogParser class.
 * 
 */
$arg =null;
if($argc > 1)
{
    $arg=$argv[1];
}
include 'gitlogparser.php';
$obj=new GitLogParser();
$obj->showInsights($arg);
?>

