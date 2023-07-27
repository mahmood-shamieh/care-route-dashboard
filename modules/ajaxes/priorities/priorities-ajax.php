<?php
require "../../../includes/conf.php";
$columns = [
    0 => "name",
    1 => "priority",
    2 => "color",
    3 => "description",
    4 => "created_date",
    5 => "last_modified_date",
    6 => "status",
    7 => "Actions",
];
$query = "SELECT * FROM `priorities`";

$totalRecords = count($db->select("SELECT * FROM `priorities`"));
$searchValue = $_POST['search']['value'];




$query .= " WHERE (
`name` like '%" . trim($searchValue) . "%' OR
`priority` like '%" . trim($searchValue) . "%' OR
`description` like '%" . trim($searchValue) . "%' OR
`status` like '%" . (trim($searchValue) == 'active' ? 1 : 0) . "%')
";

if (isset($_POST['order'])) {
    $orderBy = $columns[$_POST['order'][0]['column']];
    $orderDir = $_POST['order'][0]['dir'];
    $query .= " ORDER BY $orderBy $orderDir";
} else {
    $query .= " ORDER BY `priorities`.`id` asc";
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
// print_r($_POST['section']);
// die;
foreach ($innerData as $key => $value) {



    $data[$key] = array(
        "name" => $value['id'] .' . '. $value['name'],
        "priority" => $value["priority"],
        "color" => '<div class="badge d-flex flex-1 justify-content-center " style="color:white;background-color:' . $value['color'] . ';"> priority color </div>',
        "description" => $value['description'],
        "created_date" => $value["created_date"],
        "last_modified_date" => $value["last_modified_date"],
        "status" => $value["status"] == 1 ? '<div class="badge badge-success d-flex flex-1 justify-content-center"> Active </div>' : '<div class="badge badge-danger d-flex flex-1 justify-content-center"> Susspend </div>',
        "Actions" => '
        
        
        <td class="text-center">
        <div class="list-icons">
            <div class="dropdown">
                <a href="datatable_basic.html#" class="list-icons-item" data-toggle="dropdown">
                    <i class="icon-menu9"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-right">' . ($_POST['section']['edit'] == 1 ?
            '<a href="index.php?cmd=priorities&suspend=1&id=' . $value['id'] . '" class="dropdown-item"><i class="icon-eye-blocked2"></i> Suspend </a>
                
                <a href="index.php?cmd=priorities&active=1&id=' . $value['id'] . '" class="dropdown-item"><i class="icon-eye2"></i> Activate </a>
                <a href="#" data-toggle="modal" data-target="#edit_popup' . $key . '" class="dropdown-item"><i class="icon-pencil7"></i> Edit</a>'
            : ''
        ) .
            ($_POST['section']['delete'] == 1 ? '<a href="#" data-toggle="modal" data-target="#delete_pop_up' . $key . '" class="dropdown-item"><i class="icon-trash"></i>Delete</a>' : '') .


            '        </div>
            </div>
        </div>
    </td>
    
    <div id="edit_popup' . $key . '" class="modal fade show"  aria-modal="true" role="dialog" >
    <form method="POST" >
    <input type="hidden" name="edit" value="' . $value['id'] . '">
		<div class="modal-dialog modal-full">
			<div class="modal-content">
				<div class="modal-header bg-primary text-white">
					<h5 class="modal-title">Edit Priority Details</h5>
					<button type="button" class="close" data-dismiss="modal">×</button>
				</div> 
                <div class="modal-body">
                <div class="form-group row">
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">Priority Name</label>
                <div class="col-lg-9 col-sm-12 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['name'] . '" value="' . $value['name'] . '" name="name"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Priority Number</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' . $value['priority'] . '" value="' . $value['priority'] . '" name="priority"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Priority Color</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="color" class="form-control border-left-0" placeholder="' . $value['color'] . '" value="' . $value['color'] . '" name="color"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Priority Description</label>
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
                <div class=" col-lg-6 p-2 d-block ">
                <div class="row">
                    <div class="custom-control custom-control-right custom-switch text-right p-2">
                        <input type="checkbox" class="custom-control-input" id="sc_rs_c' . $value['id'] . '" name="status" ' . ($value['status'] == 1 ? 'checked=""' : '') . '>
                        <label class="custom-control-label" for="sc_rs_c' . $value['id'] . '">Active</label>
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
									<h6 class="font-weight-semibold">Are you sure to delete this priority</h6>
									
								</div>

								<div class="modal-footer">
									<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
									<button type="sumbit" class="btn btn-danger">Save changes</button>
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
