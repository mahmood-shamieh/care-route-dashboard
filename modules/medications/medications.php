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
                    targets: [6]
                }],
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


<div class="card">
    <table class="table datatable-button-html5-columns table-bordered dataTable">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Job Title</th>
                <th>DOB</th>
                <th>Status</th>
                <th>Salary</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Marth</td>
                <td><a href="datatable_extension_buttons_html5.html#">Enright</a></td>
                <td>Traffic Court Referee</td>
                <td>22 Jun 1972</td>
                <td><span class="badge badge-success">Active</span></td>
                <td>$85,600</td>
                <td class="text-center">
                    <div class="list-icons">
                        <div class="dropdown">
                            <a href="datatable_basic.html#" class="list-icons-item" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="datatable_basic.html#" class="dropdown-item"><i class="icon-file-pdf"></i> Export to .pdf</a>
                                <a href="datatable_basic.html#" class="dropdown-item"><i class="icon-file-excel"></i> Export to .csv</a>
                                <a href="datatable_basic.html#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Marth</td>
                <td><a href="datatable_extension_buttons_html5.html#">Enright</a></td>
                <td>Traffic Court Referee</td>
                <td>22 Jun 1972</td>
                <td><span class="badge badge-success">Active</span></td>
                <td>$85,600</td>
                <td class="text-center">
                    <div class="list-icons">
                        <div class="dropdown">
                            <a href="datatable_basic.html#" class="list-icons-item" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="datatable_basic.html#" class="dropdown-item"><i class="icon-file-pdf"></i> Export to .pdf</a>
                                <a href="datatable_basic.html#" class="dropdown-item"><i class="icon-file-excel"></i> Export to .csv</a>
                                <a href="datatable_basic.html#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Marth</td>
                <td><a href="datatable_extension_buttons_html5.html#">Enright</a></td>
                <td>Traffic Court Referee</td>
                <td>22 Jun 1972</td>
                <td><span class="badge badge-success">Active</span></td>
                <td>$85,600</td>
                <td class="text-center">
                    <div class="list-icons">
                        <div class="dropdown">
                            <a href="datatable_basic.html#" class="list-icons-item" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="datatable_basic.html#" class="dropdown-item"><i class="icon-file-pdf"></i> Export to .pdf</a>
                                <a href="datatable_basic.html#" class="dropdown-item"><i class="icon-file-excel"></i> Export to .csv</a>
                                <a href="datatable_basic.html#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Marth</td>
                <td><a href="datatable_extension_buttons_html5.html#">Enright</a></td>
                <td>Traffic Court Referee</td>
                <td>22 Jun 1972</td>
                <td><span class="badge badge-success">Active</span></td>
                <td>$85,600</td>
                <td class="text-center">
                    <div class="list-icons">
                        <div class="dropdown">
                            <a href="datatable_basic.html#" class="list-icons-item" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="datatable_basic.html#" class="dropdown-item"><i class="icon-file-pdf"></i> Export to .pdf</a>
                                <a href="datatable_basic.html#" class="dropdown-item"><i class="icon-file-excel"></i> Export to .csv</a>
                                <a href="datatable_basic.html#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Marth</td>
                <td><a href="datatable_extension_buttons_html5.html#">Enright</a></td>
                <td>Traffic Court Referee</td>
                <td>22 Jun 1972</td>
                <td><span class="badge badge-success">Active</span></td>
                <td>$85,600</td>
                <td class="text-center">
                    <div class="list-icons">
                        <div class="dropdown">
                            <a href="datatable_basic.html#" class="list-icons-item" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="datatable_basic.html#" class="dropdown-item"><i class="icon-file-pdf"></i> Export to .pdf</a>
                                <a href="datatable_basic.html#" class="dropdown-item"><i class="icon-file-excel"></i> Export to .csv</a>
                                <a href="datatable_basic.html#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Marth</td>
                <td><a href="datatable_extension_buttons_html5.html#">Enright</a></td>
                <td>Traffic Court Referee</td>
                <td>22 Jun 1972</td>
                <td><span class="badge badge-success">Active</span></td>
                <td>$85,600</td>
                <td class="text-center">
                    <div class="list-icons">
                        <div class="dropdown">
                            <a href="datatable_basic.html#" class="list-icons-item" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="datatable_basic.html#" class="dropdown-item"><i class="icon-file-pdf"></i> Export to .pdf</a>
                                <a href="datatable_basic.html#" class="dropdown-item"><i class="icon-file-excel"></i> Export to .csv</a>
                                <a href="datatable_basic.html#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Marth</td>
                <td><a href="datatable_extension_buttons_html5.html#">Enright</a></td>
                <td>Traffic Court Referee</td>
                <td>22 Jun 1972</td>
                <td><span class="badge badge-success">Active</span></td>
                <td>$85,600</td>
                <td class="text-center">
                    <div class="list-icons">
                        <div class="dropdown">
                            <a href="datatable_basic.html#" class="list-icons-item" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="datatable_basic.html#" class="dropdown-item"><i class="icon-file-pdf"></i> Export to .pdf</a>
                                <a href="datatable_basic.html#" class="dropdown-item"><i class="icon-file-excel"></i> Export to .csv</a>
                                <a href="datatable_basic.html#" class="dropdown-item"><i class="icon-file-word"></i> Export to .doc</a>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>

        </tbody>
    </table>
</div>