<?php
require 'config.php';

/**
 * search pattern in table fields
 */
function search_table($db_name, $table_name, $pattern)
{
    global $link;
 
    $result = mysql_query("SHOW COLUMNS FROM $table_name");
    if (!$result) {
        echo 'Could not run query: ' . mysql_error();
        return false;
    }
    if (mysql_num_rows($result) > 0) {

        $matched = array();
        while ($row = mysql_fetch_assoc($result)) {
            if (strstr($row['Field'], $pattern)) $matched[] = $row['Field'];
        }
        if (count($matched)) {
            echo "searching in db $db_name, table $table_name\n";
            foreach($matched as $t) echo "\tmatched in field : ",$t,"\n";
            echo "\n";
        }
    }
}

/**
 * search pattern in table names of a db
 */
function search_db($dbs=array(), $pattern)
{
    foreach($dbs as $db_name) {
        $result = mysql_list_tables($db_name);
        while ($row = mysql_fetch_row($result)) {
            $matched = array();
            if (strstr($row[0], $pattern)) {
                $matched[] = $row[0];
            }
            // search in table fields
            search_table($db_name, $row[0], $pattern);
        }
        mysql_free_result($result);

        if (count($matched)) {
            echo "searching in db $db_name\n";
            foreach($matched as $t) echo "\tmatched in table : ",$t,"\n";
        }
    }
}

function usage()
{
    echo "\n";
    echo "Usage: phpmysearch.php -d <db_name> -t <table_name> -s <pattern> [OPTION]...\n";
    echo "\n";
    echo "\t-h\t\tPrint help\n";
    echo "\n";
    echo "\t-d\t\tdb_name\n";
    echo "\t-t\t\ttable_name\n";
    echo "\t-s\t\tpattern\n";    
    echo "\n";
    exit;    
}

$shortopts  = "t:d:s:";
$shortopts .= "h";

$options = getopt($shortopts);
if (empty($options)) usage();
if (isset($options['h'])) usage();
if (!isset($options['s'])) usage();


/*** MAIN ***/

$link = mysql_connect(DB_HOST, DB_USER, DB_PWD);
if (!$link) {
    echo 'Could not connect to mysql';
    exit;
}

echo 'search for "'.$options['s'],'"',"\n";

$dbs = array();
if (isset($options['d'])) {
    $dbs[] = $options['d'];
} else {
    $db_list = mysql_list_dbs($link);
    while ($row = mysql_fetch_object($db_list)) {
        $dbs[] = $row->Database;
    }

}
search_db($dbs, $options['s']);
