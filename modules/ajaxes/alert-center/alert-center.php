<?php
require "../../../includes/conf.php";
$columns = [

    // 0 => "id",
    0 => "order_number",
    1 => "Medication",
    // 3 => "PatientName",
    2 => "Unit",
    // 5 => "Room",
    // 6 => "Bed",
    // 7 => "order_status",
    3 => "QTY",
    4 => "Start_Time",
    5 => "current_Status",
    6 => "current_user",
    7 => "current_location",
    8 => "Alert",
    // 13 => "Actions",

];

$query = "SELECT * FROM `rde_messages`";
$totalRecords = count($db->select("SELECT * FROM `rde_messages`"));
$searchValue = $_POST["search"]["value"];
// $query .= " WHERE (
//     `username` like '%" . trim($searchValue) . "%' OR
//     `full_name` like '%" . trim($searchValue) . "%' OR
//     `phone_number` like '%" . trim($searchValue) . "%' OR
//     `address` like '%" . trim($searchValue) . "%' OR
//     `role` like '%" . trim($searchValue) . "%' OR
//     `status` like '%" . (trim($searchValue) == 'active' ? 1 : 0) . "%')
//     ";

// if (isset($_POST['order'])) {
//     $orderBy = $columns[$_POST['order'][0]['column']];
//     $orderDir = $_POST['order'][0]['dir'];
//     $query .= " ORDER BY $orderBy $orderDir";
// } else {
$query .= " ORDER BY `rde_messages`.`id` desc";
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
function compareDatesWithTimeSlot($date1, $date2, $timeSlotInMinutes)
{

    // 0 red color
    // 1 yellow
    // 2 green
    // current Date is date 1 
    // creation Date is date 2 
    $dateTime1 = new DateTime($date1);
    $dateTime2 = new DateTime($date2);
    $dateTime1PlusTimeSlot = clone $dateTime1;
    $dateTime2MinusTimeSlot = clone $dateTime2;
    // $dateTime1PlusTimeSlot->modify("+{0} minutes");
    $dateTime2MinusTimeSlot->modify("+" . strval(intval($timeSlotInMinutes * (3 / 4))) . " minutes");
    // var_dump(intval($timeSlotInMinutes * (3 / 4)));
    if ($dateTime1PlusTimeSlot >= $dateTime2MinusTimeSlot) {
        return '0';
    }
    $dateTime1minusHalfTimeSlot = clone $dateTime1;
    $dateTime2PlusHalfTimeSlot = clone $dateTime2;
    // $dateTime1minusHalfTimeSlot->modify("-{0} minutes");
    $dateTime2PlusHalfTimeSlot->modify("+" . strval(intval($timeSlotInMinutes * (1 / 2))) . " minutes");
    if ($dateTime1minusHalfTimeSlot >= $dateTime2PlusHalfTimeSlot) {
        return '1';
    }
    return '2';
}

foreach ($innerData as $key => $value) {
    $alertChecking = $db->select('
    SELECT `check_points`.`id`, `check_points`.`start_date`, `check_points`.`end_date`, `check_points`.`current_location`,
    `check_points`.`routes_check_points_id`, `check_points`.`check_in_or_check_out_status`, `check_points`.`RDE_message_id`,
    `check_points`.`creation_date`,`routes_check_points`.`id` as \'routes_check_points_id_2\', `routes_check_points`.`route_id`,
    `routes_check_points`.`location_id`, `routes_check_points`.`time_slot`, `routes_check_points`.`order_number`,
    `routes_check_points`.`creation_date` as \'routes_creation_date\', `routes_check_points`.`last_modified_date`, `routes_check_points`.`status`
    FROM `check_points` INNER JOIN `routes_check_points` ON `routes_check_points`.`id` = `check_points`.`routes_check_points_id` WHERE `check_points`.`RDE_message_id` = ' . $value['id'])[0];
    // $cmsModeules = $db->select(
    //     'SELECT `cms_modules`.`id`, `cms_modules`.`name`, `cms_modules`.`status`,
    //  `cms_modules`.`link`, `cms_modules`.`icon`, `cms_modules`.`have_actions`,
    //   `users_prev`.`user_id`, `users_prev`.`edit`,
    //   `users_prev`.`add`, `users_prev`.`delete`, `users_prev`.`view`, `users_prev`.`module_id`
    //    FROM `cms_modules` inner join `users_prev` on `cms_modules`.`id` =`users_prev`.`module_id`
    //     WHERE `users_prev`.`user_id` = ' . $value["id"]
    // );
    $data[$key] = array(
        "order_number" => $value['order_number'],
        // "id" => $value['id'],
        "Medication" => $value["medication_code"] . "." . $value['medication_name'],
        // "PatientName" => $value['patient_id'] . '<br>' . $value['patient_name'] . $value['patient_last_name'],
        "Unit" => $value['unit'],
        // "Room" => $value["room"],
        // "Bed" => $value["bed"], 
        // "order_status" => $value["order_status"],
        "QTY" => $value["quantity"],


        // "Start_Time" => $value["start_date_time"],
        // "Current_Status" => '<div class="badge badge-teal d-flex flex-1 justify-content-center"> ' . $db->select('select * from `check_points` where `RDE_message_id`='.$value['id'].' order by `id` desc')[0]['current_location'] . ' </div>',
    );

    $orderCurrentStatus = $db->select('select * from `check_points` where `RDE_message_id`=' . $value['id'] . ' order by `id` desc')[0];
    // print_r($tempLocation[$orderCurrentStatus['current_location']]['check_in_status']);
    if (count($orderCurrentStatus)) {
        $status = $orderCurrentStatus['check_in_or_check_out_status'] == 0 ?  $tempLocation[$orderCurrentStatus['current_location']]['check_in_status'] : $tempLocation[$orderCurrentStatus['current_location']]['check_out_status'];
        $data[$key]["current_Status"] = '<div class="badge badge-teal d-flex flex-1 justify-content-center" style="height: 2rem;align-items: center;">' . $status . ' </div>';
        $data[$key]["Start_Time"] = $orderCurrentStatus['start_date'];
        $data[$key]["current_location"] = '<div class="badge badge-teal d-flex flex-1 justify-content-center" style="height: 2rem;align-items: center;">' . $tempLocation[$orderCurrentStatus['current_location']]['location_name'] . ' </div>';
        $data[$key]["current_user"] = '<div class="badge badge-teal d-flex flex-1 justify-content-center" style="height: 2rem;align-items: center;">' . $db->select('select * from `users` where `id` = ' . $orderCurrentStatus['user_id'])[0]['username'] . '</div>';
    } else {
        $data[$key]["current_Status"] = '-';
        $data[$key]["Start_Time"] = '-';
        $data[$key]["current_location"] = '-';
        $data[$key]["current_user"] = '-';
    }
    $data[$key]["Alert"] = '<div class="border-2 border-primary  d-flex  align-items-center justify-content-center " style="height:2rem;padding: 0.3125rem 0.4375rem;border-radius: 0.1875rem;width:5rem;';
    switch (compareDatesWithTimeSlot(date('Y-m-d H:i:s'), $alertChecking['routes_creation_date'], $alertChecking['time_slot'])) {
            // switch (compareDatesWithTimeSlot('2024-12-2 10:04:00', '2024-12-2 10:00:00', intval(10))) {
            // 0 red color
            // 1 yellow
            // 2 green
            // current Date is date 1 
            // creation Date is date 2 
        case 0:
            $data[$key]["Alert"] .= 'background-color:#fd0002;';
            break;
        case 1:
            $data[$key]["Alert"] .= 'background-color:#ffff01;';
            break;
        case 2:
            $data[$key]["Alert"] .= 'background-color:#077e03;';
            break;
    }
    $data[$key]["Alert"] .= '">';
    // $data[$key]["Alert"] .= compareDatesWithTimeSlot('2024-12-2 10:06:00', '2024-12-2 10:00:00', 10);
    $data[$key]["Alert"] .= '</div>';
    // $data[$key]["Actions"] =
    //     '


    //         <td class="text-center">
    //         <div class="list-icons">
    //             <div class="dropdown position-static">
    //                 <a href="datatable_basic.html#" class="list-icons-item" data-toggle="dropdown">
    //                     <i class="icon-menu9"></i>
    //                 </a>

    //                 <div class="dropdown-menu dropdown-menu-right">'
    //     // . ($_POST['section']['edit'] == 1 ?
    //     //     '<a href="index.php?cmd=users&suspend=1&id=' . $value['id'] . '" class="dropdown-item"><i class="icon-eye-blocked2"></i> Suspend </a>

    //     //             <a href="index.php?cmd=users&active=1&id=' . $value['id'] . '" class="dropdown-item"><i class="icon-eye2"></i> Activate </a>
    //     //             <a href="#" data-toggle="modal" data-target="#edit_popup' . $key . '" class="dropdown-item"><i class="icon-pencil7"></i> Edit</a>'
    //     //     : ''
    //     // ) 
    //     .
    //     ($_POST['section']['view'] == 1 ? '<a href="#" data-toggle="modal" data-target="#DetailsPopUp' . $key . '" class="dropdown-item"><i class="icon-question4"></i>Trace Details</a>' : '') .


    //     '        </div>
    //             </div>
    //         </div>
    //     </td>

    //     <div id="edit_popup' . $key . '" class="modal fade show"  aria-modal="true" role="dialog" >
    //     <form method="POST" enctype="multipart/form-data" >
    //     <input type="hidden" name="edit" value="' . $value['id'] . '">
    //         <div class="modal-dialog modal-full">
    //             <div class="modal-content">
    //                 <div class="modal-header bg-primary text-white">
    //                     <h5 class="modal-title">Edit Location Details</h5>
    //                     <button type="button" class="close" data-dismiss="modal">×</button>
    //                 </div> 
    //                 <div class="modal-body">

    //                 <div class="form-group row">
    //                 <div class="col-lg-6 p-2">
    //                         <div class="row">
    //                             <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">User Image</label>
    //                             <div class="col-lg-9 col-sm-12 col-md-9">
    //                                 <div class="input-group">
    //                                     <div class="custom-file">
    //                                         <input type="file" class="custom-file-input " id="img" name="img">
    //                                         <label class="custom-file-label" for="customFile">Choose file</label>
    //                                     </div>
    //                                 </div>
    //                             </div>
    //                         </div>
    //                 </div>

    //                 <div class="col-lg-6 p-2">
    //                     <div class="row">
    //                         <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">UserName</label>
    //                         <div class="col-lg-9 col-sm-12 col-md-9">
    //                             <div class="input-group">
    //                                 <span class="input-group-prepend">
    //                                     <span class="input-group-text bg-primary border-primary text-white">
    //                                         <i class="icon-pencil6"></i>
    //                                     </span>
    //                                 </span>
    //                                 <input type="text" class="form-control border-left-0" placeholder="UserName" name="username" value="' . $value['username'] . '" required="">
    //                             </div>
    //                         </div>
    //                     </div>
    //                 </div>

    //                 <div class=" col-lg-6 p-2">
    //                     <div class="row">
    //                         <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">User Password</label>
    //                         <div class="col-lg-9 col-sm-12 col-md-9">
    //                             <div class="input-group">
    //                                 <span class="input-group-prepend">
    //                                     <span class="input-group-text bg-primary border-primary text-white">
    //                                         <i class="icon-file-locked2"></i>
    //                                     </span>
    //                                 </span>
    //                                 <input type="password" class="form-control border-left-0" placeholder="password" name="password" >
    //                             </div>
    //                         </div>
    //                     </div>
    //                 </div>

    //                 <div class="col-lg-6 p-2">
    //                     <div class="row">
    //                         <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">User Email</label>
    //                         <div class="col-lg-9 col-sm-12 col-md-9">
    //                             <div class="input-group">
    //                                 <span class="input-group-prepend">
    //                                     <span class="input-group-text bg-primary border-primary text-white">
    //                                         <i class="icon-pencil6"></i>
    //                                     </span>
    //                                 </span>
    //                                 <input type="text" class="form-control border-left-0" placeholder="User Email" name="email" value="' . $value['email'] . '" required="">
    //                             </div>
    //                         </div>
    //                     </div>
    //                 </div>

    //                 <div class="col-lg-6 p-2">
    //                     <div class="row">
    //                         <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">Full Name</label>
    //                         <div class="col-lg-9 col-sm-12 col-md-9">
    //                             <div class="input-group">
    //                                 <span class="input-group-prepend">
    //                                     <span class="input-group-text bg-primary border-primary text-white">
    //                                         <i class="icon-pencil6"></i>
    //                                     </span>
    //                                 </span>
    //                                 <input type="text" class="form-control border-left-0" placeholder="Full Name" name="full_name" value="' . $value['full_name'] . '" required="">
    //                             </div>
    //                         </div>
    //                     </div>
    //                 </div>

    //                 <div class="col-lg-6 p-2">
    //                     <div class="row">
    //                         <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">Phone Number</label>
    //                         <div class="col-lg-9 col-sm-12 col-md-9">
    //                             <div class="input-group">
    //                                 <span class="input-group-prepend">
    //                                     <span class="input-group-text bg-primary border-primary text-white">
    //                                         <i class="icon-pencil6"></i>
    //                                     </span>
    //                                 </span>
    //                                 <input type="text" class="form-control border-left-0" placeholder="Phone Number" name="phone_number" value="' . $value['phone_number'] . '" required="">
    //                             </div>
    //                         </div>
    //                     </div>
    //                 </div>

    //                 <div class="col-lg-6 p-2">
    //                     <div class="row">
    //                         <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">Address</label>
    //                         <div class="col-lg-9 col-sm-12 col-md-9">
    //                             <div class="input-group">
    //                                 <span class="input-group-prepend">
    //                                     <span class="input-group-text bg-primary border-primary text-white">
    //                                         <i class="icon-pencil6"></i>
    //                                     </span>
    //                                 </span>
    //                                 <input type="text" class="form-control border-left-0" placeholder="User Adress" name="address" value="' . $value['address'] . '" required="">
    //                             </div>
    //                         </div>
    //                     </div>
    //                 </div>
    //                 <div class="col-lg-6 p-2">
    //                     <div class="row">
    //                         <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">User Role</label>
    //                         <div class="col-lg-9 col-sm-12 col-md-9">
    //                             <div class="input-group">
    //                                 <span class="input-group-prepend">
    //                                     <span class="input-group-text bg-primary border-primary text-white">
    //                                         <i class="icon-pencil6"></i>
    //                                     </span>
    //                                 </span>
    //                                 <input type="text" class="form-control border-left-0" placeholder="User Role" name="role" value="' . $value['role'] . '" required="">
    //                             </div>
    //                         </div>
    //                     </div>
    //                 </div>
    //                 <div class="row p-2">
    //                     <div class="custom-control custom-control-right custom-switch text-right p-2">
    //                         <input type="checkbox" class="custom-control-input" id="sc_rs_c' . $value['id'] . '" name="status" ' . ($value['status'] == 1 ? 'checked=""' : '') . '>
    //                         <label class="custom-control-label" for="sc_rs_c' . $value['id'] . '">Active</label>
    //                     </div>
    //                 </div>
    //                 <div style="width:100%;height:1px;"></div>

    //                 <div class ="row p-2 justify-content-center">';
    // /* foreach ($cmsModeules as $modulesKey => $module) {
    //     $data[$key]["Actions"] .=
    //         '<div class=" col-lg-6 col-md-12 col-sm-12 ">
    //                                                         <div id="prev-block ' .
    //         $module["id"] .
    //         $value["id"] .
    //         '" class="card">
    //                                                             <div class="card-header header-elements-inline">
    //                                                                 <h6 class="card-title">' .
    //         $module["name"] .
    //         '</h6>
    //                                                             </div>
    //                                                             <div class="card-body ">
    //                                                                 <ul class="media-list row justify-content-between " style="align-items: baseline;">
    //                                                                     <li class="media  col-lg-3 col-md-4 col-sm-6">
    //                                                                         <div class="mr-3 ">
    //                                                                             <div class="custom-control custom-checkbox">
    //                                                                                 <input type="checkbox" class="custom-control-input crud" id="add' .
    //         $module["id"] .
    //         $value["id"] .
    //         '" name="add' .
    //         $module["id"] .
    //         $value["id"] .
    //         '" ' .
    //         ($module["add"] == 1 ? "checked" : "") .
    //         '>
    //                                                                                 <label for="add' .
    //         $module["id"] .
    //         $value["id"] .
    //         '" class="custom-control-label p-0"></label>
    //                                                                             </div>
    //                                                                         </div>
    //                                                                         <div class="media-body ">
    //                                                                             <h6 class="media-title">
    //                                                                                 <label for="add' .
    //         $module["id"] .
    //         $value["id"] .
    //         '" class="font-weight-semibold cursor-pointer mb-0">Add</label>
    //                                                                             </h6>
    //                                                                         </div>
    //                                                                     </li>
    //                                                                     <li class="media  col-lg-3 col-md-4 col-sm-6">
    //                                                                         <div class="mr-3">
    //                                                                             <div class="custom-control custom-checkbox">
    //                                                                                 <input type="checkbox" class="custom-control-input crud" id="edit' .
    //         $module["id"] .
    //         $value["id"] .
    //         '" name="edit' .
    //         $module["id"] .
    //         $value["id"] .
    //         '" ' .
    //         ($module["edit"] == 1 ? "checked" : "") .
    //         '>
    //                                                                                 <label for="edit' .
    //         $module["id"] .
    //         $value["id"] .
    //         '" class="custom-control-label p-0"></label>
    //                                                                             </div>
    //                                                                         </div>
    //                                                                         <div class="media-body ">
    //                                                                             <h6 class="media-title">
    //                                                                                 <label for="edit' .
    //         $module["id"] .
    //         $value["id"] .
    //         '" class="font-weight-semibold cursor-pointer mb-0">Edit</label>
    //                                                                             </h6>
    //                                                                         </div>
    //                                                                     </li>
    //                                                                     <li class="media  col-lg-3 col-md-4 col-sm-6">
    //                                                                         <div class="mr-3">
    //                                                                             <div class="custom-control custom-checkbox">
    //                                                                                 <input type="checkbox" class="custom-control-input crud" id="delete' .
    //         $module["id"] .
    //         $value["id"] .
    //         '" name="delete' .
    //         $module["id"] .
    //         $value["id"] .
    //         '" ' .
    //         ($module["delete"] == 1 ? "checked" : "") .
    //         '>
    //                                                                                 <label for="delete' .
    //         $module["id"] .
    //         $value["id"] .
    //         '" class="custom-control-label p-0"></label>
    //                                                                             </div>
    //                                                                         </div>
    //                                                                         <div class="media-body ">
    //                                                                             <h6 class="media-title">
    //                                                                                 <label for="delete' .
    //         $module["id"] .
    //         $value["id"] .
    //         '" class="font-weight-semibold cursor-pointer mb-0">Delete</label>
    //                                                                             </h6>
    //                                                                         </div>
    //                                                                     </li>
    //                                                                     <li class="media  col-lg-3 col-md-4 col-sm-6">
    //                                                                         <div class="mr-3">
    //                                                                             <div class="custom-control custom-checkbox">
    //                                                                                 <input type="checkbox" class="custom-control-input" id="view' .
    //         $module["id"] .
    //         $value["id"] .
    //         '" name="view' .
    //         $module["id"] .
    //         $value["id"] .
    //         '" ' .
    //         ($module["view"] == 1 ? "checked" : "") .
    //         '>
    //                                                                                 <label for="view' .
    //         $module["id"] .
    //         $value["id"] .
    //         '"  class="custom-control-label p-0"></label>
    //                                                                             </div>
    //                                                                         </div>
    //                                                                         <div class="media-body ">
    //                                                                             <h6 class="media-title">
    //                                                                                 <label for="view' .
    //         $module["id"] .
    //         $value["id"] .
    //         '" class="font-weight-semibold cursor-pointer mb-0">View</label>
    //                                                                             </h6>
    //                                                                         </div>
    //                                                                     </li>
    //                                                                 </ul>
    //                                                             </div>
    //                                                         </div>
    //                                                     </div>';
    // } */
    // foreach ($cmsModeules as $modulesKey => $module) {
    //     $data[$key]["Actions"] .=
    //         '<div class="col-lg-6 col-md-12 col-sm-12">
    //         <div id="prev-block ' . $module["id"] . $value["id"] . '" class="card">
    //             <div class="card-header header-elements-inline">
    //                 <h6 class="card-title">' . $module["name"] . '</h6>
    //             </div>
    //             <div class="card-body">
    //                 <ul class="media-list row justify-content-between" style="align-items: baseline;">
    //                     <li class="media col-lg-3 col-md-4 col-sm-6">
    //                         <div class="mr-3">
    //                             <div class="custom-control custom-checkbox">
    //                                 <input type="checkbox" class="custom-control-input crud" id="add' . $module["id"] . $value["id"] . '" name="add' . $module["id"] . $value["id"] . '" ' . ($module["add"] == 1 ? "checked" : "") . '>
    //                                 <label for="add' . $module["id"] . $value["id"] . '" class="custom-control-label p-0"></label>
    //                             </div>
    //                         </div>
    //                         <div class="media-body">
    //                             <h6 class="media-title">
    //                                 <label for="add' . $module["id"] . $value["id"] . '" class="font-weight-semibold cursor-pointer mb-0">Add</label>
    //                             </h6>
    //                         </div>
    //                     </li>
    //                     <li class="media col-lg-3 col-md-4 col-sm-6">
    //                         <div class="mr-3">
    //                             <div class="custom-control custom-checkbox">
    //                                 <input type="checkbox" class="custom-control-input crud" id="edit' . $module["id"] . $value["id"] . '" name="edit' . $module["id"] . $value["id"] . '" ' . ($module["edit"] == 1 ? "checked" : "") . '>
    //                                 <label for="edit' . $module["id"] . $value["id"] . '" class="custom-control-label p-0"></label>
    //                             </div>
    //                         </div>
    //                         <div class "media-body">
    //                             <h6 class="media-title">
    //                                 <label for="edit' . $module["id"] . $value["id"] . '" class="font-weight-semibold cursor-pointer mb-0">Edit</label>
    //                             </h6>
    //                         </div>
    //                     </li>
    //                     <li class="media col-lg-3 col-md-4 col-sm-6">
    //                         <div class="mr-3">
    //                             <div class="custom-control custom-checkbox">
    //                                 <input type="checkbox" class="custom-control-input crud" id="delete' . $module["id"] . $value["id"] . '" name="delete' . $module["id"] . $value["id"] . '" ' . ($module["delete"] == 1 ? "checked" : "") . '>
    //                                 <label for="delete' . $module["id"] . $value["id"] . '" class="custom-control-label p-0"></label>
    //                             </div>
    //                         </div>
    //                         <div class="media-body">
    //                             <h6 class="media-title">
    //                                 <label for="delete' . $module["id"] . $value["id"] . '" class="font-weight-semibold cursor-pointer mb-0">Delete</label>
    //                             </h6>
    //                         </div>
    //                     </li>
    //                     <li class="media col-lg-3 col-md-4 col-sm-6">
    //                         <div class="mr-3">
    //                             <div class="custom-control custom-checkbox">
    //                                 <input type="checkbox" class="custom-control-input" id="view' . $module["id"] . $value["id"] . '" name="view' . $module["id"] . $value["id"] . '" ' . ($module["view"] == 1 ? "checked" : "") . '>
    //                                 <label for="view' . $module["id"] . $value["id"] . '" class="custom-control-label p-0"></label>
    //                             </div>
    //                         </div>
    //                         <div class="media-body">
    //                             <h6 class="media-title">
    //                                 <label for="view' . $module["id"] . $value["id"] . '" class="font-weight-semibold cursor-pointer mb-0">View</label>
    //                             </h6>
    //                         </div>
    //                     </li>
    //                 </ul>
    //             </div>
    //         </div>
    //     </div>';
    // }
    // $data[$key]["Actions"] .= '</div>


    //             </div>
    //                 </div>
    //                 <div class="modal-footer">
    //                     <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="icon-cross2 font-size-base mr-1"></i> Close</button>
    //                     <button type="submit" class="btn btn-primary"> <i class="icon-checkmark3 font-size-base mr-1"></i> Save changes</button>
    //                 </div>
    //             </div>
    //         </div>
    //         </form>
    //     </div>
    //     <div id="DetailsPopUp' . $key . '" class="modal fade show" aria-modal="false" role="dialog" >
    //     <form method="POST" >
    //     <input type="hidden" name="delete" value="' . $value['id'] . '">
    //                         <div class="modal-dialog modal-dialog-scrollable modal-full">
    //                             <div class="modal-content">
    //                                 <div class="modal-header bg-primary text-white">
    //                                     <h6 class="modal-title">Trace Details</h6>
    //                                     <button type="button" class="close" data-dismiss="modal">×</button>
    //                                 </div>

    //                                 <div class="modal-body row">
    //                                 <div class="col-lg-6 col-md-6 col-sm-12" >
    //                                     <div class="text-left m-1">
    //                                         <span class="font-weight-semibold">Message Control Id:</span>
    //                                         <span class="">' . $value['message_control_id'] . '</span>
    //                                     </div>
    //                                     <div class="text-left m-1">
    //                                         <span class="font-weight-semibold">Patient ID:</span>
    //                                         <span class="">' . $value['patient_id'] . '</span>
    //                                     </div>
    //                                     <div class="text-left m-1">
    //                                         <span class="font-weight-semibold">Patient Name:</span>
    //                                         <span class="">' . $value['patient_name'] . '</span>
    //                                     </div>
    //                                     <div class="text-left m-1">
    //                                         <span class="font-weight-semibold">Patient Last Name:</span>
    //                                         <span class="">' . $value['patient_last_name'] . '</span>
    //                                     </div>
    //                                     <div class="text-left m-1">
    //                                         <span class="font-weight-semibold">Patient Middle Name:</span>
    //                                         <span class="">' . $value['patient_third_name'] . '</span>
    //                                     </div>
    //                                     <div class="text-left m-1">
    //                                         <span class="font-weight-semibold">Patient Date Of Birth:</span>
    //                                         <span class="">' . substr($value['patient_dof'], 0, 4) . '/' . substr($value['patient_dof'], 4, 2) . '/' . substr($value['patient_dof'], 6, 2) . '  ' . substr($value['patient_dof'], 8, 2) . ':' . substr($value['patient_dof'], 10, 2) . ':' . substr($value['patient_dof'], 12, 2) . '</span>

    //                                     </div>
    //                                     <div class="text-left m-1">
    //                                         <span class="font-weight-semibold">Room:</span>
    //                                         <span class="">' . $value['room'] . '</span>
    //                                     </div>
    //                                     <div class="text-left m-1">
    //                                             <span class="font-weight-semibold">Bed:</span>
    //                                             <span class="">' . $value['bed'] . '</span>
    //                                     </div>
    //                                     <div class="text-left m-1">
    //                                             <span class="font-weight-semibold">Medication ID:</span>
    //                                             <span class="">' . $value['medication_id'] . '</span>
    //                                     </div>

    //                                 </div>
    //                                 <div class="col-lg-6 col-md-6 col-sm-12" >
    //                                 <div class="text-left m-1">
    //                                 <span class="font-weight-semibold">Medication Code:</span>
    //                                 <span class="">' . $value['medication_code'] . '</span>
    //                         </div>
    //                         <div class="text-left m-1">
    //                                 <span class="font-weight-semibold">Medication Name:</span>
    //                                 <span class="">' . $value['medication_name'] . '</span>
    //                         </div>
    //                         <div class="text-left m-1">
    //                                 <span class="font-weight-semibold">Interval:</span>
    //                                 <span class="">' . $value['interval'] . '</span>
    //                         </div>
    //                         <div class="text-left m-1">
    //                                 <span class="font-weight-semibold">Dosage:</span>
    //                                 <span class="">' . $value['dosage'] . '</span>
    //                         </div>
    //                         <div class="text-left m-1">
    //                                 <span class="font-weight-semibold">Quantity:</span>
    //                                 <span class="">' . $value['quantity'] . '</span>
    //                         </div>
    //                         <div class="text-left m-1">
    //                                 <span class="font-weight-semibold">Unit:</span>
    //                                 <span class="">' . $value['unit'] . '</span>
    //                         </div>
    //                         <div class="text-left m-1">
    //                                 <span class="font-weight-semibold">Frequency:</span>
    //                                 <span class="">' . $value['frequency'] . '</span>
    //                         </div>
    //                         <div class="text-left m-1">
    //                                 <span class="font-weight-semibold">Order Provider Name:</span>
    //                                 <span class="">' . $value['order_provider_name'] . '</span>
    //                         </div>
    //                         <div class="text-left m-1">
    //                                 <span class="font-weight-semibold">Order Date Time:</span>
    //                                 <span class="">' . substr($value['order_date_time'], 0, 4) . '/' . substr($value['order_date_time'], 4, 2) . '/' . substr($value['order_date_time'], 6, 2) . '  ' . substr($value['order_date_time'], 8, 2) . ':' . substr($value['order_date_time'], 10, 2) . ':' . substr($value['order_date_time'], 12, 2) . '</span>
    //                         </div>
    //                     </div>
    //                     ';
    // $checkPoints = $db->select('select * from `check_points` where `RDE_message_id` =  ' . $value['id'] . ' order by `id` desc');
    // if (count($checkPoints)) {
    //     $data[$key]['Actions'] .= '
    //                     <table class="table datatable-button-html5-columns table-bordered dataTable m-1">
    //                         <thead>
    //                             <tr class="bg-primary">
    //                                 <th class="text-white">Status</th>
    //                                 <th class="text-white">Location</th>
    //                                 <th class="text-white">Start Time</th>
    //                                 <th class="text-white">End Time</th>
    //                                 <th class="text-white">Date</th>
    //                             </tr>';



    //     foreach ($checkPoints as $checkPointkey => $checkPoint) {

    //         $data[$key]['Actions'] .=
    //             '<tr>
    //                                     <td>' . ($checkPoint['check_in_or_check_out_status'] == 0 ? $tempLocation[$checkPoint['current_location']]['check_in_status'] : $tempLocation[$checkPoint['current_location']]['check_out_status']) . '</td>
    //                                     <td>' . $tempLocation[$checkPoint['current_location']]['location_name'] . '</td>
    //                                     <td>' . $checkPoint['start_date'] . '</td>
    //                                     <td>' . $checkPoint['end_date'] . '</td>
    //                                     <td>' . $checkPoint['creation_date'] . '</td>

    //                                 </tr>';
    //     }
    //     $data[$key]['Actions'] .= '</thead>
    //                         <tbody>


    //                         </tbody>
    //                     </table>';
    // } else {
    //     // $data[$key]['Actions'] .='<h6 class= "text-danger m-1">This order undispensed yest</h6>';    
    // }

    // $data[$key]['Actions'] .= '




    //                     </div>

    //                     <div class="modal-footer">
    //                         <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    //                         <button type="sumbit" class="btn btn-primary ">Save changes</button>
    //                     </div>
    //     </form>
    //     </div>
    //     </div>
    //     </div>
    //     </div>

    //     ';
}
echo json_encode(array(
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $filteredRecords,
    "data" => $data
));
die;
