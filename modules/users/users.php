<?php
$cmsModeules = $db->select('select * from `cms_modules` where 1');
if (isset($_POST['add'])) {


    $ins_arr = array(
        'username' => $db->sqlsafe($_POST['username']),
        'email' => $db->sqlsafe($_POST['email']),
        'full_name' => $db->sqlsafe($_POST['full_name']),
        'phone_number' => $db->sqlsafe($_POST['phone_number']),
        'address' => $db->sqlsafe($_POST['address']),
        'role' => $db->sqlsafe($_POST['role']),
        'role' => $db->sqlsafe($_POST['role']),
        'password' => $db->sqlsafe(md5($_POST['password'])),
        'creation_date' => $db->sqlsafe(formatDate(time())),
        'last_date_modified' => $db->sqlsafe(formatDate(time())),
        'status' => $_POST['status'] == 'on' ? 1 : 0,
    );
    // print_r($_FILES);
    // die;
    if (isset($_FILES['img']['size']) && $_FILES['img']['size'] != 0) {

        $filename = upload_images('img', $mediaPath . '/users/', time(), 'user');

        if (strlen($filename) != 0) {
            $ins_arr['img'] = $db->sqlsafe($filename);
        }
    }


    $test = $db->insert('users', $ins_arr, false);

    // print($test);
    // die;
    print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . $test . "\";</script>";
    die;
}
if (isset($_POST['edit']) && $_POST['edit'] != 0) {
    $ins_arr = array(
        'username' => $db->sqlsafe($_POST['username']),
        'email' => $db->sqlsafe($_POST['email']),
        'full_name' => $db->sqlsafe($_POST['full_name']),
        'phone_number' => $db->sqlsafe($_POST['phone_number']),
        'address' => $db->sqlsafe($_POST['address']),
        'role' => $db->sqlsafe($_POST['role']),
        'role' => $db->sqlsafe($_POST['role']),
        
        'last_date_modified' => $db->sqlsafe(formatDate(time())),
        'status' => $_POST['status'] == 'on' ? 1 : 0,
    );
    if (isset($_POST['password']) && strlen($_POST['password']) != 0) {
        $ins_arr['password'] =  $db->sqlsafe(md5($_POST['password']));
    }
    if (isset($_FILES['img']['size']) && $_FILES['img']['size'] != 0) {
        // print('asdasd');

        $filename = upload_images('img', $mediaPath . '/users/', time(), 'user');

        if (strlen($filename) != 0) {
            $ins_arr['img'] = $db->sqlsafe($filename);
        }
    }
    // print_r($ins_arr);
    // die;
    $test = $db->update('users', $ins_arr, '`id` = ' . $_POST['edit']);
    print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . $test . "\";</script>";
    die;
}
if (isset($_GET['suspend'])) {
    $data = $db->select('select * from `users` where `id` = ' . $_GET['id']);
    foreach ($data[0] as $key => $value) {
        $data[0][$key] = $db->sqlsafe($value);
    }
    $data[0]['status'] = 0;
    $data[0]['last_date_modified'] = $db->sqlsafe(formatDate(time()));
    $test = $db->update('users', $data[0], ' `id` = ' . $_GET['id']);
    print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . $test . "\";</script>";
}
if (isset($_GET['active'])) {
    $data = $db->select('select * from `users` where `id` = ' . $_GET['id']);
    foreach ($data[0] as $key => $value) {
        $data[0][$key] = $db->sqlsafe($value);
    }
    $data[0]['status'] = 1;
    $data[0]['last_date_modified'] = $db->sqlsafe(formatDate(time()));
    $test = $db->update('users', $data[0], ' `id` = ' . $_GET['id']);
    print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . $test . "\";</script>";
}
if (isset($_POST['delete'])) {
    $test = $db->delete('users', ' `id` = ' . $_POST['delete']);

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
                    targets: [0, 9, 10]
                }],
                processing: true,
                serverSide: true,
                ajax: {
                    'url': "modules/ajaxes/users/users-ajax.php",
                    'type': 'POST',
                    'data': {
                        'section': <?php print(json_encode($currentSection)) ?>
                    }
                },

                columns: [{
                        "data": "img"
                    },
                    {
                        "data": "username"
                    },
                    {
                        "data": "email"
                    },
                    {
                        "data": "full_name"
                    },
                    {
                        "data": "phone_number"
                    },
                    {
                        "data": "address"
                    },
                    {
                        "data": "role"
                    },
                    {
                        "data": "creation_date"
                    },
                    {
                        "data": "last_date_modified"
                    }, {
                        "data": "status"
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
                                <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">UserName</label>
                                <div class="col-lg-9 col-sm-12 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="icon-pencil6"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control border-left-0" placeholder="UserName" name="username" required="">
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
                                        <input type="password" class="form-control border-left-0" placeholder="password" name="password" required="">
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
                                        <input type="text" class="form-control border-left-0" placeholder="User Email" name="email" required="">
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
                                        <input type="text" class="form-control border-left-0" placeholder="Full Name" name="full_name" required="">
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
                                        <input type="text" class="form-control border-left-0" placeholder="Phone Number" name="phone_number" required="">
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
                                        <input type="text" class="form-control border-left-0" placeholder="User Adress" name="address" required="">
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
                                        <input type="text" class="form-control border-left-0" placeholder="User Role" name="role" required="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" col-lg-6 p-2 d-block ">
                            <div class=" row">
                                <div class="custom-control custom-control-right custom-switch text-right p-2">
                                    <input type="checkbox" class="custom-control-input" id="sc_rs_c" name="status" checked="">
                                    <label class="custom-control-label" for="sc_rs_c">Active</label>
                                </div>
                            </div>
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
                <th>Image</th>
                <th>Username</th>
                <th>Email</th>
                <th>Full Name</th>

                <th>Phone Number</th>
                <th>Adress</th>
                <th>Role</th>
                <th>Creation Date</th>
                <th>Last Modified Date</th>
                <th>Status</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>


        </tbody>
    </table>
</div>