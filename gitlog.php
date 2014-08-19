<?php
namespace Git_Log_Parser;

/**
 * Driver code for Git-Log-Parser class.
 * 
 * Triggers showInsights method of GitLogParser class
 * 
 */

$recievedArgument =null;

if($argc > 1)
{
    $recievedArgument=$argv[1];
}

include 'gitlogparser.php';

$parser=new GitLogParser();
$parser->showInsights($recievedArgument);

