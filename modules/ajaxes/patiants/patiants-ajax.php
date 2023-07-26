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
    14 => "Active",
    15 => "Actions",

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
 `active` like '%" . (trim($searchValue) == 'active' ? 1 : 0) . "%' OR
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
        "sex" => '<div class="badge badge-purple d-flex flex-1 justify-content-center">' . $value["sex"] . '</div>',
        "patiant_alias" => $value["patiant_alias"],
        "primary_language" => $value["primary_language"],
        "material_status" => '<div class="badge badge-yellow d-flex flex-1 justify-content-center">' . $value["material_status"] . '</div>',
        "religion" => '<div class="badge badge-indigo d-flex flex-1 justify-content-center">' . $value["religion"] . '</div>',
        "nationality" => '<div class="badge badge-pink d-flex flex-1 justify-content-center">' . $value["nationality"] . '</div>',
        "patiant_account_number" => $value["patiant_account_number"],
        "ssn_number" => '<div class="badge badge-teal d-flex flex-1 justify-content-center">' . $value["ssn_number"] . '</div>',

        "patiant_deth_date_time" => $value["patiant_deth_date_time"],
        "patiant_death_indicator" => $value["patiant_death_indicator"],
        "Active" => $value["active"] == 1 ?  '<div class="badge badge-success d-flex flex-1 justify-content-center"> Active </div>' : '<div class="badge badge-danger d-flex flex-1 justify-content-center"> Susspended </div>',
        "Actions" => '<td class="text-center">
        <div class="list-icons">
            <div class="dropdown position-static">
                <a href="datatable_basic.html#" class="list-icons-item" data-toggle="dropdown">
                    <i class="icon-menu9"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-right">' . ($_POST['section']['edit'] == 1 ?
            '<a href="index.php?cmd=Patients&suspend=1&id=' . $value['id'] . '" class="dropdown-item"><i class="icon-eye-blocked2"></i> Suspend </a>
                
                <a href="index.php?cmd=Patients&active=1&id=' . $value['id'] . '" class="dropdown-item"><i class="icon-eye2"></i> Activate </a>
                <a href="#" data-toggle="modal" data-target="#edit_popup' . $key . '" class="dropdown-item"><i class="icon-pencil7"></i> Edit</a>'
            : ''
        ) .
            ($_POST['section']['delete'] == 1 ? '<a href="#" data-toggle="modal" data-target="#delete_pop_up' . $key . '" class="dropdown-item"><i class="icon-trash"></i>Delete</a>' : '') .


            '        </div>
            </div>
        </div>
        
    
    
    <div id="edit_popup' . $key . '" class="modal fade show"  aria-modal="true" role="dialog" >
    <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="edit" value="' . $value["id"] . '">
		<div class="modal-dialog modal-dialog-scrollable modal-full">
			<div class="modal-content">
				<div class="modal-header bg-primary text-white">
					<h5 class="modal-title">Edit Patiant Details</h5>
					<button type="button" class="close" data-dismiss="modal">×</button>
				</div> 
				<div class="modal-body">
                
                             
                <div class="form-group row">
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">Patiant Name</label>
                <div class="col-lg-9 col-sm-12 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['patiant_name'] . '" value="' . $value['patiant_name'] . '" name="patiant_name"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Family Name</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['family_name'] . '" value="' . $value['family_name'] . '" name="family_name"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Mother Name</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['mother_name'] . '" value="' . $value['mother_name'] . '" name="mother_name"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">BirthDate</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['date_of_birthday'] . '" value="' . $value['date_of_birthday'] . '" name="date_of_birthday"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">SEX</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['sex'] . '" value="' . $value['sex'] . '" name="sex"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Patiant Alias</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['patiant_alias'] . '" value="' . $value['patiant_alias'] . '" name="patiant_alias"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Patiant Adress</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['patiant_adress'] . '" value="' . $value['patiant_adress'] . '" name="patiant_adress"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Country Code</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['country_code'] . '" value="' . $value['country_code'] . '" name="country_code"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Phone Number Home</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['phone_number_home'] . '" value="' . $value['phone_number_home'] . '" name="phone_number_home"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Phone Number Business</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['phone_number_business'] . '" value="' . $value['phone_number_business'] . '" name="phone_number_business"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Primary Languages</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['primary_language'] . '" value="' . $value['primary_language'] . '" name="primary_language"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Material Status</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['material_status'] . '" value="' . $value['material_status'] . '" name="material_status"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Religion</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['religion'] . '" value="' . $value['religion'] . '" name="religion"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Patiant Account Number</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['patiant_account_number'] . '" value="' . $value['patiant_account_number'] . '" name="patiant_account_number"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">SSN Number</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['ssn_number'] . '" value="' . $value['ssn_number'] . '" name="ssn_number"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Driver License Number</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['driver_license_number'] . '" value="' . $value['driver_license_number'] . '" name="driver_license_number"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Ethinc Number</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['ethinc_number'] . '" value="' . $value['ethinc_number'] . '" name="ethinc_number"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Birth Place</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['birth_Place'] . '" value="' . $value['birth_Place'] . '" name="birth_Place"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Nationality</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['nationality'] . '" value="' . $value['nationality'] . '" name="nationality"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Patiant Deth Date Time</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['patiant_deth_date_time'] . '" value="' . $value['patiant_deth_date_time'] . '" name="patiant_deth_date_time"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Patiant Death Indicator</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['patiant_death_indicator'] . '" value="' . $value['patiant_death_indicator'] . '" name="patiant_death_indicator"">
                    </div>
                </div>
                </div>
                </div>
                <div class=" col-lg-6 p-2">
                    <div class="row">
                        <div class="custom-control custom-control-right custom-switch text-right p-2">
                            <input type="checkbox" class="custom-control-input " id="sc_rs_c'.$key.'" name="active" '.($value['active'] == 1 ? 'checked=""' : '').'>
                            <label class="custom-control-label " for="sc_rs_c'.$key.'">Active</label>
                        </div>
                    </div>
                </div>
                
				</div>
                </div>
				<div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="icon-cross2 font-size-base mr-1"></i> Close</button>
                <button type="submit" class="btn btn-primary"> <i class="icon-checkmark3 font-size-base mr-1"></i> Save changes</button>
				</div>
		</div>
        </form>
	</div>
	</div>
    <div id="delete_pop_up' . $key . '" class="modal fade show" aria-modal="false" role="dialog" >
    <form method="POST" >
    <input type="hidden" name="delete" value="' . $value['id'] . '">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-danger text-white">
					<h6 class="modal-title">Delete Confirmation</h6>
					<button type="button" class="close" data-dismiss="modal">×</button>
				</div>
				<div class="modal-body">
					<h6 class="font-weight-semibold">Are you sure to delete this location</h6>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
					<button type="sumbit" class="btn btn-danger">Save changes</button>
				</div>
                </form>
			</div>
		</div>
	</div>
    </td>
	',

    );
}
echo json_encode(array(
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $filteredRecords,
    "data" => $data
));
die;
