<?php
require "../../../includes/conf.php";
$columns = [
    0 => 'Name',
    1 => 'Status',
    2 => 'Created_date',
    3 => 'Last_date_modified',
    4 => 'Actions',
];

$query = "SELECT * FROM `medication_type`";
$totalRecords = count($db->select("SELECT * FROM `medication_type`"));
$searchValue = $_POST["search"]["value"];
$query .= " WHERE (
    `name` like '%" . trim($searchValue) . "%' OR
    `status` like '%" . (trim($searchValue) == 'active' ? 1 : 0) . "%')
    ";

// if (isset($_POST['order'])) {
//     $orderBy = $columns[$_POST['order'][0]['column']];
//     $orderDir = $_POST['order'][0]['dir'];
//     $query .= " ORDER BY $orderBy $orderDir";
// } else {
$query .= " ORDER BY `medication_type`.`last_modified_date` desc";
// }
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
// print_r($_POST['section']);
// die;
foreach ($innerData as $key => $value) {
    $data[$key] = array(

        "Name" => $value['id'] . ' . ' . $value["name"],
        "Status" => $value["status"] == 1 ? '<div class="badge badge-success d-flex flex-1 justify-content-center"> Active </div>' : '<div class="badge badge-danger d-flex flex-1 justify-content-center"> Susspend </div>',
        "Created_date" => $value["created_date"],
        "Last_date_modified" => $value["last_modified_date"],
        "Actions" => '
            <td class="text-center">
            <div class="list-icons">
                <div class="dropdown position-static">
                    <a href="datatable_basic.html#" class="list-icons-item" data-toggle="dropdown">
                        <i class="icon-menu9"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">' . ($_POST['section']['edit'] == 1 ?
            '<a href="index.php?cmd=' . $_POST['cmd'] . '&suspend=1&id=' . $value['id'] . '" class="dropdown-item"><i class="icon-eye-blocked2"></i> Suspend </a>
                    
                    <a href="index.php?cmd=' . $_POST['cmd'] . '&active=1&id=' . $value['id'] . '" class="dropdown-item"><i class="icon-eye2"></i> Activate </a>
                    <a href="#" data-toggle="modal" data-target="#edit_popup' . $key . '" class="dropdown-item"><i class="icon-pencil7"></i> Edit</a>'
            : ''
        ) .
            ($_POST['section']['delete'] == 1 ? '<a href="#" data-toggle="modal" data-target="#delete_pop_up' . $key . '" class="dropdown-item"><i class="icon-trash"></i>Delete</a>' : '') .
            '        </div>
                </div>
            </div>
        </td>
        <div id="edit_popup' . $key . '" class="modal fade show"  aria-modal="true" role="dialog" >
        <form method="POST" enctype="multipart/form-data" >
        <input type="hidden" name="edit" value="' . $value['id'] . '">
            <div class="modal-dialog modal-full">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Edit Medication Type</h5>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div> 
                    <div class="modal-body">
                    <div class="form-group row">
                    <div class="col-lg-6 p-2">
                        <div class="row">
                            <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">Medication Type</label>
                            <div class="col-lg-9 col-sm-12 col-md-9">
                                <div class="input-group">
                                    <span class="input-group-prepend">
                                        <span class="input-group-text bg-primary border-primary text-white">
                                            <i class="icon-pencil6"></i>
                                        </span>
                                    </span>
                                    <input type="text" class="form-control border-left-0" placeholder="Medication Type" name="name" value="' . $value['name'] . '" required="">
                                </div>
                            </div>
                        </div>
                    </div>                    
                    
                    <div class="row p-2">
                        <div class="custom-control custom-control-right custom-switch text-right p-2">
                            <input type="checkbox" class="custom-control-input" id="sc_rs_c' . $value['id'] . '" name="status" ' . ($value['status'] == 1 ? 'checked=""' : '') . '>
                            <label class="custom-control-label" for="sc_rs_c' . $value['id'] . '">Active</label>
                        </div>
                    </div>
                    
                </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="icon-cross2 font-size-base mr-1"></i> Close</button>
                        <button type="submit" class="btn btn-primary"> <i class="icon-checkmark3 font-size-base mr-1"></i> Save changes</button>
                    </div>
                </div>
            </div>
            </form>
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
                                        <h6 class="font-weight-semibold">Are you sure to delete this users</h6>
                                        
                                    </div>
    
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                        <button type="sumbit" class="btn btn-danger">Delete</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        </div>
        
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
