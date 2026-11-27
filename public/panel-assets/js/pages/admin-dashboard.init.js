(function () {
    "use strict";

    if (typeof ApexCharts === "undefined") {
        return;
    }

    const data = window.adminDashboardCharts || {};
    const brand = "#EA580C";
    const muted = "#95a0c5";

    const sparkOptions = function (seriesData, color) {
        return {
            series: [{ data: seriesData }],
            chart: {
                type: "line",
                width: 120,
                height: 35,
                sparkline: { enabled: true },
                dropShadow: {
                    enabled: true,
                    top: 4,
                    left: 0,
                    bottom: 0,
                    right: 0,
                    blur: 2,
                    color: "rgba(234, 88, 12, 0.3)",
                    opacity: 0.35,
                },
            },
            colors: [color],
            stroke: {
                show: true,
                curve: "smooth",
                width: [3],
                lineCap: "round",
            },
            tooltip: {
                fixed: { enabled: false },
                x: { show: false },
                y: {
                    title: {
                        formatter: function () {
                            return "";
                        },
                    },
                },
                marker: { show: false },
            },
        };
    };

    const line1 = document.querySelector("#line-1");
    if (line1) {
        new ApexCharts(
            line1,
            sparkOptions([4, 8, 6, 12, 9, 14, 11, 16, 13, 18, 15, Math.max(data.bookingCount || 2, 2)], brand)
        ).render();
    }

    const line2 = document.querySelector("#line-2");
    if (line2) {
        new ApexCharts(
            line2,
            sparkOptions([10, 12, 11, 14, 13, 16, 15, 18, 17, 20, 19, Math.max(data.packageCount || 4, 4)], muted)
        ).render();
    }

    const overview = document.querySelector("#monthly_income");
    if (overview) {
        const values = [
            Math.max(data.newBookingCount || 1, 1),
            Math.max(Math.round((data.bookingCount || 2) * 0.4), 1),
            Math.max(Math.round((data.packageCount || 4) * 0.2), 1),
            Math.max(data.destinationCount || 2, 1),
            Math.max(data.blogPostCount || 1, 1),
            Math.max(data.publishedBlogPostCount || 1, 1),
            Math.max(Math.round((data.bookingCount || 2) * 0.3), 1),
            Math.max(Math.round((data.packageCount || 4) * 0.15), 1),
            Math.max(Math.round((data.destinationCount || 2) * 0.8), 1),
            Math.max(Math.round((data.blogPostCount || 1) * 0.7), 1),
            Math.max(Math.round((data.bookingCount || 2) * 0.5), 1),
            Math.max(data.packageCount || 2, 1),
        ];

        new ApexCharts(overview, {
            chart: {
                height: 270,
                type: "bar",
                toolbar: { show: false },
                dropShadow: {
                    enabled: true,
                    top: 0,
                    left: 5,
                    bottom: 5,
                    right: 0,
                    blur: 5,
                    color: "#45404a2e",
                    opacity: 0.35,
                },
            },
            colors: [muted, muted, muted, brand, muted, muted, muted, muted, muted, muted, muted, muted],
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    dataLabels: { position: "top" },
                    columnWidth: "20",
                    distributed: true,
                },
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val;
                },
                offsetY: -20,
                style: {
                    fontSize: "12px",
                    colors: ["#8997bd"],
                },
            },
            series: [{ name: "Activity", data: values }],
            xaxis: {
                categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                position: "top",
                axisBorder: { show: false },
                axisTicks: { show: false },
            },
            yaxis: {
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { show: true },
            },
            grid: {
                row: { colors: ["transparent", "transparent"], opacity: 0.2 },
                strokeDashArray: 2.5,
            },
            legend: { show: false },
        }).render();
    }

    const customers = document.querySelector("#customers");
    if (customers) {
        const total = Math.max(data.bookingCount || 0, 1);
        const neu = Math.max(data.newBookingCount || 0, 0);
        const reviewed = Math.max(total - neu, 0);
        const other = Math.max(total - neu - Math.round(reviewed * 0.5), 0);

        new ApexCharts(customers, {
            chart: {
                height: 280,
                type: "donut",
            },
            plotOptions: {
                pie: {
                    donut: { size: "80%" },
                },
            },
            dataLabels: { enabled: false },
            stroke: {
                show: true,
                width: 2,
                colors: ["transparent"],
            },
            series: [Math.max(neu, 1), Math.max(Math.round(reviewed * 0.5), 1), Math.max(other, 1)],
            legend: {
                show: true,
                position: "bottom",
                horizontalAlign: "center",
                fontSize: "13px",
                fontFamily: "Be Vietnam Pro, sans-serif",
            },
            labels: ["New", "Reviewed", "Other"],
            colors: [brand, "#08b0e7", "#ffc728"],
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val;
                    },
                },
            },
        }).render();
    }
})();
