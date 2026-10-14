(function () {
    'use strict';

    // =====================================================================
    // CHARTS
    // =====================================================================
    var trendRaw = window.DASHBOARD_DATA.trendRaw || [];
    var trendLabels = trendRaw.map(function (d) { return d.name; });
    var trendData   = trendRaw.map(function (d) { return d.count; });

    var statsValid   = window.DASHBOARD_DATA.statsValid || 0;
    var statsPending = window.DASHBOARD_DATA.statsPending || 0;
    var statsInvalid = window.DASHBOARD_DATA.statsInvalid || 0;

    // Bar Chart
    var trendChart = new ApexCharts(document.querySelector('#apex-trend-chart'), {
        chart: {
            type: 'bar',
            height: 280,
            toolbar: { show: false },
            fontFamily: 'Inter, sans-serif',
            animations: { enabled: true, easing: 'easeinout', speed: 600 }
        },
        series: [{ name: 'Pendaftar', data: trendData }],
        xaxis: {
            categories: trendLabels,
            labels: { style: { colors: '#43474e', fontSize: '12px' } },
            axisBorder: { show: false },
            axisTicks: { show: false }
        },
        yaxis: {
            labels: {
                style: { colors: '#43474e', fontSize: '12px' },
                formatter: function (v) { return Math.floor(v); }
            },
            min: 0
        },
        plotOptions: {
            bar: {
                borderRadius: 6,
                columnWidth: '55%',
                dataLabels: { position: 'top' }
            }
        },
        dataLabels: {
            enabled: true,
            offsetY: -18,
            style: { fontSize: '11px', colors: ['#002045'], fontWeight: 600 },
            formatter: function (v) { return v > 0 ? v : ''; }
        },
        colors: ['#002045'],
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: 'vertical',
                shadeIntensity: 0.5,
                gradientToColors: ['#1a365d'],
                opacityFrom: 1,
                opacityTo: 0.75,
                stops: [0, 100]
            }
        },
        grid: {
            borderColor: '#e7eeff',
            strokeDashArray: 4,
            xaxis: { lines: { show: false } }
        },
        tooltip: {
            y: { formatter: function (v) { return v + ' pendaftar'; } },
            theme: 'light'
        }
    });
    trendChart.render();

    // Donut Chart
    var donutChart = new ApexCharts(document.querySelector('#apex-donut-chart'), {
        chart: {
            type: 'donut',
            height: 200,
            fontFamily: 'Inter, sans-serif',
            animations: { enabled: true, easing: 'easeinout', speed: 600 }
        },
        series: [statsValid, statsPending, statsInvalid],
        labels: ['Valid', 'Pending', 'Tidak Valid'],
        colors: ['#002625', '#7d5700', '#ba1a1a'],
        dataLabels: { enabled: false },
        legend: {
            position: 'bottom',
            fontSize: '12px',
            labels: { colors: '#43474e' }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '13px',
                            fontWeight: 600,
                            color: '#43474e',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce(function (a, b) { return a + b; }, 0);
                            }
                        }
                    }
                }
            }
        },
        tooltip: {
            y: { formatter: function (v) { return v + ' pendaftar'; } },
            theme: 'light'
        }
    });
    donutChart.render();

    // =====================================================================
    // COUNT-UP ANIMATION FOR STAT CARDS
    // =====================================================================
    function animateCountUp(el, target) {
        var duration = 800;
        var startTime = null;

        function step(timestamp) {
            if (!startTime) startTime = timestamp;
            var progress = Math.min((timestamp - startTime) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.floor(eased * target).toLocaleString('id-ID');
            if (progress < 1) {
                requestAnimationFrame(step);
            } else {
                el.textContent = target.toLocaleString('id-ID');
            }
        }

        requestAnimationFrame(step);
    }

    function initCountUps() {
        var els = document.querySelectorAll('.stat-value[data-target]');
        els.forEach(function (el) {
            var target = parseInt(el.getAttribute('data-target'), 10);
            if (!isNaN(target) && target > 0) {
                animateCountUp(el, target);
            } else {
                el.textContent = '0';
            }
        });
    }

    // Run count-up after charts render (small delay for layout settle)
    setTimeout(initCountUps, 300);
})();
