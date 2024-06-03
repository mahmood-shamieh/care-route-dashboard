

<?php





$insertionArr = array();

foreach ($data as $key => $value) {

    if ($value != null && strcmp($value, 'null') != 0) {

        $insertionArr[$key] = $db->sqlsafe($value);

    } else {

        $insertionArr[$key] ='null';

    }

}







$db->insert('rde_messages', $insertionArr);

print('<h1>data inserted successfully </h1>')

?>





