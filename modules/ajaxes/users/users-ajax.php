<?php

require "../../../includes/conf.php";

$columns = [

    0 => "img",

    1 => "username",

    // 2 => "email",
    2 => "Default_location",

    3 => "full_name",

    // 5 => "phone_number",

    // 6 => "address",

    // 7 => "role",

    4 => "creation_date",

    5 => "last_date_modified",

    6 => "status",

    7 => "Actions",

];



$query = "SELECT * FROM `users`";

$totalRecords = count($db->select("SELECT * FROM `users`"));

$searchValue = $_POST["search"]["value"];

$query .= " WHERE (

    `username` like '%" . trim($searchValue) . "%' OR

    `full_name` like '%" . trim($searchValue) . "%' OR

    `phone_number` like '%" . trim($searchValue) . "%' OR

    `address` like '%" . trim($searchValue) . "%' OR

    `role` like '%" . trim($searchValue) . "%' OR

    `status` like '%" . (trim($searchValue) == 'active' ? 1 : 0) . "%')

    ";



// if (isset($_POST['order'])) {

//     $orderBy = $columns[$_POST['order'][0]['column']];

//     $orderDir = $_POST['order'][0]['dir'];

//     $query .= " ORDER BY $orderBy $orderDir";

// } else {

$query .= " ORDER BY `users`.`id` desc";

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

// print_r($_POST[''])
$locations = $db->select('select * from `locations` where `active` = 1');
$tempLocation = array();
foreach ($locations as $key => $value) {
    $tempLocation[$value['location_id']] = $value;
}
foreach ($innerData as $key => $value) {

    $cmsModeules = $db->select(

        'SELECT `cms_modules`.`id`, `cms_modules`.`name`, `cms_modules`.`status`,

     `cms_modules`.`link`, `cms_modules`.`icon`, `cms_modules`.`have_actions`,

      `users_prev`.`user_id`, `users_prev`.`edit`,

      `users_prev`.`add`, `users_prev`.`delete`, `users_prev`.`view`, `users_prev`.`module_id`

       FROM `cms_modules` inner join `users_prev` on `cms_modules`.`id` =`users_prev`.`module_id`

        WHERE `users_prev`.`user_id` = ' . $value["id"]

    );

    $data[$key] = array(

        "img" => '<img class="border-2 rounded-lg border-primary" width=120 height=120 src="' . $mediaPath . '/users/' . $value['img'] . '">',

        "username" => $value['id'] . ' . ' . $value["username"],

        // "email" => $value["email"],
        "Default_location" => '<div class="badge badge-success d-flex flex-1 justify-content-center"> ' . $tempLocation[$value["location_id"]]['location_name'] . ' </div>',

        "full_name" => $value['full_name'],

        // "phone_number" => $value['phone_number'],

        // "address" => $value["address"],

        // "role" => '<div class="badge badge-teal d-flex flex-1 justify-content-center"> ' . $value['role'] . ' </div>',

        "creation_date" => $value["creation_date"],

        "last_date_modified" => $value["last_date_modified"],

        "status" => $value["status"] == 1 ? '<div class="badge badge-success d-flex flex-1 justify-content-center"> Active </div>' : '<div class="badge badge-danger"> Susspend </div>',

    );

    $data[$key]["Actions"] =

        '
            <td class="text-center">

            <div class="list-icons">

                <div class="dropdown position-static">

                    <a href="datatable_basic.html#" class="list-icons-item" data-toggle="dropdown">

                        <i class="icon-menu9"></i>

                    </a>

    

                    <div class="dropdown-menu dropdown-menu-right">' . ($_POST['section']['edit'] == 1 ?

            '<a href="index.php?cmd=users&suspend=1&id=' . $value['id'] . '" class="dropdown-item"><i class="icon-eye-blocked2"></i> Suspend </a>

                    

                    <a href="index.php?cmd=users&active=1&id=' . $value['id'] . '" class="dropdown-item"><i class="icon-eye2"></i> Activate </a>

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

                        <h5 class="modal-title">Edit Location Details</h5>

                        <button type="button" class="close" data-dismiss="modal">×</button>

                    </div> 

                    <div class="modal-body">

                    

                    <div class="form-group row">

                    <div class="col-lg-6 p-2">

                            <div class="row">

                                <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">User Image</label>

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

                                <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">Default Location</label>

                                <div class="col-lg-9 col-sm-12 col-md-9">

                                    <select id="locationSelect' . $value['id'] . '" class="form-control select" data-placeholder="Select Default Location" name="location_id">
                                        ';
    foreach ($locations as $locationKey => $locationValue) {


        $data[$key]["Actions"] .= '<option ' . ($locationValue['location_id'] == $value['location_id'] ? 'selected = "true"' : '') . ' value="' . $locationValue['location_id'] . '">' . $locationValue['location_name'] . '</option>';
    }
    $data[$key]["Actions"] .= '
                                    </select>
                                </div>

                            </div>


                        </div>



                    <div class="col-lg-6 p-2">

                        <div class="row">

                            <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">UserName</label>

                            <div class="col-lg-9 col-sm-12 col-md-9">

                                <div class="input-group">

                                    <span class="input-group-prepend">

                                        <span class="input-group-text bg-primary border-primary text-white">

                                            <i class="icon-pencil6"></i>

                                        </span>

                                    </span>

                                    <input type="text" class="form-control border-left-0" placeholder="UserName" name="username" value="' . $value['username'] . '" required="">

                                </div>

                            </div>

                        </div>

                    </div>



                    <div class=" col-lg-6 p-2">

                        <div class="row">

                            <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">User Password</label>

                            <div class="col-lg-9 col-sm-12 col-md-9">

                                <div class="input-group">

                                    <span class="input-group-prepend">

                                        <span class="input-group-text bg-primary border-primary text-white">

                                            <i class="icon-file-locked2"></i>

                                        </span>

                                    </span>

                                    <input type="password" class="form-control border-left-0" placeholder="password" name="password" >

                                </div>

                            </div>

                        </div>

                    </div>



                    <div class="col-lg-6 p-2">

                        <div class="row">

                            <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">User Email</label>

                            <div class="col-lg-9 col-sm-12 col-md-9">

                                <div class="input-group">

                                    <span class="input-group-prepend">

                                        <span class="input-group-text bg-primary border-primary text-white">

                                            <i class="icon-pencil6"></i>

                                        </span>

                                    </span>

                                    <input type="text" class="form-control border-left-0" placeholder="User Email" name="email" value="' . $value['email'] . '" required="">

                                </div>

                            </div>

                        </div>

                    </div>



                    <div class="col-lg-6 p-2">

                        <div class="row">

                            <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">Full Name</label>

                            <div class="col-lg-9 col-sm-12 col-md-9">

                                <div class="input-group">

                                    <span class="input-group-prepend">

                                        <span class="input-group-text bg-primary border-primary text-white">

                                            <i class="icon-pencil6"></i>

                                        </span>

                                    </span>

                                    <input type="text" class="form-control border-left-0" placeholder="Full Name" name="full_name" value="' . $value['full_name'] . '" required="">

                                </div>

                            </div>

                        </div>

                    </div>



                    <div class="col-lg-6 p-2">

                        <div class="row">

                            <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">Phone Number</label>

                            <div class="col-lg-9 col-sm-12 col-md-9">

                                <div class="input-group">

                                    <span class="input-group-prepend">

                                        <span class="input-group-text bg-primary border-primary text-white">

                                            <i class="icon-pencil6"></i>

                                        </span>

                                    </span>

                                    <input type="text" class="form-control border-left-0" placeholder="Phone Number" name="phone_number" value="' . $value['phone_number'] . '" required="">

                                </div>

                            </div>

                        </div>

                    </div>



                    <div class="col-lg-6 p-2">

                        <div class="row">

                            <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">Address</label>

                            <div class="col-lg-9 col-sm-12 col-md-9">

                                <div class="input-group">

                                    <span class="input-group-prepend">

                                        <span class="input-group-text bg-primary border-primary text-white">

                                            <i class="icon-pencil6"></i>

                                        </span>

                                    </span>

                                    <input type="text" class="form-control border-left-0" placeholder="User Adress" name="address" value="' . $value['address'] . '" required="">

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="col-lg-6 p-2">

                        <div class="row">

                            <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">User Role</label>

                            <div class="col-lg-9 col-sm-12 col-md-9">

                                <div class="input-group">

                                    <span class="input-group-prepend">

                                        <span class="input-group-text bg-primary border-primary text-white">

                                            <i class="icon-pencil6"></i>

                                        </span>

                                    </span>

                                    <input type="text" class="form-control border-left-0" placeholder="User Role" name="role" value="' . $value['role'] . '" required="">

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

                    <div style="width:100%;height:1px;"></div>

                    

                    <div class ="row p-2 justify-content-center">';

    /* foreach ($cmsModeules as $modulesKey => $module) {

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

    } */

    foreach ($cmsModeules as $modulesKey => $module) {

        $data[$key]["Actions"] .=

            '<div class="col-lg-6 col-md-12 col-sm-12">

            <div id="prev-block ' . $module["id"] . $value["id"] . '" class="card">

                <div class="card-header header-elements-inline">

                    <h6 class="card-title">' . $module["name"] . '</h6>

                </div>

                <div class="card-body">

                    <ul class="media-list row justify-content-between" style="align-items: baseline;">

                        <li class="media col-lg-3 col-md-4 col-sm-6">

                            <div class="mr-3">

                                <div class="custom-control custom-checkbox">

                                    <input type="checkbox" class="custom-control-input crud" id="add' . $module["id"] . $value["id"] . '" name="add' . $module["id"] . $value["id"] . '" ' . ($module["add"] == 1 ? "checked" : "") . '>

                                    <label for="add' . $module["id"] . $value["id"] . '" class="custom-control-label p-0"></label>

                                </div>

                            </div>

                            <div class="media-body">

                                <h6 class="media-title">

                                    <label for="add' . $module["id"] . $value["id"] . '" class="font-weight-semibold cursor-pointer mb-0">Add</label>

                                </h6>

                            </div>

                        </li>

                        <li class="media col-lg-3 col-md-4 col-sm-6">

                            <div class="mr-3">

                                <div class="custom-control custom-checkbox">

                                    <input type="checkbox" class="custom-control-input crud" id="edit' . $module["id"] . $value["id"] . '" name="edit' . $module["id"] . $value["id"] . '" ' . ($module["edit"] == 1 ? "checked" : "") . '>

                                    <label for="edit' . $module["id"] . $value["id"] . '" class="custom-control-label p-0"></label>

                                </div>

                            </div>

                            <div class "media-body">

                                <h6 class="media-title">

                                    <label for="edit' . $module["id"] . $value["id"] . '" class="font-weight-semibold cursor-pointer mb-0">Edit</label>

                                </h6>

                            </div>

                        </li>

                        <li class="media col-lg-3 col-md-4 col-sm-6">

                            <div class="mr-3">

                                <div class="custom-control custom-checkbox">

                                    <input type="checkbox" class="custom-control-input crud" id="delete' . $module["id"] . $value["id"] . '" name="delete' . $module["id"] . $value["id"] . '" ' . ($module["delete"] == 1 ? "checked" : "") . '>

                                    <label for="delete' . $module["id"] . $value["id"] . '" class="custom-control-label p-0"></label>

                                </div>

                            </div>

                            <div class="media-body">

                                <h6 class="media-title">

                                    <label for="delete' . $module["id"] . $value["id"] . '" class="font-weight-semibold cursor-pointer mb-0">Delete</label>

                                </h6>

                            </div>

                        </li>

                        <li class="media col-lg-3 col-md-4 col-sm-6">

                            <div class="mr-3">

                                <div class="custom-control custom-checkbox">

                                    <input type="checkbox" class="custom-control-input" id="view' . $module["id"] . $value["id"] . '" name="view' . $module["id"] . $value["id"] . '" ' . ($module["view"] == 1 ? "checked" : "") . '>

                                    <label for="view' . $module["id"] . $value["id"] . '" class="custom-control-label p-0"></label>

                                </div>

                            </div>

                            <div class="media-body">

                                <h6 class="media-title">

                                    <label for="view' . $module["id"] . $value["id"] . '" class="font-weight-semibold cursor-pointer mb-0">View</label>

                                </h6>

                            </div>

                        </li>

                    </ul>

                </div>

            </div>

        </div>';
    }

    $data[$key]["Actions"] .= '</div>

                    

                    

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

                                        <button type="sumbit" class="btn btn-danger">Save changes</button>

                                    </div>

                                    </form>

                                </div>

                            </div>

                        </div>

                        </div>

        

        ';
}

echo json_encode(array(

    "draw" => intval($_POST['draw']),

    "recordsTotal" => $totalRecords,

    "recordsFiltered" => $filteredRecords,

    "data" => $data

));

die;
