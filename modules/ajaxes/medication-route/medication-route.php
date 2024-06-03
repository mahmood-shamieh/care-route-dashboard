<?php

require "../../../includes/conf.php";

$columns = [

    // 0 => 'Name',

    0 => 'Medications_types',
    1 => 'related_medications',

    2 => 'Route',

    3 => 'Status',

    4 => 'Created_date',

    5 => 'Last_date_modified',

    6 => 'Actions',

];



$query = "SELECT * FROM `routes`";

$temp = $db->select('select * from `locations` where `active` = 1');

$computedLocations = array();

foreach ($temp as $key => $value) {



    $computedLocations[$value['location_id']] = $value;
}

$temp = $db->select('select * from `medication_type` where `status` = 1');

$computedMedicationType = array();

foreach ($temp as $key => $value) {

    $computedMedicationType[$value['id']] = $value;
}

$totalRecords = count($db->select("SELECT * FROM `routes`"));

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

$query .= " ORDER BY `routes`.`last_modified_date` desc";

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

$medicationsTypes = $db->select('select * from `medication_type` where `status` = 1');

$locations = $db->select('select * from `locations` where `active` = 1');

// print_r($_POST['section']);

// die;

foreach ($innerData as $key => $value) {

    $data[$key] = array(



        // "Name" => $value['id'] . ' . ' . $value["name"],

        "Medications_types" => $computedMedicationType[$value['medication_type_id']]['id'] . ' . ' . $computedMedicationType[$value['medication_type_id']]["name"],
        "Medications_types" => $computedMedicationType[$value['medication_type_id']]['id'] . ' . ' . $computedMedicationType[$value['medication_type_id']]["name"],

        "Status" => $value["status"] == 1 ? '<div class="badge badge-success d-flex flex-1 justify-content-center"> Active </div>' : '<div class="badge badge-danger d-flex flex-1 justify-content-center"> Susspend </div>',

        "Created_date" => $value["created_date"],

        "Last_date_modified" => $value["last_modified_date"],

        "Actions" => ''





    );
    $data[$key]['related_medications'] .= "<a href='#' data-toggle='modal' data-target='#related_medications_pop_up" . $key . "'class='btn btn-primary d-flex justify-content-center flex-1'>Related Medications</a>";
    $data[$key]['related_medications'] .= '<div id="related_medications_pop_up' . $key . '" class="modal fade show" aria-modal="false" role="dialog" >

        

    <input type="hidden" name="delete" value="' . $value['id'] . '">

                        <div class="modal-dialog">

                            <div class="modal-content">

                                <div class="modal-header bg-primary text-white">

                                    <h6 class="modal-title">Related Medications</h6>

                                    <button type="button" class="close" data-dismiss="modal">×</button>

                                </div>
                                <div class="modal-body"><h5>Empty</h5> </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>';

    $data[$key]['Actions'] = '

    <td class="text-center">

    <div class="list-icons">

        <div class="dropdown position-static">

            <a href="datatable_basic.html#" class="list-icons-item" data-toggle="dropdown">

                <i class="icon-menu9"></i>

            </a>

            <div class="dropdown-menu dropdown-menu-right">' . ($_POST['section']['edit'] == 1 ?

        '<a href="index.php?cmd=' . $_POST['cmd'] . '&suspend=1&id=' . $value['id'] . '"

                    class="dropdown-item"><i class="icon-eye-blocked2"></i> Suspend </a>



                <a href="index.php?cmd=' . $_POST['cmd'] . '&active=1&id=' . $value['id'] . '" class="dropdown-item"><i

                        class="icon-eye2"></i> Activate </a>

                <a href="#" data-toggle="modal" data-target="#edit_popup' . $value['id'] . '" class="dropdown-item"><i

                        class="icon-pencil7"></i> Edit</a>'

        : ''

    ) .

        ($_POST['section']['delete'] == 1 ? '<a href="#" data-toggle="modal"

                    data-target="#delete_pop_up' . $key . '" class="dropdown-item"><i class="icon-trash"></i>Delete</a>'

            : '') .

        '

            </div>

        </div>

    </div>

</td>

<div id="edit_popup' . $value['id'] . '" class="modal fade show" aria-modal="true" role="dialog">

    <form method="POST" enctype="multipart/form-data">

        <input type="hidden" name="edit" value="' . $value['id'] . '">

        <div class="modal-dialog modal-full">

            <div class="modal-content">

                <div class="modal-header bg-primary text-white">

                    <h5 class="modal-title">Edit Medication Route</h5>

                    <button type="button" class="close" data-dismiss="modal">×</button>

                </div>

                <div class="modal-body">

                    <div class="form-group row">

                        <div class="col-lg-6 p-2">

                            <div class="row">

                                <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">Route Name</label>

                                <div class="col-lg-9 col-sm-12 col-md-9">

                                    <div class="input-group">

                                        <span class="input-group-prepend">

                                            <span class="input-group-text bg-primary border-primary text-white">

                                                <i class="icon-pencil6"></i>

                                            </span>

                                        </span>

                                        <input type="text" class="form-control border-left-0"

                                            placeholder="routeName" name="name" value="' . $value['name'] . '" required="">

                                    </div>

                                </div>

                            </div>

                        </div>

                    

                        <div  class=" col-lg-6 p-2">

                            <div class="row form-group">

                                <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">Medications Types</label>

                                <div class="col-lg-9 col-sm-12 col-md-9">

                                

                                    <select class="form-control select" data-fouc aria-hidden="false" name="medicationType" >';

    foreach ($medicationsTypes as $medicationKey => $medicationsType) {



        $data[$key]['Actions'] .= '<option value="' . $medicationsType['id'] . '" ' . ($medicationsType['id'] == $value['medication_type_id'] ? 'selected' : '') . '>' . $medicationsType['name'] . '</option>';
    }



    $data[$key]['Actions'] .= '</select>

                                                        </div>

                                                    </div>

                                                </div>';

    $data[$key]['Actions'] .= '

                        <div class=" col-lg-12 p-2 border-primary border-2 p-2 rounded-lg text-left">



                                                <h5>Route locations</h5>

                                                <h6>Note that when you add a new location, all time slot for old locations will return to the last entered time, so please first add new location then secondly edit time slot for old locations if necessary. </h6>

                                                

                                                <div id="locationsArea' . $value['id'] . '" class="row m-2">



                                                </div>



                                                <div class="border-primary border-2 p-2 rounded-lg m-2">

                                                <div class="row p-2">

                                                    <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">Location Name : </label>

                                                    <div class="col-lg-6 col-sm-12 col-md-9">

                                                        <select id="locations' . $value['id'] . '" class="form-control select" data-fouc aria-hidden="false">

                                                            <option value="0">Click To Select</option>';

    foreach ($locations as $locationKey => $location) {

        $data[$key]['Actions'] .= '<option value="' . $location['location_name'] . '" location_id="' . $location['location_id'] . '">' . $location['location_name'] . '</option>';
    }

    $data[$key]['Actions'] .= "

                        <script>

                        function deleteElementAtIndex" . $value['id'] . "(arr, key) {

                            console.log(arr.length);

                            // console.log(index);

                            let index;

                            arr.forEach(element => {

                                if (element['id'] == key) {

                                    index = arr.indexOf(element);

                                }

                            });

                            if (index >= 0 && index <= arr.length) {

                                arr.splice(index, 1); // Delete 1 element at the specified index

                            } else {

                                console.error('Index out of range.');

                            }

                            update" . $value['id'] . "();

                        }



                        function update" . $value['id'] . "() {

                            console.log(routes" . $value['id'] . ".length);

                            $('#locationsArea" . $value['id'] . "').empty();

                            console.log(routes" . $value['id'] . ");

                            if (routes" . $value['id'] . ".length == 0) {

                                $('#noSelectedLocationsTitle" . $value['id'] . "').css('display', 'block');

                            } else {

                                routes" . $value['id'] . ".forEach(element => {

                                    console.log(element['block']);

                                    $('#locationsArea" . $value['id'] . "').append(element['input']);

                                    $('#locationsArea" . $value['id'] . "').append(element['block']);

                                });

                            }

                        }

                        let i" . $value['id'] . " = 1;

                        i" . $value['id'] . "++;

                        

                        

                        let routes" . $value['id'] . " = [";

    $oldRoutes = $db->select('select * from `routes_check_points` where `route_id` = ' . $db->sqlsafe($value['id']));

    // print_r($oldRoutes);

    foreach ($oldRoutes as $routeKey => $oldRoute) {

        if ($routeKey == 0) {

            $data[$key]['Actions'] .= "{

                                    'id': " . $routeKey . ",

                                    'input': '<input type=\"hidden\" name=\"locations" . $value['id'] . "[]\" value=\"" . $oldRoute['location_id'] . "\">',

                                    'block': '<div class=\"mb-2 d-flex\" style=\"transition: .3s;\"><div class=\"border-2 p-2  border-primary rounded-lg\" style=\"width: fit-content;border-color: var(--primary-color);\"><button class=\"rounded-lg border-2 border-primary\" onclick=\"deleteElementAtIndex" . $value['id'] . "(routes" . $value['id'] . "," . $routeKey . ")\"><i class=\"fa-solid fa-xmark p-1 m-1 bg-primary rounded-lg\" style=\"color:#fff;\"></i></button> " . $computedLocations[$oldRoute['location_id']]['location_name'] . "<br><label >Time Slot: </label><input id=\"test\" type=\"number\" name=\"times" . $value['id'] . "[]\" class=\"border-primary border-2 rounded-lg m-2 w-50\" style=\"text-align:center;\" value=\"" . $oldRoute['time_slot'] . "\"></div></div>',

                                },";
        } else {

            $data[$key]['Actions'] .= "{

                                    'id': " . $routeKey . ",

                                    'input': '<input type=\"hidden\" name=\"locations" . $value['id'] . "[]\" value=\"" . $oldRoute['location_id'] . "\">',

                                    'block': '<div class=\"d-flex justify-content-center align-items-center\"><div style=\"width: 30px;height: 2px;\" class=\"bg-primary d-inline-block\"></div></div><div class=\"mb-2 d-flex\" style=\"transition: .3s;\"><div class=\"border-2 p-2  border-primary rounded-lg\" style=\"width: fit-content;border-color: var(--primary-color);\"><button class=\"rounded-lg border-2 border-primary\" onclick=\"deleteElementAtIndex" . $value['id'] . "(routes" . $value['id'] . "," . $routeKey . ")\"><i class=\"fa-solid fa-xmark p-1 m-1 bg-primary rounded-lg\" style=\"color:#fff;\"></i></button> " . $computedLocations[$oldRoute['location_id']]['location_name'] . "<br><label >Time Slot: </label><input id=\"test\" type=\"number\" name=\"times" . $value['id'] . "[]\" class=\"border-primary border-2 rounded-lg m-2 w-50\" style=\"text-align:center;\" value=\"" . $oldRoute['time_slot'] . "\"></div></div>',

                                },";
        }
    }



    $data[$key]['Actions'] .= "

                        ];

                        console.log(routes" . $value['id'] . ");

                        update" . $value['id'] . "();

                        $('#locations" . $value['id'] . "').on('change', function() {

                            // $(this).val('');

                        })

                        

                        $('#addNewCheckPointButton" . $value['id'] . "').on('click', function() {

                            let value" . $value['id'] . " = $('#timeSlot" . $value['id'] . "').val();

                            console.log(value" . $value['id'] . ");

                            let i" . $value['id'] . " = routes" . $value['id'] . ".length;

                            let temp" . $value['id'] . ";

                            let step" . $value['id'] . " = '<div class=\"d-flex justify-content-center align-items-center\"><div style=\"width: 30px;height: 2px;\" class=\"bg-primary d-inline-block\"></div></div>';

                            let block" . $value['id'] . " = '<div class=\"mb-2 d-flex\" style=\"transition: .3s;\"><div class=\"border-2 p-2  border-primary rounded-lg\" style=\"width: fit-content;border-color: var(--primary-color);\"><button class=\"rounded-lg border-2 border-primary\" onclick=\"deleteElementAtIndex" . $value['id'] . "(routes" . $value['id'] . ",'+i" . $value['id'] . "+')\"><i class=\"fa-solid fa-xmark p-1 m-1 bg-primary rounded-lg\" style=\"color:#fff;\"></i></button> '+$('#locations" . $value['id'] . "').val()+'<br><label >Time Slot: </label> <input id=\"aaaaa" . $value['id'] . "\" type=\"number\" name=\"times" . $value['id'] . "[]\" class=\"border-primary border-2 rounded-lg m-2 w-50\" style=\"text-align:center;\" value=\"'+value" . $value['id'] . "+'\"> </div>';

                            let endOfBlock" . $value['id'] . " = `</div>`;

                            if ($('#locations" . $value['id'] . "').val() != 0) {

                                

                                

                                if (routes" . $value['id'] . ".length == 0) {

                                    temp" . $value['id'] . " = {

                                        'id': i" . $value['id'] . ",

                                        'input': '<input type=\"hidden\" name=\"locations" . $value['id'] . "[]\" value=\"'+$('#locations" . $value['id'] . " option:selected').attr('location_id')+'\">',

                                        'block': block" . $value['id'] . "+endOfBlock" . $value['id'] . ",

                                    };

                                } else {

                                    temp" . $value['id'] . " = {

                                        'id': i" . $value['id'] . ",

                                        'input': '<input type=\"hidden\" name=\"locations" . $value['id'] . "[]\" value=\"'+$('#locations" . $value['id'] . " option:selected').attr('location_id')+'\">',

                                        'block': step" . $value['id'] . "+block" . $value['id'] . "+endOfBlock" . $value['id'] . ",

                                    };

                                } 

                                i" . $value['id'] . "++;



                                routes" . $value['id'] . ".push(temp" . $value['id'] . ");

                                $('#noSelectedLocationsTitle" . $value['id'] . "').css('display', 'none');

                                $('#locations" . $value['id'] . "').val('0');

                                $('#timeSlot" . $value['id'] . "').val('1'); 

                                update" . $value['id'] . "();

                            }



                            // console.log($('#locations').val());

                        });

                    </script>";

    $data[$key]['Actions'] .= '</select>

                                                    </div>

                                                    </div> 

                                                    <div class="row p-2">

                                                        <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">Location Time Slot Per Minute:</label>

                                                        <div class="col-lg-6 col-sm-12 col-md-9">

                                                            <div class="input-group">

                                                                <span class="input-group-prepend">

                                                                    <span class="input-group-text bg-primary border-primary text-white">

                                                                        <i class="icon-file-locked2"></i>

                                                                    </span>

                                                                </span>

                                                                <input id="timeSlot' . $value['id'] . '" type="number" class="form-control border-left-0" placeholder="Location Time Slot Per Minute" name="timeSlot' . $value['id'] . '" required="" value="1">

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="row">

                                                    <div style="width: 74%;height: 1px;"></div>

                                                    <button id="addNewCheckPointButton' . $value['id'] . '" type="button" class="btn btn-primary col-lg-3">Add New Location</button>

                                                    </div>

                                                </div>



                                            </div>





                                            <div class="row p-2">

                                                <div class="custom-control custom-control-right custom-switch text-right p-2">

                                                    <input type="checkbox" class="custom-control-input" id="sc_rs_c' . $value['id'] . '"

                                                        name="status" ' . ($value['status'] == 1 ? 'checked=""' : '') . '>

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

                                                    <button type="submit" class="btn btn-danger">Delete</button>

                                                </div>

                                                </form>

                                            </div>

                                        </div>

                                    </div>

                                    </div>';





    $routes = $db->select('select * from `routes_check_points` where `route_id` = ' . $db->sqlsafe($value['id']));

    if (count($routes)) {



        $data[$key]['Route'] .= '<div id="route_pop_up' . $key . '" class="modal fade show" aria-modal="false" role="dialog" >

        

        <input type="hidden" name="delete" value="' . $value['id'] . '">

                            <div class="modal-dialog">

                                <div class="modal-content">

                                    <div class="modal-header bg-primary text-white">

                                        <h6 class="modal-title">Route Checkpoints</h6>

                                        <button type="button" class="close" data-dismiss="modal">×</button>

                                    </div>
                                    <div class="modal-body">';
        for ($i = 0; $i < count($routes); $i++) {
            $data[$key]['Route'] .= '
                <div class="d-flex flex-column justify-content-center align-items-center">
                <div class="rounded-lg border-2 border-primary p-1 d-flex flex-1" >' .
                $computedLocations[$routes[$i]['location_id']]['location_name'] .
                "Time Slot :" . $routes[$i]['time_slot'] . '(Minutes)
                                            </div>';

            if (count($routes) - 1 == $i) {
            } else {

                $data[$key]['Route'] .= "<div style='background-color:var(--primary-color); width:2px;height:20px;'></div>";
            }

            $data[$key]['Route'] .= "</div>";
        }

        $data[$key]['Route'] .= '
        </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>';
        $data[$key]['Route'] .= "<a href='#' data-toggle='modal' data-target='#route_pop_up" . $key . "'class='btn btn-primary d-flex justify-content-center flex-1'>Route</a>";

        // for ($i = 0; $i < count($routes); $i++) {



        //     $data[$key]['Route'] .= "<div class='d-flex flex-column justify-content-center align-items-center'><div class='rounded-lg border-2 border-primary p-1 d-flex flex-1' >" . $computedLocations[$routes[$i]['location_id']]['location_name'] . '<br>Time Slot :' . $routes[$i]['time_slot'] . '(Minutes)';

        // if (count($routes) - 1 == $i) {

        // } else {

        //     $data[$key]['Route'] .= "</div><div style='background-color:var(--primary-color); width:2px;height:20px;'></div>";

        // }

        // $data[$key]['Route'] .= "</div>";

        // }

    } else {

        $data[$key]['Route'] = "<span>No Route</span>";
    }

    // foreach ($routes as $locationKey => $location) {





    // }

}

echo json_encode(array(

    "draw" => intval($_POST['draw']),

    "recordsTotal" => $totalRecords,

    "recordsFiltered" => $filteredRecords,

    "data" => $data

));

die;
