/*
 File: Datatables
*/
document.addEventListener("DOMContentLoaded", function() {
    new DataTable("#example")
}), document.addEventListener("DOMContentLoaded", function() {
    new DataTable("#scroll-vertical", {
        scrollY: "210px",
        scrollCollapse: !0,
        paging: !1
    })
}), document.addEventListener("DOMContentLoaded", function() {
    new DataTable("#scroll-horizontal", {
        scrollX: !0
    })
}), document.addEventListener("DOMContentLoaded", function() {
    new DataTable("#alternative-pagination", {
        pagingType: "full_numbers"
    })
}), $(document).ready(function() {
    var e = $("#add-rows").DataTable(),
        a = 1;
    $("#addRow").on("click", function() {
        e.row.add([a + ".1", a + ".2", a + ".3", a + ".4", a + ".5", a + ".6", a + ".7", a + ".8", a + ".9", a + ".10", a + ".11", a + ".12"]).draw(!1), a++
    }), $("#addRow").click()
}), $(document).ready(function() {
    $("#example").DataTable()
}), document.addEventListener("DOMContentLoaded", function() {
    new DataTable("#fixed-header", {
        fixedHeader: !0
    })
}), document.addEventListener("DOMContentLoaded", function() {
    new DataTable("#model-datatables", {
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.modal({
                    header: function(e) {
                        e = e.data();
                        return "Details for " + e[0] + " " + e[1]
                    }
                }),
                renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                    tableClass: "table"
                })
            }
        }
    })
}), document.addEventListener("DOMContentLoaded", function() {
    new DataTable("#buttons-datatables", {
        dom: "Bfrtip",
        buttons: ["copy", "csv", "excel", "print", "pdf"]
    })
}), document.addEventListener("DOMContentLoaded", function() {
    new DataTable("#ajax-datatables", {
        ajax: "offers/json/datatable.json"
    })
});

$(document).ready(function() {
        $(".basic-datatable").DataTable( {
                language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>"
                    }
                }
                , drawCallback:function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            }
        );
        $(".report-datatable").DataTable( {
                language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>"
                    }
                },
                scrollY: 480,
                'aasorting':-1,
                "order": [0, "desc"],
                'lengthMenu': [[25, 50, 100, 200, 500, 1000, -1], [25, 50, 100, 200, 500, 1000, "All"]]
                , drawCallback:function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            }
        );
        $(".task-datatable").DataTable( {
                'aasorting':-1,
                'searching': true,
                'paging': false,
                "bInfo": false,
                "order": [0, "desc"],
            }
        );
        let oTable = $("#mini-datatable").DataTable( {
                language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>"
                    }
                }
                , drawCallback:function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                },
                'aasorting':-1,
                'searching': true,
                'paging': false,
                "bInfo": false,
                "order": [0, "asc"],
                scrollY: "60vh"
            },

        );
        $("#mini-datatable_filter").hide();
        $('#filtercolumn_name').on('keyup', function () {
            oTable.columns(1).search(this.value).draw();
        });
        let oTable2 = $(".dt-mini").DataTable( {
                language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>"
                    }
                }
                , drawCallback:function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                },
                'aasorting':-1,
                'searching': false,
                'paging': false,
                "bInfo": false,
                "order": [0, "asc"],
                scrollY: 300
            },
        );
        $(".dt-mini_filter").hide();
        $('#filtercolumn_name').on('keyup', function () {
            oTable2.columns(1).search(this.value).draw();
        });
        var a=$("#datatable-buttons").DataTable( {
            lengthChange:!1,
            buttons:["copy", "print", "pdf", "csv"],
            scrollY: "60vh",
            pageLength: 25,
            language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>"
                    }
                },
            drawCallback:function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            }
        );
        var a=$(".dashboard-table").DataTable( {
                lengthChange:!1,
                buttons:["copy", "print", "pdf", "csv"],
                language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>"
                    }
                }
                , drawCallback:function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                },
                "bInfo": false,
                scrollY: 300
            },
        );
        $("#selection-datatable").DataTable( {
                select: {
                    style: "multi"
                }
                , language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>"
                    }
                }
                , drawCallback:function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            }
        ), $("#key-datatable").DataTable( {
                keys:!0, language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>"
                    }
                }
                , drawCallback:function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            }
        ), a.buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)"), $("#complex-header-datatable").DataTable( {
                language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>"
                    }
                }
                , drawCallback:function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
                , columnDefs:[ {
                    visible: !1, targets: -1
                }
                ]
            }
        ), $("#state-saving-datatable").DataTable( {
                stateSave:!0, language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>", next: "<i class='mdi mdi-chevron-right'>"
                    }
                }
                , drawCallback:function() {
                    $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                }
            }
        )
    }

);
