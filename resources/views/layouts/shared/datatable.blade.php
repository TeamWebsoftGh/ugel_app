@include("layouts.shared.dt-scripts")

<!-- Datatables init -->
<script src="/js/forms.js"></script>
<script>
    $('document').ready(function (){
        // $('.dataTables_scrollBody').perfectScrollbar();
        $('.dataTables_scrollBody').each(function(){
            new SimpleBar($(this)[0], { autoHide: false });
        });
    })

</script>

