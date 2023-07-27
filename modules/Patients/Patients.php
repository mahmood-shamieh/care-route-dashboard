<?php
if (isset($_POST['edit'])) {
    $insArr = array(
        'patiant_name' => $db->sqlsafe($_POST['patiant_name']),
        'family_name' => $db->sqlsafe($_POST['family_name']),
        'mother_name' => $db->sqlsafe($_POST['mother_name']),
        'date_of_birthday' => $db->sqlsafe($_POST['date_of_birthday']),
        'sex' => $db->sqlsafe($_POST['sex']),
        'patiant_alias' => $db->sqlsafe($_POST['patiant_alias']),
        'patiant_adress' => $db->sqlsafe($_POST['patiant_adress']),
        'country_code' => $db->sqlsafe($_POST['country_code']),
        'phone_number_home' => $db->sqlsafe($_POST['phone_number_home']),
        'phone_number_business' => $db->sqlsafe($_POST['phone_number_business']),
        'primary_language' => $db->sqlsafe($_POST['primary_language']),
        'material_status' => $db->sqlsafe($_POST['material_status']),
        'religion' => $db->sqlsafe($_POST['religion']),
        'patiant_account_number' => $db->sqlsafe($_POST['patiant_account_number']),
        'ssn_number' => $db->sqlsafe($_POST['ssn_number']),
        'driver_license_number' => $db->sqlsafe($_POST['driver_license_number']),
        'ethinc_number' => $db->sqlsafe($_POST['ethinc_number']),
        'birth_Place' => $db->sqlsafe($_POST['birth_Place']),
        'nationality' => $db->sqlsafe($_POST['nationality']),
        'patiant_deth_date_time' => $db->sqlsafe($_POST['patiant_deth_date_time']),
        'patiant_death_indicator' => $db->sqlsafe($_POST['patiant_death_indicator']),
        'active' => $_POST['active'] == 'on' ? 1 : 0,
    );
    $test = $db->update('patiant_identifications', $insArr, ' `id` = ' . $db->sqlsafe($_POST['edit']));
    print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . $test . "\";</script>";
    die;
}
if (isset($_POST['add'])) {
    $insArr = array(
        'patiant_name' => $db->sqlsafe($_POST['patiant_name']),
        'family_name' => $db->sqlsafe($_POST['family_name']),
        'mother_name' => $db->sqlsafe($_POST['mother_name']),
        'date_of_birthday' => $db->sqlsafe($_POST['date_of_birthday']),
        'sex' => $db->sqlsafe($_POST['sex']),
        'patiant_alias' => $db->sqlsafe($_POST['patiant_alias']),
        'patiant_adress' => $db->sqlsafe($_POST['patiant_adress']),
        'country_code' => $db->sqlsafe($_POST['country_code']),
        'phone_number_home' => $db->sqlsafe($_POST['phone_number_home']),
        'phone_number_business' => $db->sqlsafe($_POST['phone_number_business']),
        'primary_language' => $db->sqlsafe($_POST['primary_language']),
        'material_status' => $db->sqlsafe($_POST['material_status']),
        'religion' => $db->sqlsafe($_POST['religion']),
        'patiant_account_number' => $db->sqlsafe($_POST['patiant_account_number']),
        'ssn_number' => $db->sqlsafe($_POST['ssn_number']),
        'driver_license_number' => $db->sqlsafe($_POST['driver_license_number']),
        'ethinc_number' => $db->sqlsafe($_POST['ethinc_number']),
        'birth_Place' => $db->sqlsafe($_POST['birth_Place']),
        'nationality' => $db->sqlsafe($_POST['nationality']),
        'patiant_deth_date_time' => $db->sqlsafe($_POST['patiant_deth_date_time']),
        'patiant_death_indicator' => $db->sqlsafe($_POST['patiant_death_indicator']),
        'active' => $_POST['active'] == 'on' ? 1 : 0,
    );
    $test = $db->insert('patiant_identifications', $insArr);
    print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . $test . "\";</script>";
    die;
}
if (isset($_GET['suspend'])) {
    $data = $db->select('select * from `patiant_identifications` where `id` = ' . $_GET['id']);
    foreach ($data[0] as $key => $value) {
        $data[0][$key] = $db->sqlsafe($value);
    }
    $data[0]['active'] = 0;
    
    $test = $db->update('patiant_identifications', $data[0], ' `id` = ' . $_GET['id']);
    print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . $test . "\";</script>";
    die;
}
if (isset($_GET['active'])) {
    $data = $db->select('select * from `patiant_identifications` where `id` = ' . $_GET['id']);
    foreach ($data[0] as $key => $value) {
        $data[0][$key] = $db->sqlsafe($value);
    }
    $data[0]['active'] = 1;
    
    $test = $db->update('patiant_identifications', $data[0], ' `id` = ' . $_GET['id']);
    print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . $test . "\";</script>";
    die;
}
if (isset($_POST['delete'])) {
    $test = $db->delete('patiant_identifications', ' `id` = ' . $_POST['delete']);
    $test = $test ? 1 : 0;
    print "<script language=\"JavaScript\">window.location=\"index.php?cmd=" . $_GET['cmd'] . "&done=" . $test . "\";</script>";
    die;
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
                            <a href="#" data-toggle="modal" data-target="#add_popup" class="dropdown-item "><i class="fa-solid fa-plus"></i> add <?php print($currentSection['name']) ?></a>
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



            var table = $.extend($.fn.dataTable.defaults, {
                autoWidth: false,
                columnDefs: [{
                    orderable: false,
                    width: 100,
                    targets: [6]
                }],
                processing: true,
                serverSide: true,
                ajax: {
                    'url': "modules/ajaxes/patiants/patiants-ajax.php",
                    'type': 'POST',
                    'data': {
                        'section': <?php print(json_encode($currentSection)) ?>
                    }
                },

                columns: [{
                        "data": "patiant_name"
                    },
                    {
                        "data": "family_name"
                    },
                    {
                        "data": "mother_name"
                    },
                    {
                        "data": "date_of_birthday"
                    },
                    {
                        "data": "sex"
                    }, {
                        "data": "patiant_alias"
                    },
                    {
                        "data": "primary_language"
                    },
                    {
                        "data": "material_status"
                    },
                    {
                        "data": "religion"
                    }, {
                        "data": "nationality"
                    },
                    {
                        "data": "patiant_account_number"
                    },
                    {
                        "data": "ssn_number"
                    },
                    {
                        "data": "patiant_deth_date_time"
                    },
                    {
                        "data": "patiant_death_indicator"
                    },
                    {
                        "data": "Active"
                    },
                    {
                        "data": "Actions"
                    }
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


            $('.datatable-button-html5-columns').DataTable({
                buttons: {
                    buttons: [{
                            extend: 'copyHtml5',
                            className: 'btn btn-light',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            className: 'btn btn-light',
                            exportOptions: {
                                columns: ':visible'
                            }
                        }, , {
                            extend: 'print',
                            className: 'btn btn-light',
                            exportOptions: {
                                columns: ':visible'
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
            // setInterval(function() {
            //     $('#care-route').DataTable().ajax.reload()
            // }, 3000);

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


<div class="card">
    <table id='care-route' class="table datatable-button-html5-columns table-bordered dataTable">
        <thead>
            <tr>
                <th>patiant name</th>
                <th>family name</th>
                <th>mother name</th>
                <th>date of birthday</th>
                <th>sex</th>
                <th>patiant alias</th>
                <th>primary language</th>
                <th>material status</th>
                <th>religion</th>
                <th>nationality</th>
                <th>patiant account number</th>
                <th>ssn number</th>
                <th>patiant deth date time</th>
                <th>patiant death indicator</th>
                <th>Status</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
</div>
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="add" value="1">
    <div id="add_popup" class="modal fade show" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable modal-full">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Edit Patiant Details</h5>
                    <button type="button" class="close" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">


                    <div class="form-group row">
                        <div class="col-lg-6 p-2">
                            <div class="row">
                                <label class="col-form-label col-lg-3 col-sm-12 col-md-3 text-left">Patiant Name</label>
                                <div class="col-lg-9 col-sm-12 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="icon-pencil6"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control border-left-0" placeholder="patiant name" name="patiant_name">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-6 p-2">
                            <div class="row">
                                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Family Name</label>
                                <div class="col-lg-9 col-sm-9 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="icon-pencil6"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control border-left-0" name="family_name" placeholder="familyname">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-6 p-2">
                            <div class="row">
                                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Mother Name</label>
                                <div class="col-lg-9 col-sm-9 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="icon-pencil6"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control border-left-0" name="mother_name" placeholder="mother name">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-6 p-2">
                            <div class="row">
                                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">BirthDate</label>
                                <div class="col-lg-9 col-sm-9 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="icon-pencil6"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control border-left-0" placeholder="date of birthday" name="date_of_birthday">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-6 p-2">
                            <div class="row">
                                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">SEX</label>
                                <div class="col-lg-9 col-sm-9 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="icon-pencil6"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control border-left-0" placeholder="sex" name="sex">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-6 p-2">
                            <div class="row">
                                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Patiant Alias</label>
                                <div class="col-lg-9 col-sm-9 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="icon-pencil6"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control border-left-0" placeholder="patiant alias" name="patiant_alias">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-6 p-2">
                            <div class="row">
                                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Patiant Adress</label>
                                <div class="col-lg-9 col-sm-9 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="icon-pencil6"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control border-left-0" placeholder="patiant adress" name="patiant_adress">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-6 p-2">
                            <div class="row">
                                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Country Code</label>
                                <div class="col-lg-9 col-sm-9 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="icon-pencil6"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control border-left-0" placeholder="country code" name="country_code">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-6 p-2">
                            <div class="row">
                                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Phone Number Home</label>
                                <div class="col-lg-9 col-sm-9 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="icon-pencil6"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control border-left-0" placeholder="phone number home" name="phone_number_home">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-6 p-2">
                            <div class="row">
                                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Phone Number Business</label>
                                <div class="col-lg-9 col-sm-9 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="icon-pencil6"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control border-left-0" placeholder="phone number business" name="phone_number_business">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-6 p-2">
                            <div class="row">
                                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Primary Languages</label>
                                <div class="col-lg-9 col-sm-9 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="icon-pencil6"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control border-left-0" placeholder="primary language" name="primary_language">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-6 p-2">
                            <div class="row">
                                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Material Status</label>
                                <div class="col-lg-9 col-sm-9 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="icon-pencil6"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control border-left-0" placeholder="material status" name="material_status">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-6 p-2">
                            <div class="row">
                                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Religion</label>
                                <div class="col-lg-9 col-sm-9 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="icon-pencil6"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control border-left-0" placeholder="religion" name="religion">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-6 p-2">
                            <div class="row">
                                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Patiant Account Number</label>
                                <div class="col-lg-9 col-sm-9 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="icon-pencil6"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control border-left-0" placeholder="patiant account number" name="patiant_account_number">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-6 p-2">
                            <div class="row">
                                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">SSN Number</label>
                                <div class="col-lg-9 col-sm-9 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="icon-pencil6"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control border-left-0" placeholder="ssn number" name="ssn_number">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-6 p-2">
                            <div class="row">
                                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Driver License Number</label>
                                <div class="col-lg-9 col-sm-9 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="icon-pencil6"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control border-left-0" placeholder="driver license number" name="driver_license_number">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-6 p-2">
                            <div class="row">
                                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Ethinc Number</label>
                                <div class="col-lg-9 col-sm-9 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="icon-pencil6"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control border-left-0" placeholder="ethinc number" name="ethinc_number">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-6 p-2">
                            <div class="row">
                                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Birth Place</label>
                                <div class="col-lg-9 col-sm-9 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="icon-pencil6"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control border-left-0" placeholder="birth Place" name="birth_Place">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-6 p-2">
                            <div class="row">
                                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Nationality</label>
                                <div class="col-lg-9 col-sm-9 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="icon-pencil6"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control border-left-0" placeholder="nationality" name="nationality">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-6 p-2">
                            <div class="row">
                                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Patiant Deth Date Time</label>
                                <div class="col-lg-9 col-sm-9 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="icon-pencil6"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control border-left-0" placeholder="patiant deth date time" name="patiant_deth_date_time">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-6 p-2">
                            <div class="row">
                                <label class="col-form-label col-lg-3 col-sm-3 col-md-3 text-left">Patiant Death Indicator</label>
                                <div class="col-lg-9 col-sm-9 col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text bg-primary border-primary text-white">
                                                <i class="icon-pencil6"></i>
                                            </span>
                                        </span>
                                        <input type="text" class="form-control border-left-0" placeholder="patiant death indicator" name="patiant_death_indicator">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-lg-6 p-2">
                            <div class="row">
                                <div class="custom-control custom-control-right custom-switch text-right p-2">
                                    <input type="checkbox" class="custom-control-input " id="sc_rs_c" name="active" checked="">
                                    <label class="custom-control-label " for="sc_rs_c">Active</label>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                <div class=" modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="icon-cross2 font-size-base mr-1"></i> Close</button>
                    <button type="submit" class="btn btn-primary"> <i class="icon-checkmark3 font-size-base mr-1"></i> Save changes</button>
                </div>
            </div>
        </div>
</form>