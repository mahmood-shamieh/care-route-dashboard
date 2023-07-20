<?php
$cmsModeules = $db->select('select * from `cms_modules` where 1');
if (isset($_POST['add'])) {


    $ins_arr = array(
        'username' => $db->sqlsafe($_POST['username']),
        'password' => $db->sqlsafe(md5($_POST['password'])),
        'time_stamp' => $db->sqlsafe(formatDate(time())),
        'status' => $_POST['active'] == 'on' ? 1 : 0,
    );
    // print_r($_FILES);
    // die;
    if (isset($_FILES['img']['size']) && $_FILES['img']['size'] != 0) {

        $filename = upload_images('img', $mediaPath . '/admins/', time(), 'admin');

        if (strlen($filename) != 0) {
            $ins_arr['img'] = $db->sqlsafe($filename);
        }
    }


    $test = $db->insert('administrators', $ins_arr, false);
    if ($test) {
        foreach ($cmsModeules as $key => $value) {
            if (isset($_POST['add-all-true']) && $_POST['add-all-true'] != 1) {
                $ins_prev = array(
                    'admin_id' => $db->sqlsafe($test),
                    'module_id' => $db->sqlsafe($value['id']),
                    'edit' => $_POST['edit' . $value['id']] == 'on' ? 1 : 0,
                    'add' => $_POST['add' . $value['id']] == 'on' ? 1 : 0,
                    'delete' => $_POST['delete' . $value['id']] == 'on' ? 1 : 0,
                    'view' => $_POST['view' . $value['id']] == 'on' ? 1 : 0,

                );
            } else {
                $ins_prev = array(
                    'admin_id' => $db->sqlsafe($test),
                    'module_id' => $db->sqlsafe($value['id']),
                    'edit' => 1,
                    'add' => 1,
                    'delete' => 1,
                    'view' => 1,
                );
            }


            $db->insert('admin_prev', $ins_prev, false);
        }
        $ins_prev = $db->select('select * from `admin_prev` where  `admin_id` = ' . $test);
    }
    // print($test);
    // die;
    print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . $test . "\";</script>";
    // die;
}
if (isset($_POST['edit']) && $_POST['edit'] != 0) {
    foreach ($cmsModeules as $key => $value) {
        $ins_prev = array(
            'edit' => $_POST['edit' . $value['id'].$_POST['edit']] == 'on' ? 1 : 0,
            'add' => $_POST['add' . $value['id'].$_POST['edit']] == 'on' ? 1 : 0,
            'delete' => $_POST['delete' . $value['id'].$_POST['edit']] == 'on' ? 1 : 0,
            'view' => $_POST['view' . $value['id'].$_POST['edit']] == 'on' ? 1 : 0,

        );
        $db->update('admin_prev', $ins_prev, '`module_id` = ' . $value['id'] . ' AND `admin_id` = ' . $_POST['edit']);
    }
}
if (isset($_GET['suspend'])) {
    $data = $db->select('select * from `administrators` where `id` = ' . $_GET['id']);
    foreach ($data[0] as $key => $value) {
        $data[0][$key] = $db->sqlsafe($value);
    }
    $data[0]['status'] = 0;
    $test = $db->update('administrators', $data[0], ' `id` = ' . $_GET['id']);
    print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . $test . "\";</script>";
}
if (isset($_GET['active'])) {
    $data = $db->select('select * from `administrators` where `id` = ' . $_GET['id']);
    foreach ($data[0] as $key => $value) {
        $data[0][$key] = $db->sqlsafe($value);
    }
    $data[0]['status'] = 1;
    $test = $db->update('administrators', $data[0], ' `id` = ' . $_GET['id']);
    print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . $test . "\";</script>";
}
if (isset($_POST['delete'])) {
    $test = $db->delete('admin_prev', ' `admin_id` = ' . $_POST['delete']);
    $test = $db->delete('administrators', ' `id` = ' . $_POST['delete']);
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
                    targets: [0, 1, 2, 3, 4, 5]
                }],
                processing: true,
                serverSide: true,
                ajax: {
                    'url': "modules/ajaxes/administrators/administrators-ajax.php",
                    'type': 'POST',
                    // 'data': {
                    //     'section': <?php print($_GET['cmd']) ?>
                    // }
                },

                columns: [{
                        "data": "img"
                    },
                    {
                        "data": "username"
                    },
                    {
                        "data": "Privileges"
                    },
                    {
                        "data": "time_stamp"
                    }, {
                        "data": "Active"
                    }, {
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
                <h5 class="modal-title">Edit Admin Details</h5>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="add" value="1">
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
                                        <input type="text" class="form-control border-left-0" placeholder="Admin UserName" name="username" required="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-6 p-2">
                            <div class="row">
                                <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">Admin Password</label>
                                <div class="col-lg-9 col-sm-12 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="icon-file-locked2"></i>
                                            </span>
                                        </span>
                                        <input type="password" class="form-control border-left-0" placeholder="Admin Password" name="password" required="">
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

                        <div class=" col-lg-6 col-ms-6 col-sm-12 p-2 d-block ">
                            <div class=" row">
                                <div class="custom-control custom-control-right custom-switch text-right p-2">
                                    <input type="checkbox" class="custom-control-input" id="show-prev" name="">
                                    <label class="custom-control-label" for="show-prev">Show section for Privileges </label>


                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-6 col-ms-6 col-sm-12 p-2 d-block ">
                            <div class=" row">
                                <div class="custom-control custom-control-right custom-switch text-right p-2">
                                    <input type="checkbox" class="custom-control-input" id="give-all-permisions" name="" checked>
                                    <label class="custom-control-label" for="give-all-permisions">Give all permissions </label>
                                    <input type="hidden" name='add-all-true' class="custom-control-input" id="add-all-true" value="1">

                                </div>
                            </div>
                        </div>

                        <script>
                            $('#show-prev').on('click', function() {
                                if ($(this).is(':checked')) {
                                    $('#prev-section').addClass('row');
                                    $('#prev-section').removeClass('d-none');
                                    $('#give-all-permisions').prop('checked', false);
                                    $('#add-all-true').removeAttr('value');

                                } else {
                                    $('#prev-section').removeClass('row');
                                    $('#prev-section').addClass('d-none');
                                    $('#give-all-permisions').prop('checked', true);



                                }
                            });
                            $('#give-all-permisions').on('click', function() {
                                if ($(this).is(':checked')) {


                                    $('#prev-section').addClass('d-none');
                                    $('#show-prev').prop('checked', false);
                                    $('#add-all-true').prop('value', 1);
                                } else {

                                    $('#add-all-true').removeAttr('value');
                                    $('#prev-section').removeClass('d-none');
                                    $('#prev-section').addClass('row');
                                    $('#show-prev').prop('checked', true);
                                }
                            });
                        </script>
                        <div id="prev-section" class="d-none justify-content-center p-2" style="transition: 0.3s;transition-property: display;">
                            <?php foreach ($cmsModeules as $key => $value) {

                            ?>
                                <div class=" col-lg-6 col-md-12 col-sm-12 ">
                                    <div id="prev-block<?php print($value['id']) ?>" class="card">
                                        <div class="card-header header-elements-inline">
                                            <h6 class="card-title"><?php print($value['name']) ?></h6>
                                        </div>

                                        <div class="card-body ">
                                            <ul class="media-list row justify-content-between " style="align-items: baseline;">
                                                <li class="media  col-lg-3 col-md-4 col-sm-6">
                                                    <div class="mr-3 ">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="add<?php print($value['id']) ?>" name='add<?php print($value['id']) ?>'>
                                                            <label for="add<?php print($value['id']) ?>" class="custom-control-label p-0"></label>
                                                        </div>
                                                    </div>
                                                    <div class="media-body ">
                                                        <h6 class="media-title">
                                                            <label for="add<?php print($value['id']) ?>" class="font-weight-semibold cursor-pointer mb-0">Add</label>
                                                        </h6>
                                                    </div>
                                                </li>
                                                <li class="media  col-lg-3 col-md-4 col-sm-6">
                                                    <div class="mr-3">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="edit<?php print($value['id']) ?>" name="edit<?php print($value['id']) ?>">
                                                            <label for="edit<?php print($value['id']) ?>" class="custom-control-label p-0"></label>
                                                        </div>
                                                    </div>
                                                    <div class="media-body ">
                                                        <h6 class="media-title">
                                                            <label for="edit<?php print($value['id']) ?>" class="font-weight-semibold cursor-pointer mb-0">Edit</label>
                                                        </h6>
                                                    </div>
                                                </li>
                                                <li class="media  col-lg-3 col-md-4 col-sm-6">
                                                    <div class="mr-3">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="delete<?php print($value['id']) ?>" name="delete<?php print($value['id']) ?>">
                                                            <label for="delete<?php print($value['id']) ?>" class="custom-control-label p-0"></label>
                                                        </div>
                                                    </div>
                                                    <div class="media-body ">
                                                        <h6 class="media-title">
                                                            <label for="delete<?php print($value['id']) ?>" class="font-weight-semibold cursor-pointer mb-0">Delete</label>
                                                        </h6>
                                                    </div>
                                                </li>
                                                <li class="media  col-lg-3 col-md-4 col-sm-6">
                                                    <div class="mr-3">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="view<?php print($value['id']) ?>" name="view<?php print($value['id']) ?>">
                                                            <label for="view<?php print($value['id']) ?>" class="custom-control-label p-0"></label>
                                                        </div>
                                                    </div>
                                                    <div class="media-body ">
                                                        <h6 class="media-title">
                                                            <label for="view<?php print($value['id']) ?>" class="font-weight-semibold cursor-pointer mb-0">View</label>
                                                        </h6>
                                                    </div>
                                                </li>




                                            </ul>


                                        </div>
                                    </div>



                                </div>
                            <?php } ?>
                        </div>






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
                <th>img</th>
                <th>username</th>
                <th>Privileges</th>

                <th>time_stamp</th>
                <th>Active</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>


        </tbody>
    </table>
</div>