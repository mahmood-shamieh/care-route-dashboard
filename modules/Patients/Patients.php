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
                            <a href="" class="dropdown-item "><i class="fa-solid fa-plus"></i> add <?php print($currentSection['name']) ?></a>
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
                    // 'data': {
                    //     'section': <?php print($_GET['cmd']) ?>
                    // }
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
            setInterval(function() {
                $('#care-route').DataTable().ajax.reload()
            }, 3000);

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
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>