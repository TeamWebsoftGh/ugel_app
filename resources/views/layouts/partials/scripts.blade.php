<!-- JAVASCRIPT -->
<script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/libs/simplebar/simplebar.min.js"></script>
<script src="/assets/libs/node-waves/waves.min.js"></script>
<script src="/assets/libs/feather-icons/feather.min.js"></script>
<script src="/assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
<script type="text/javascript" src="/assets/libs/flatpickr/flatpickr.min.js"></script>
<script type="text/javascript" src="/assets/libs/bootstrap/js/bootstrap-select.min.js"></script>


<!-- prismjs plugin -->
<script src="/assets/libs/prismjs/prism.js"></script>

<!-- apexcharts -->
<script src="/assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- Chart JS -->
<script src="/assets/libs/chart.js/chart.min.js"></script>

<!-- chartjs init -->
<script src="/assets/js/pages/chartjs.init.js"></script>

<!--Swiper slider js-->
<script src="/assets/libs/swiper/swiper-bundle.min.js"></script>

<!-- dropify min -->
<script src="/plugins/dropify/dropify.min.js"></script>

<!-- dropzone min -->
<script src="/assets/libs/dropzone/dropzone-min.js"></script>

<script src="/assets/js/pages/form-validation.init.js"></script>

<script src="/plugins/summernote/summernote-bs5.js"></script>

<!-- list.js min js -->
<script src="/assets/libs/list.js/list.min.js"></script>

<!--list pagination js-->
<script src="/assets/libs/list.pagination.js/list.pagination.min.js"></script>

<!-- Sweet Alerts js -->
<script src="/assets/libs/sweetalert2/sweetalert2.min.js"></script>

<script src="/js/bootbox/bootbox.min.js"></script>
<script src="/js/main.js"></script>

<!-- App js -->
<script src="/assets/js/app.js"></script>
<script src="/assets/js/custom.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<!-- ecommerce product details init -->
<script src="/assets/js/pages/ecommerce-product-details.init.js"></script>

<script>
    var dateFormat = @json(env('Date_Format'));
    function ShowItem(url, target)
    {
        $.ajax({
            type: "GET",
            url: url,
            timeout:60000,
            datatype: "json",
            cache: false,
            error: function(XMLHttpRequest, textStatus, errorThrown){
                HandleJSONPOSTErrors(XMLHttpRequest, textStatus, errorThrown);
            },
            success: function (data) {
                $(target).html("");
                $(target).html(data);
            },
        });
    }
</script>
