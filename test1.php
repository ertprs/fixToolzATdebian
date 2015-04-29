<?php
function outputCSV($data) {

    $outstream = fopen("php://output", 'w');

    function __outputCSV(&$vals, $key, $filehandler) {
        fputcsv($filehandler, $vals, ';', '"');
    }
    array_walk($data, '__outputCSV', $outstream);

    fclose($outstream);
}

$mydata = array(
    array('data11', 'data12', 'data13'),
    array('data21', 'data22', 'data23'),
    array('data31', 'data32', 'data23'));

outputCSV($mydata);
/* Output sent :
data11;data12;data13
data21;data22;data23
data31;data32;data23
*/
?>