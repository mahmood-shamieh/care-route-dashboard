<?php

$cmsModeules = $db->select('select * from `cms_modules` where 1');

if (isset($_POST['add'])) {

    $ins_arr = array(

        'name' => $db->sqlsafe($_POST['name']),

        'created_date' => $db->sqlsafe(formatDate(time())),

        'last_modified_date' => $db->sqlsafe(formatDate(time())),

        'status' => $_POST['active'] == 'on' ? 1 : 0,

    );





    $test = $db->insert('medication_type', $ins_arr, false);



    // print($test);

    // die;

    print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . $test . "\";</script>";

    // die;

}

if (isset($_POST['edit']) && $_POST['edit'] != 0) {

    $ins_arr = array(

        'name' => $db->sqlsafe($_POST['name']),

        'last_modified_date' => $db->sqlsafe(formatDate(time())),

        'status' => $_POST['status'] == 'on' ? 1 : 0,

    );


    try {
        $test = $db->update('medication_type', $ins_arr, '`id` = ' . $_POST['edit']);
    } catch (Exception $e) {
        print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . 0 . "\";</script>";
        die;
    }


    print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . $test . "\";</script>";
}

if (isset($_GET['suspend'])) {

    $data = $db->select('select * from `medication_type` where `id` = ' . $_GET['id']);

    foreach ($data[0] as $key => $value) {

        $data[0][$key] = $db->sqlsafe($value);
    }

    $data[0]['status'] = 0;



    $test = $db->update('medication_type', $data[0], ' `id` = ' . $_GET['id']);

    print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . $test . "\";</script>";
}

if (isset($_GET['active'])) {

    $data = $db->select('select * from `medication_type` where `id` = ' . $_GET['id']);

    foreach ($data[0] as $key => $value) {

        $data[0][$key] = $db->sqlsafe($value);
    }

    $data[0]['status'] = 1;



    $test = $db->update('medication_type', $data[0], ' `id` = ' . $_GET['id']);

    print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . $test . "\";</script>";
}

if (isset($_POST['delete'])) {
    try {
        $test = $db->delete('medication_type', ' `id` = ' . $_POST['delete']);
    } catch (Exception $e) {

        print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . 0 . "\";</script>";
        die;
    }


    $test = $test ? 1 : 0;

    print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . $test . "\";</script>";
}

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

        text: 'You clicked the button!',

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

                    targets: [0, 1, 2, 3, 4]

                }],

                processing: true,

                serverSide: true,

                ajax: {

                    'url': "modules/ajaxes/medication-type/medication-type.php",

                    'type': 'POST',

                    'data': {

                        'section': <?php print(json_encode($currentSection)) ?>,

                        'cmd': <?php print(json_encode($_GET['cmd'])) ?>,

                    }

                },



                columns: [{

                        "data": "Name"

                    },

                    {

                        "data": "Status"

                    },

                    {

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
</script>









<div id="add-pop-up" class="modal fade show " aria-modal="true" role="dialog">

    <div class="modal-dialog modal-dialog-scrollable modal-full">

        <div class="modal-content">

            <div class="modal-header bg-primary text-white">

                <h5 class="modal-title">Add New Medication TypeType</h5>

                <button type="button" class="close" data-dismiss="modal">×</button>

            </div>

            <div class="modal-body">

                <form method="post" enctype="multipart/form-data">

                    <input type="hidden" name="add" value="1">

                    <div class="form-group row">

                        <div class=" col-lg-6 p-2">

                            <div class="row">

                                <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">Medication Type</label>

                                <div class="col-lg-9 col-sm-12 col-md-9">

                                    <div class="input-group">

                                        <span class="input-group-prepend">

                                            <span class="input-group-text bg-primary border-primary text-white">

                                                <i class="icon-file-locked2"></i>

                                            </span>

                                        </span>

                                        <input type="text" class="form-control border-left-0" placeholder="Medication Type" name="name" required="">

                                    </div>

                                </div>

                            </div>

                        </div>

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

                <button id='sweet_success' type="sumbit" class="btn btn-primary"> <i class="icon-checkmark3 font-size-base mr-1"></i> Add Admin</button>

            </div>

            </form>



        </div>



    </div>

</div>

<div class="card">



    <table class="table datatable-button-html5-columns table-bordered dataTable">

        <thead>

            <tr>

                <th>Name</th>

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