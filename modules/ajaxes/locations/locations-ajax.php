







<?php
require("../../../includes/conf.php");
$columns = array(

    0 => "location_id",
    1 => "location_name",
    2 => "check_in_status",
    3 => "check_out_status",
    4 => "description",
    5 => "creation_date",
    6 => "last_update",
    7 => "Active",
    8 => "Actions",

);
$query = "SELECT * FROM `locations`";

$totalRecords = count($db->select("SELECT * FROM `locations`"));
$searchValue = $_POST['search']['value'];




$query .= " WHERE (
`location_id` like '%" . trim($searchValue) . "%' OR
`location_name` like '%" . trim($searchValue) . "%' OR
`check_in_status` like '%" . trim($searchValue) . "%' OR
`check_out_status` like '%" . trim($searchValue) . "%' OR
`description` like '%" . trim($searchValue) . "%' OR
`creation_date` like '%" . trim($searchValue) . "%' OR
`last_update` like '%" . trim($searchValue) . "%' OR
`active` like '%" . (trim($searchValue) == 'active' ? 1 : 0) . "%')
";

if (isset($_POST['order'])) {
    $orderBy = $columns[$_POST['order'][0]['column']];
    $orderDir = $_POST['order'][0]['dir'];
    $query .= " ORDER BY $orderBy $orderDir";
} else {
    $query .= " ORDER BY `locations`.`location_id` DESC";
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
        "location_id" => $value["location_id"],
        "location_name" => $value["location_name"],
        "check_in_status" => '<div class="badge badge-indigo">' . $value["check_in_status"] . '</div>',
        "check_out_status" => '<div class="badge badge-pink">' . $value["check_out_status"] . '</div>',
        "description" => $value["description"],
        "creation_date" => $value["creation_date"],
        "last_update" => $value["last_update"],
        "Active" => $value["active"] == 1 ? '<div class="badge badge-teal"> Active </div>' : '<div class="badge badge-danger"> Susspend </div>',
        "Actions" => '<td class="text-center">
        <div class="list-icons">
            <div class="dropdown">
                <a href="datatable_basic.html#" class="list-icons-item" data-toggle="dropdown">
                    <i class="icon-menu9"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                <a href="index.php?cmd=location&suspend=1&id=' . $value['location_id'] . '" class="dropdown-item"><i class="icon-eye-blocked2"></i> Suspend</a>
                <a href="index.php?cmd=location&active=1&id=' . $value['location_id'] . '" class="dropdown-item"><i class="icon-eye2"></i> Activate</a>
                <a href="#" data-toggle="modal" data-target="#edit_popup' . $key . '" class="dropdown-item"><i class="icon-pencil7"></i> Edit</a>
                <a href="#" data-toggle="modal" data-target="#delete_pop_up' . $key . '" class="dropdown-item"><i class="icon-trash"></i>Delete</a>
                
                    
                </div>
            </div>
        </div>
    </td>
    <div id="edit_popup' . $key . '" class="modal fade show"  aria-modal="true" role="dialog" >
		<div class="modal-dialog modal-full">
			<div class="modal-content">
				<div class="modal-header bg-primary text-white">
					<h5 class="modal-title">Edit Location Details</h5>
					<button type="button" class="close" data-dismiss="modal">×</button>
				</div> 
				<div class="modal-body">
                <div class="form-group row">
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">Location Name</label>
                <div class="col-lg-9 col-sm-12 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['location_name'] . '" value="' . $value['location_name'] . '" name="location_name"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">CheckIn Status</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['check_in_status'] . '" value="' . $value['check_in_status'] . '" name="check_in_status"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">CheckOut Status</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['check_out_status'] . '" value="' . $value['check_out_status'] . '" name="check_out_status"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Location Description</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['description'] . '" value="' . $value['description'] . '" name="description"">
                    </div>
                </div>
                </div>
                </div>
                
            </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="icon-cross2 font-size-base mr-1"></i> Close</button>
					<button type="button" class="btn btn-primary"> <i class="icon-checkmark3 font-size-base mr-1"></i> Save changes</button>
				</div>
			</div>
		</div>
	</div>
    <div id="delete_pop_up' . $key . '" class="modal fade show" aria-modal="false" role="dialog" >
    <form method="POST" >
    <input type="hidden" name="delete" value="' . $value['location_id'] . '">
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
