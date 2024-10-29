function getTaskStatusData(data) {
    let options, chart;
    ((options = {
        series: data['values'],
        labels: data['labels'],
        chart: { type: "donut", height: 230 },
        plotOptions: { pie: { size: 100, offsetX: 0, offsetY: 0, donut: { size: "90%", labels: { show: !1 } } } },
        dataLabels: { enabled: !1 },
        legend: { show: !1 },
        stroke: { lineCap: "round", width: 0 },
        colors: data['colors'],
    }),
        (chart = new ApexCharts(document.querySelector("#task-status-chart"), options)).render());

}
function getTaskStatusChart(startDate, endDate) {
    // Bar chart

    if ($('#task-status-chart').length) {
        $.ajax({
            type: "GET",
            url: "/report/task-status-chart",
            data: ({ startDate: startDate, endDate: endDate }),
            contentType: "application/json; charset=utf-8",
            async: true,
            success: function (data) {
                // document.getElementById("chartContent").innerHTML = '';
                // document.getElementById("chartContent").innerHTML = '<div id="totalSearchBarChart"></div>';

                console.log("ok")
                getTaskStatusData(data);
                // $("#idCount").html(numberWithCommas(voters + drivers + passport + ssnit + oldvoters));
                // //$("#driverCount").html(numberWithCommas(drivers));
                // //$("#passportCount").html(numberWithCommas(passport));
                // //$("#ssnitCount").html(numberWithCommas(ssnit));
                // $("#sanctionCount").html(numberWithCommas(sanction));
                // $("#addressCount").html(numberWithCommas(address));
                // $("#facialCount").html(numberWithCommas(facial));
                // $("#totalCount").html(numberWithCommas(voters + drivers + passport + ssnit + sanction + address + facial + oldvoters));
            }
        });
    }
}

