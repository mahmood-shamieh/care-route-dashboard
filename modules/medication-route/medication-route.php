<?php
$e = null;

$cmsModeules = $db->select('select * from `cms_modules` where 1');

if (isset($_POST['add'])) {

    // print_r($_POST);

    $route = array(

        'name' => $db->sqlsafe($_POST['name']),

        'medication_type_id' => $db->sqlsafe($_POST['medicationTypes']),

        'created_date' => $db->sqlsafe(formatDate(time())),

        'last_modified_date' => $db->sqlsafe(formatDate(time())),

        'status' => $_POST['active'] == 'on' ? 1 : 0,

    );

    $test = $db->insert('routes', $route, false);



    if ($test) {



        foreach ($_POST['locations'] as $key => $value) {

            $routeCheckPoint = array(

                'route_id' => $test,

                'location_id' => $value,

                'time_slot' => $_POST['times'][$key],

                'order_number' => $key,

                'creation_date' => $db->sqlsafe(formatDate(time())),

                'last_modified_date' => $db->sqlsafe(formatDate(time())),

                'status' => $_POST['active'] == 'on' ? 1 : 0,

            );



            $db->insert('routes_check_points', $routeCheckPoint, false);
        }
    }

    // die;

    print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . $test . "\";</script>";
}

if (isset($_POST['edit']) && $_POST['edit'] != 0) {

    $routeName = $_POST['name'];

    $medicationType = $_POST['medicationType'];

    $routeId = $_POST['edit'];

    $ins_arr = array(

        'name' => $db->sqlsafe($routeName),

        'medication_type_id' => $db->sqlsafe($medicationType),

        'last_modified_date' => $db->sqlsafe(formatDate(time())),

        'status' => $_POST['status'] == 'on' ? 1 : 0,

    );

    $test = $db->update('routes', $ins_arr, '`id` = ' . $_POST['edit']);

    if ($test) {

        try {
            $db->delete('routes_check_points', ' `route_id` = ' . $db->sqlsafe($routeId));
        } catch (Exception $t) {
            $e = $t;
            print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . 0 . "\";</script>";
            die;
        }

        foreach ($_POST['locations' . $routeId] as $key => $value) {

            $routeCheckPoint = array(

                'route_id' => $db->sqlsafe($routeId),

                'location_id' => $db->sqlsafe($value),

                'time_slot' => $_POST['times' . $routeId][$key],

                'order_number' => $db->sqlsafe($key),

                'creation_date' => $db->sqlsafe(formatDate(time())),

                'last_modified_date' => $db->sqlsafe(formatDate(time())),

                'status' => 1,

            );

            $db->insert('routes_check_points', $routeCheckPoint, false);
        }
    }

    print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . $test . "\";</script>";
}

if (isset($_GET['suspend'])) {

    $data = $db->select('select * from `routes` where `id` = ' . $_GET['id']);

    foreach ($data[0] as $key => $value) {

        $data[0][$key] = $db->sqlsafe($value);
    }

    $data[0]['status'] = 0;



    $test = $db->update('routes', $data[0], ' `id` = ' . $_GET['id']);

    print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . $test . "\";</script>";
}

if (isset($_GET['active'])) {

    $data = $db->select('select * from `routes` where `id` = ' . $_GET['id']);

    foreach ($data[0] as $key => $value) {

        $data[0][$key] = $db->sqlsafe($value);
    }

    $data[0]['status'] = 1;



    $test = $db->update('routes', $data[0], ' `id` = ' . $_GET['id']);



    print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . $test . "\";</script>";
}

if (isset($_POST['delete'])) {
    try {
        $canDelete = $db->delete('routes_check_points', ' `route_id` = ' . $_POST['delete']);
    } catch (Exception $e) {
        print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . 0 . "\";</script>";
        die;
    }
    if ($canDelete) {

        $test = $db->delete('routes', ' `id` = ' . $_POST['delete']);
    }

    $test = $test ? 1 : 0;

    print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . $test . "\";</script>";
}

$routes = $db->select('select * from `routes` where `status` = 1');

$medicationsTypes = $db->select('select * from `medication_type` where `status` = 1');

$locations = $db->select('select * from `locations` where `active` = 1');

?>

<?php

if (isset($_GET['done']) && $_GET['done'] != 0) {

    print(" <script language=\"JavaScript\">

    var swalInit = swal.mixin({

        buttonsStyling: false,

        customClass: {

            confirmButton: 'btn btn-primary',

            cancelButton: 'btn btn-light',

            denyButton: 'btn btn-light',

            input: 'form-control'

        }

    });



    swalInit.fire({

        title: 'Good job!',

        // text: 'You clicked the button!',

        icon: 'success'

    });</script>");
} else if (isset($_GET['done']) && $_GET['done'] == 0)

    print(" <script language=\"JavaScript\">

var swalInit = swal.mixin({

    buttonsStyling: false,

    customClass: {

        confirmButton: 'btn btn-primary',

        cancelButton: 'btn btn-light',

        denyButton: 'btn btn-light',

        input: 'form-control'

    }

});


swalInit.fire({

    title: 'Oops...',

    text: 'Something went wrong!',

    icon: 'error'

});

</script>");

unset($_GET['done'])



?>



<div class="page-header p-2 ">

    <div class="page-header-content header-elements-lg-inline d-flex justify-content-between align-content-center">

        <div class="class=" d-inline-block"">

            <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold"><?php print($currentSection['name']) ?></span></h4>



        </div>



        <div class="d-inline-block">

            <?php if ($currentSection['have_actions'] == 1) : ?>

                <div class="btn-group justify-content-center position-static show ">

                    <a href="" class="btn btn-indigo dropdown-toggle  " data-toggle="dropdown" aria-expanded="false" style="background-color : var(--primary-color);border:none;">Actions</a>

                    <div class="dropdown-menu m-2">

                        <?php if ($currentSection['add'] == 1) : ?>

                            <a href="#" data-toggle="modal" data-target="#add-pop-up" class="dropdown-item "><i class="fa-solid fa-plus"></i> add <?php print($currentSection['name']) ?></a>

                        <?php endif ?>

                        <?php if ($currentSection['view'] == 1) : ?>

                            <a href="" class="dropdown-item "><i class="fa-solid fa-filter"></i> Filter <?php print($currentSection['name']) ?></a>

                        <?php endif ?>

                    </div>

                </div>

            <?php endif ?>

        </div>

    </div>

</div>

<script>
    /* ------------------------------------------------------------------------------

     *

     *  # Buttons extension for Datatables. HTML5 examples

     *

     *  Demo JS code for datatable_extension_buttons_html5.html page

     *

     * ---------------------------------------------------------------------------- */





    // Setup module

    // ------------------------------



    var DatatableButtonsHtml5 = function() {





        //

        // Setup module components

        //



        // Basic Datatable examples

        var _componentDatatableButtonsHtml5 = function() {

            if (!$().DataTable) {

                console.warn('Warning - datatables.min.js is not loaded.');

                return;

            }



            // Setting datatable defaults

            $.extend($.fn.dataTable.defaults, {

                autoWidth: false,

                columnDefs: [{

                    orderable: false,

                    width: 100,

                    targets: [0, 1, 2, 3, 4, 5, 6]

                }],

                processing: true,

                serverSide: true,

                ajax: {

                    'url': "modules/ajaxes/medication-route/medication-route.php",

                    'type': 'POST',

                    'data': {

                        'section': <?php print(json_encode($currentSection)) ?>,

                        'cmd': <?php print(json_encode($_GET['cmd'])) ?>,

                    }

                },



                columns: [
                    // {

                    //     "data": "Name"

                    // },

                    {

                        "data": "Medications_types"

                    }, {

                        "data": "related_medications"

                    },

                    {

                        "data": "Route"

                    }, {

                        "data": "Status"

                    }, {

                        "data": "Created_date"

                    }, {

                        "data": "Last_date_modified"

                    },

                    {

                        "data": "Actions"

                    },



                ],

                dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',

                language: {

                    search: '<span>Filter:</span> _INPUT_',

                    searchPlaceholder: 'Type to filter...',

                    lengthMenu: '<span>Show:</span> _MENU_',

                    paginate: {

                        'first': 'First',

                        'last': 'Last',

                        'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;',

                        'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;'

                    }

                }

            });



            // Apply custom style to select

            $.extend($.fn.dataTableExt.oStdClasses, {

                "sLengthSelect": "custom-select"

            });





            // Basic initialization







            // PDF with image

            // $('.datatable-button-html5-image').DataTable({

            //     buttons: [{

            //         extend: 'pdfHtml5',

            //         text: 'Export to PDF <i class="icon-file-pdf ml-2"></i>',

            //         className: 'btn btn-teal',

            //         customize: function(doc) {

            //             doc.content.splice(1, 0, {

            //                 margin: [0, 0, 0, 12],

            //                 alignment: 'center',

            //                 fontFamily: 'Helvetica',



            //             });

            //         }

            //     }]

            // });





            // Column selectors

            $('.datatable-button-html5-columns').DataTable({

                buttons: {

                    buttons: [{

                            extend: 'copyHtml5',

                            className: 'btn btn-light',

                            exportOptions: {

                                columns: [0, ':visible']

                            }

                        },

                        {

                            extend: 'excelHtml5',

                            className: 'btn btn-light',

                            exportOptions: {

                                columns: ':visible'

                            }

                        },

                        {

                            extend: 'pdfHtml5',

                            className: 'btn btn-light',

                            exportOptions: {

                                columns: [0, 1, 2, 5]

                            }

                        }, {

                            extend: 'print',

                            className: 'btn btn-light',

                            exportOptions: {

                                columns: [0, 1, 2, 5]

                            }

                        },

                        {

                            extend: 'colvis',

                            text: '<i class="icon-three-bars"></i>',

                            className: 'btn btn-primary btn-icon'

                        }

                    ]

                }

            });





            // Tab separated values

            $('.datatable-button-html5-tab').DataTable({

                buttons: {

                    buttons: [{

                            extend: 'copyHtml5',

                            className: 'btn btn-light',

                            text: '<i class="icon-copy3 mr-2"></i> Copy'

                        },

                        {

                            extend: 'csvHtml5',

                            className: 'btn btn-light',

                            text: '<i class="icon-file-spreadsheet mr-2"></i> CSV',

                            fieldSeparator: '\t',

                            extension: '.tsv'

                        }

                    ]

                }

            });

        };





        //

        // Return objects assigned to module

        //



        return {

            init: function() {

                _componentDatatableButtonsHtml5();

            }

        }

    }();









    // Initialize module

    // ------------------------------



    document.addEventListener('DOMContentLoaded', function() {

        DatatableButtonsHtml5.init();

    });

    $(document).ready(function() {

        $('.select').select2({});

    })
</script>











<div id="add-pop-up" class="modal fade show " aria-modal="true" role="dialog">

    <div class="modal-dialog modal-dialog-scrollable modal-full">

        <div class="modal-content">

            <div class="modal-header bg-primary text-white">

                <h5 class="modal-title">Add New Route</h5>

                <button type="button" class="close" data-dismiss="modal">×</button>

            </div>

            <div class="modal-body">

                <form method="post" enctype="multipart/form-data">

                    <input type="hidden" name="add" value="1">

                    <div class="form-group row">

                        <div class=" col-lg-6 p-2">

                            <div class="row">

                                <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">Route Name</label>

                                <div class="col-lg-9 col-sm-12 col-md-9">

                                    <div class="input-group">

                                        <span class="input-group-prepend">

                                            <span class="input-group-text bg-primary border-primary text-white">

                                                <i class="icon-file-locked2"></i>

                                            </span>

                                        </span>

                                        <input type="text" class="form-control border-left-0" placeholder="Route Name" name="name" required="">

                                    </div>

                                </div>

                            </div>

                        </div>



                        <div class=" col-lg-6 p-2">

                            <div class="row form-group">

                                <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">Medications Types</label>

                                <div class="col-lg-9 col-sm-12 col-md-9">

                                    <select data-placeholder="Select Medications Types" class="form-control select" data-fouc name="medicationTypes">

                                        <?php foreach ($medicationsTypes as $key => $value) {

                                        ?>

                                            <option value="<?php print($value['id']) ?>"><?php print($value['name']) ?></option>

                                        <?php

                                        } ?>

                                    </select>

                                </div>

                            </div>

                        </div>





                        <div class=" col-lg-12 p-2 border-primary border-2 p-2 rounded-lg">



                            <h5>Route locations</h5>

                            <h5 id="noSelectedLocationsTitle">No Selected Locations Yet</h5>

                            <div id="locationsArea" class="row m-2">



                            </div>



                            <div class=" border-primary border-2 p-2 rounded-lg m-2">

                                <div class="row p-2">

                                    <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">Location Name: </label>

                                    <div class="col-lg-6 col-sm-12 col-md-9">

                                        <select id="locations" name="" id="" class="select">

                                            <option value="0">Click To Select</option>

                                            <?php foreach ($locations as $key => $value) { ?>

                                                <option value="<?php print($value['location_name']) ?>" location_id="<?php print($value['location_id']) ?>"><?php print($value['location_name']) ?></option>

                                            <?php } ?>

                                        </select>

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

                                            <input id="timeSlot" type="number" class="form-control border-left-0" placeholder="Location Time Slot Per Minute" name="timeSlot" required="" value="1">

                                        </div>

                                    </div>

                                </div>





                                <div class="row">

                                    <div style="width: 74%;height: 1px;"></div>

                                    <button id="addNewCheckPointButton" type="button" class="btn btn-primary col-lg-3">Add New Location</button>

                                </div>

                            </div>



                        </div>



                        <script>
                            let routes = [];

                            let i = 1;



                            function deleteElementAtIndex(arr, key) {

                                // console.log(arr.length);

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

                                    console.error("Index out of range.");

                                }

                                update();

                            }



                            function update() {

                                console.log(routes.length);

                                $('#locationsArea').empty();

                                if (routes.length == 0) {

                                    $('#noSelectedLocationsTitle').css('display', 'block');

                                } else {

                                    routes.forEach(element => {

                                        $('#locationsArea').append(element['input']);

                                        $('#locationsArea').append(element['block']);

                                    });

                                }

                            }

                            $('#locations').on('change', function() {

                                // $(this).val('');

                            })

                            $('#addNewCheckPointButton').on('click', function() {



                                if ($('#locations').val() != 0) {

                                    i++;

                                    let temp;

                                    let step = `<div class="d-flex justify-content-center align-items-center">

                                    <div style="width: 30px;height: 2px;" class="bg-primary d-inline-block">

                                    </div>

                                    </div>`;

                                    let block = `<div class="mb-2 d-flex" style="transition: .3s;">

                                    <div class="border-2 p-2  border-primary rounded-lg" style="width: fit-content;border-color: var(--primary-color);">

                                    <button class="rounded-lg border-2 border-primary" onclick="deleteElementAtIndex(routes,${i})"><i class="fa-solid fa-xmark p-1 m-1 bg-primary rounded-lg" style="color:#fff;"></i></button> ${$('#locations').val()}`;

                                    let endOfBlock = `</div></div>`;

                                    if (routes.length == 0) {

                                        temp = {

                                            'id': i,

                                            'input': `<input type="hidden" name="locations[]" value="${$('#locations option:selected').attr('location_id')}">`,

                                            'block': `${block}<br><label >Time Slot: </label><input id="test" type="number" name="times[]" class="border-primary border-2 rounded-lg m-2 w-50" style="text-align:center;" value="${$('#timeSlot').val()}">${endOfBlock}`,

                                        };

                                    } else {

                                        temp = {

                                            'id': i,

                                            'input': `<input type="hidden" name="locations[]" value="${$('#locations option:selected').attr('location_id')}">`,

                                            'block': `${step}${block}<br><label >Time Slot: </label><input id="test" type="number" name="times[]" class="border-primary border-2 rounded-lg m-2 w-50" style="text-align:center;" value="${$('#timeSlot').val()}">${endOfBlock}`,

                                        };

                                    }



                                    routes.push(temp);

                                    $('#noSelectedLocationsTitle').css('display', 'none');

                                    $('#locations').val('0');

                                    $('#timeSlot').val('1');

                                    update();

                                }



                                // console.log($('#locations').val());

                            });
                        </script>







                        <div class=" col-lg-6 p-2 d-block ">

                            <div class=" row">

                                <div class="custom-control custom-control-right custom-switch text-right p-2">

                                    <input type="checkbox" class="custom-control-input" id="sc_rs_c" name="active" checked="">

                                    <label class="custom-control-label" for="sc_rs_c">Active</label>

                                </div>

                            </div>

                        </div>

                        <hr style="width: 100%;">























                    </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="icon-cross2 font-size-base mr-1"></i> Close</button>

                <button id='sweet_success' type="sumbit" class="btn btn-primary"> <i class="icon-checkmark3 font-size-base mr-1"></i> Add Route</button>

            </div>

            </form>



        </div>



    </div>

</div>





<div class="card">



    <table class="table datatable-button-html5-columns table-bordered dataTable">

        <thead>

            <tr>

                <!-- <th>Name</th> -->

                <th>Medications types</th>
                <th>Related Medications</th>

                <th>Route</th>

                <th>Status</th>

                <th>Created_date</th>

                <th>Last_date_modified</th>

                <th class="text-center">Actions</th>

            </tr>

        </thead>

        <tbody>





        </tbody>

    </table>

</div>