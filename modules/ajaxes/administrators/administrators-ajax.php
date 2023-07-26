<?php
require "../../../includes/conf.php";
$columns = [
    0 => "img",
    1 => "username",
    2 => "Privileges",
    3 => "time_stamp",
    4 => "Active",
    5 => "Actions",
];
$query = "SELECT * FROM `administrators`";
$totalRecords = count($db->select("SELECT * FROM `administrators`"));
$searchValue = $_POST["search"]["value"];
$query .=
    " WHERE (
`username` like '%" .
    trim($searchValue) .
    "%')
";
$query .= " ORDER BY `administrators`.`id` DESC";
$innerData = $db->select($query);
$filteredRecords = count($innerData);
if (isset($_POST["length"])) {
    $limit = $_POST["length"];
    $offset = $_POST["start"];
    $query .= " LIMIT $limit OFFSET $offset";
}
$innerData = $db->select($query);
$data = [];
foreach ($innerData as $key => $value) {
    $cmsModeules = $db->select(
        'SELECT `cms_modules`.`id`, `cms_modules`.`name`, `cms_modules`.`status`,
     `cms_modules`.`link`, `cms_modules`.`icon`, `cms_modules`.`have_actions`,
      `admin_prev`.`admin_id`, `admin_prev`.`edit`,
      `admin_prev`.`add`, `admin_prev`.`delete`, `admin_prev`.`view`, `admin_prev`.`module_id`
       FROM `cms_modules` inner join `admin_prev` on `cms_modules`.`id` =`admin_prev`.`module_id`
        WHERE `admin_prev`.`admin_id` = ' . $value["id"]
    );
    $data[$key] = [
        "img" =>
        '<img width=100 height=100 src="' .
            $mediaPath .
            "/admins/" .
            $value["img"] .
            '" ></img>',
        "username" => $value["username"],
        "Privileges" =>
        '<a href="#" data-toggle="modal" data-target="#admin_prev' .
            $key .
            '" class="btn btn-primary d-flex flex-1 justify-content-center"> Privileges </a>',
        "time_stamp" =>
        '<div class="badge badge-indigo d-flex flex-1 justify-content-center">' .
            $value["time_stamp"] .
            "</div>",
        "Active" =>
        $value["status"] == 0
            ? '<div class="badge badge-danger d-flex flex-1 justify-content-center"> Susspend </div>'
            : '<div class="badge badge-success d-flex flex-1 justify-content-center"> Active </div>',
    ];
    $data[$key]["Actions"] =
        '<td class="text-center">
        <div class="list-icons">
            <div class="dropdown position-static">
                <a href="datatable_basic.html#" class="list-icons-item" data-toggle="dropdown">
                    <i class="icon-menu9"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-lg">' .
        ($_POST['section']['edit'] == 1 ?
            '<a href="index.php?cmd=admin&suspend=1&id=' .
            $value["id"] .
            '" class="dropdown-item"><i class="icon-eye-blocked2"></i> Suspend</a>
                <a href="index.php?cmd=admin&active=1&id=' .
            $value["id"] .
            '" class="dropdown-item"><i class="icon-eye2"></i> Activate</a>
                <a href="#" data-toggle="modal" data-target="#edit_popup' .
            $key .
            '" class="dropdown-item"><i class="icon-pencil7"></i> Edit</a>' : '') .
        ($_POST['section']['delete'] == 1 ?
            '<a href="#" data-toggle="modal" data-target="#delete_pop_up' .
            $key .
            '" class="dropdown-item"><i class="icon-trash"></i>Delete</a>' : '') .
        '</div>
            </div>
        </div>
    </td>
    <div id="edit_popup' .
        $key .
        '" class="modal fade show"  aria-modal="true" role="dialog" >
    <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="edit" value="' .
        $value["id"] .
        '">
    <div class="modal-dialog modal-dialog-scrollable modal-full">
    <div class="modal-content">
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Edit Admin Details</h5>
            <button type="button" class="close" data-dismiss="modal">×</button>
        </div> 
        <div class="modal-body">
        <div class="form-group row">
        <div class="col-lg-6 p-2">
                            <div class="row">
                                <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">Admin Image</label>
                                <div class="col-lg-9 col-sm-12 col-md-9">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input " id="img" name="img">
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">Admin UserName</label>
                <div class="col-lg-9 col-sm-12 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="' .
        $value["username"] .
        '" value="' .
        $value["username"] .
        '" name="username"">
                    </div>
                </div>
                </div>
                </div>
                <div class="col-lg-6 p-2">
                <div class="row">
                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Admin Password</label>
                <div class="col-lg-9 col-sm-9 col-md-9">
                    <div class="input-group">
                        <span class="input-group-prepend">
                            <span class="input-group-text bg-primary border-primary text-white">
                                <i class="icon-pencil6"></i>
                            </span>
                        </span>
                        <input type="text" class="form-control border-left-0" placeholder="Admin Password"  name="password"">
                    </div>
                </div>
                </div>
                </div>
                
                <div class ="row p-2 justify-content-center">';

    foreach ($cmsModeules as $modulesKey => $module) {
        $data[$key]["Actions"] .=
            '<div class=" col-lg-6 col-md-12 col-sm-12 ">
                                            <div id="prev-block ' .
            $module["id"] .
            $value["id"] .
            '" class="card">
                                                <div class="card-header header-elements-inline">
                                                    <h6 class="card-title">' .
            $module["name"] .
            '</h6>
                                                </div>
                                                <div class="card-body ">
                                                    <ul class="media-list row justify-content-between " style="align-items: baseline;">
                                                        <li class="media  col-lg-3 col-md-4 col-sm-6">
                                                            <div class="mr-3 ">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input crud" id="add' .
            $module["id"] .
            $value["id"] .
            '" name="add' .
            $module["id"] .
            $value["id"] .
            '" ' .
            ($module["add"] == 1 ? "checked" : "") .
            '>
                                                                    <label for="add' .
            $module["id"] .
            $value["id"] .
            '" class="custom-control-label p-0"></label>
                                                                </div>
                                                            </div>
                                                            <div class="media-body ">
                                                                <h6 class="media-title">
                                                                    <label for="add' .
            $module["id"] .
            $value["id"] .
            '" class="font-weight-semibold cursor-pointer mb-0">Add</label>
                                                                </h6>
                                                            </div>
                                                        </li>
                                                        <li class="media  col-lg-3 col-md-4 col-sm-6">
                                                            <div class="mr-3">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input crud" id="edit' .
            $module["id"] .
            $value["id"] .
            '" name="edit' .
            $module["id"] .
            $value["id"] .
            '" ' .
            ($module["edit"] == 1 ? "checked" : "") .
            '>
                                                                    <label for="edit' .
            $module["id"] .
            $value["id"] .
            '" class="custom-control-label p-0"></label>
                                                                </div>
                                                            </div>
                                                            <div class="media-body ">
                                                                <h6 class="media-title">
                                                                    <label for="edit' .
            $module["id"] .
            $value["id"] .
            '" class="font-weight-semibold cursor-pointer mb-0">Edit</label>
                                                                </h6>
                                                            </div>
                                                        </li>
                                                        <li class="media  col-lg-3 col-md-4 col-sm-6">
                                                            <div class="mr-3">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input crud" id="delete' .
            $module["id"] .
            $value["id"] .
            '" name="delete' .
            $module["id"] .
            $value["id"] .
            '" ' .
            ($module["delete"] == 1 ? "checked" : "") .
            '>
                                                                    <label for="delete' .
            $module["id"] .
            $value["id"] .
            '" class="custom-control-label p-0"></label>
                                                                </div>
                                                            </div>
                                                            <div class="media-body ">
                                                                <h6 class="media-title">
                                                                    <label for="delete' .
            $module["id"] .
            $value["id"] .
            '" class="font-weight-semibold cursor-pointer mb-0">Delete</label>
                                                                </h6>
                                                            </div>
                                                        </li>
                                                        <li class="media  col-lg-3 col-md-4 col-sm-6">
                                                            <div class="mr-3">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="view' .
            $module["id"] .
            $value["id"] .
            '" name="view' .
            $module["id"] .
            $value["id"] .
            '" ' .
            ($module["view"] == 1 ? "checked" : "") .
            '>
                                                                    <label for="view' .
            $module["id"] .
            $value["id"] .
            '"  class="custom-control-label p-0"></label>
                                                                </div>
                                                            </div>
                                                            <div class="media-body ">
                                                                <h6 class="media-title">
                                                                    <label for="view' .
            $module["id"] .
            $value["id"] .
            '" class="font-weight-semibold cursor-pointer mb-0">View</label>
                                                                </h6>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>';
    }
    $data[$key]["Actions"] .=
        '
        
    </div>
    
    
    
	
	</div>
    </div>
    <div class="modal-footer">
    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="icon-cross2 font-size-base mr-1"></i> Close</button>
    <button id="test' .
        $value["id"] .
        '" type="sumbit" value="submit" class="btn btn-primary"> <i class="icon-checkmark3 font-size-base mr-1"></i> Save Changes</button>
        </div>
        </div>
        </div>
        </form>
        </div>
    <div id="admin_prev' .
        $key .
        '" class="modal fade show"  aria-modal="true" role="dialog" >
    <div class="modal-dialog modal-dialog-scrollable modal-full">
    <div class="modal-content">
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Admin Privileges Preview</h5>
            <button type="button" class="close" data-dismiss="modal">×</button>
        </div> 
        <div class="modal-body">
        <div class="form-group row justify-content-center">
        ';
    foreach ($cmsModeules as $modulesKey => $module) {
        $data[$key]["Actions"] .=
            '<div class=" col-lg-6 col-md-12 col-sm-12 ">
                    <div id="prev-block ' .
            $module["id"] .
            '" class="card">
                        <div class="card-header header-elements-inline">
                            <h6 class="card-title">' .
            $module["name"] .
            '</h6>
                        </div>
                        <div class="card-body row justify-content-center">
                        <div class="badge ' .
            ($module["add"] == 1 ? "badge-success" : "badge-danger") .
            ' d-flex flex-1 m-1 col-lg-3 col-md-4 col-sm-6 justify-content-center"> ADD </div>
                        <div class="badge ' .
            ($module["edit"] == 1 ? "badge-success" : "badge-danger") .
            ' d-flex flex-1 m-1 col-lg-3 col-md-4 col-sm-6 justify-content-center"> EDIT </div>
                        <div class="badge ' .
            ($module["delete"] == 1 ? "badge-success" : "badge-danger") .
            ' d-flex flex-1 m-1 col-lg-3 col-md-4 col-sm-6 justify-content-center"> DELETE </div>
                        <div class="badge ' .
            ($module["view"] == 1 ? "badge-success" : "badge-danger") .
            ' d-flex flex-1 m-1 col-lg-3 col-md-4 col-sm-6 justify-content-center"> VIEW </div>
                        </div>
                    </div>
                </div>';
    }
    $data[$key]["Actions"] .=
        '
        </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="icon-cross2 font-size-base mr-1"></i> Close</button>
        </div>
    </div>
</div>
	</div>
    <div id="delete_pop_up' .
        $key .
        '" class="modal fade show" aria-modal="false" role="dialog" >
    <form method="POST" >
    <input type="hidden" name="delete" value="' .
        $value["id"] .
        '">
						<div class="modal-dialog modal-dialog-scrollable">
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
									<button type="sumbit" class="btn btn-danger">Delete</button>
								</div>
                                </form>
							</div>
						</div>
					</div>
    ';
}
echo json_encode([
    "draw" => intval($_POST["draw"]),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $filteredRecords,
    "data" => $data,
]);
die();
