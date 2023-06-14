







<?php
require("../../../includes/conf.php");
$columns = array(

    0 =>  "patiant_name",
    1 => "family_name",
    2 => "mother_name",
    3 => "date_of_birthday",
    4 => "sex",
    5 => "patiant_alias",
    6 => "primary_language",
    7 => "material_status",
    8 => "religion",
    9 => "nationality",
    10 => "patiant_account_number",
    11 => "ssn_number",
    12 => "patiant_deth_date_time",
    13 => "patiant_death_indicator",
    14 => "Actions",

);
$query = "SELECT * FROM `patiant_identifications`";

$totalRecords = count($db->select("SELECT * FROM `patiant_identifications`"));
$searchValue = $_POST['search']['value'];




$query .= " WHERE 
`patiant_name` like '%" . trim($searchValue) . "%' OR
`family_name` like '%" . trim($searchValue) . "%' OR
`mother_name` like '%" . trim($searchValue) . "%' OR
 `date_of_birthday` like '%" . trim($searchValue) . "%' OR
 `sex` like '%" . trim($searchValue) . "%' OR
 `patiant_alias` like '%" . trim($searchValue) . "%' OR
 `patiant_adress` like '%" . trim($searchValue) . "%' OR
 `country_code` like '%" . trim($searchValue) . "%' OR
 `phone_number_home` like '%" . trim($searchValue) . "%' OR
 `phone_number_business` like '%" . trim($searchValue) . "%' OR
 `primary_language` like '%" . trim($searchValue) . "%' OR
 `material_status` like '%" . trim($searchValue) . "%' OR
 `religion` like '%" . trim($searchValue) . "%' OR
 `patiant_account_number` like '%" . trim($searchValue) . "%' OR
 `ssn_number` like '%" . trim($searchValue) . "%' OR
 `driver_license_number` like '%" . trim($searchValue) . "%' OR
 `ethinc_number` like '%" . trim($searchValue) . "%' OR
 `birth_Place` like '%" . trim($searchValue) . "%' OR
 `nationality` like '%" . trim($searchValue) . "%' OR
 `patiant_deth_date_time` like '%" . trim($searchValue) . "%' OR
 `patiant_death_indicator` like '%" . trim($searchValue) . "%'
";

if (isset($_POST['order'])) {
    $orderBy = $columns[$_POST['order'][0]['column']];
    $orderDir = $_POST['order'][0]['dir'];
    $query .= " ORDER BY $orderBy $orderDir";
} else {
    $query .= " ORDER BY `patiant_identifications`.`Id` DESC";
}
// print($query);
// die;

$innerData = $db->select($query);
$filteredRecords = count($innerData);
if (isset($_POST['length'])) {
    $limit = $_POST['length'];
    $offset = $_POST['start'];
    $query .= " LIMIT $limit OFFSET $offset";
}
$innerData = $db->select($query);
// print_r($innerData);


$data = array();

foreach ($innerData as $key => $value) {
    $data[$key] = array(
        "patiant_name" => $value["patiant_name"],
        "family_name" => $value["family_name"],
        "mother_name" => $value["mother_name"],
        "date_of_birthday" => $value["date_of_birthday"],
        "sex" => '<div class="badge badge-purple">' . $value["sex"] . '</div>',
        "patiant_alias" => $value["patiant_alias"],
        "primary_language" => $value["primary_language"],
        "material_status" => '<div class="badge badge-yellow">' . $value["material_status"] . '</div>',
        "religion" => '<div class="badge badge-indigo">' . $value["religion"] . '</div>',
        "nationality" => '<div class="badge badge-pink">' . $value["nationality"] . '</div>',
        "patiant_account_number" => $value["patiant_account_number"],
        "ssn_number" => '<div class="badge badge-teal">' . $value["ssn_number"] . '</div>',
        
        "patiant_deth_date_time" => $value["patiant_deth_date_time"],
        "patiant_death_indicator" => $value["patiant_death_indicator"],
        "Actions" => '<td class="text-center">
        <div class="list-icons">
            <div class="dropdown">
                <a href="datatable_basic.html#" class="list-icons-item" data-toggle="dropdown">
                    <i class="icon-menu9"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a href="datatable_basic.html#" class="dropdown-item"><i class="icon-file-pdf"></i> Export to .pdf</a>
                    <a href="datatable_basic.html#" class="dropdown-item"><i class="icon-file-excel"></i> Export to .csv</a>
                    <a href="datatable_basic.html#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>
                </div>
            </div>
        </div>
    </td>',

    );
}
echo json_encode(array(
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $filteredRecords,
    "data" => $data
));
die;
