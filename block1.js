@endsection

@push('scripts')
    <script src="{{ asset('js/realtime-dashboard.js') }}"></script>
    <script>
        // Register Chart.js datalabels plugin
        try {
            Chart.register(ChartDataLabels);
        } catch (error) {
            console.warn('ChartDataLabels plugin not available:', error);
        }
        
        // Weekly Financial Chart
        try {
            const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
            const weeklyData = @json($weekly_data);
            const wGrad1 = weeklyCtx.createLinearGradient(0, 0, 0, 300);
            wGrad1.addColorStop(0, 'rgba(234,179,8,0.25)'); wGrad1.addColorStop(1, 'rgba(234,179,8,0.01)');
            const wGrad2 = weeklyCtx.createLinearGradient(0, 0, 0, 300);
            wGrad2.addColorStop(0, 'rgba(239,68,68,0.2)'); wGrad2.addColorStop(1, 'rgba(239,68,68,0.01)');
            const wGrad3 = weeklyCtx.createLinearGradient(0, 0, 0, 300);
            wGrad3.addColorStop(0, 'rgba(34,197,94,0.25)'); wGrad3.addColorStop(1, 'rgba(34,197,94,0.01)');
            window.weeklyChart = new Chart(weeklyCtx, {
                type: 'line',
                data: {
                    labels: weeklyData.map(d => d.day),
                    datasets: [
                        { label: 'Boundary', data: weeklyData.map(d => d.boundary), borderColor: '#eab308', backgroundColor: wGrad1, borderWidth: 2.5, tension: 0.45, fill: true, pointBackgroundColor: '#eab308', pointRadius: 4, pointHoverRadius: 7 },
                        { label: 'Expenses', data: weeklyData.map(d => d.expenses), borderColor: '#ef4444', backgroundColor: wGrad2, borderWidth: 2.5, tension: 0.45, fill: true, pointBackgroundColor: '#ef4444', pointRadius: 4, pointHoverRadius: 7 },
                        { label: 'Net Income', data: weeklyData.map(d => d.net), borderColor: '#22c55e', backgroundColor: wGrad3, borderWidth: 2.5, tension: 0.45, fill: true, pointBackgroundColor: '#22c55e', pointRadius: 4, pointHoverRadius: 7 }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { position: 'top', labels: { usePointStyle: true, pointStyleWidth: 10, font: { size: 12, weight: '600' }, padding: 18 } },
                        tooltip: { backgroundColor: 'rgba(15,23,42,0.95)', padding: 14, cornerRadius: 12, callbacks: { label: ctx => ` ${ctx.dataset.label}: â‚±${ctx.parsed.y.toLocaleString()}` } }
                    },
                    scales: {
                        x: { grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { font: { size: 11, weight: '600' }, color: '#64748b' } },
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { font: { size: 11 }, color: '#64748b', callback: v => 'â‚±' + v.toLocaleString() } }
                    }
                }
            });
        } catch (error) { console.error('Weekly Chart Error:', error); }

        // Unit Status Chart - (Handled by the premium donut chart below)


        // Revenue Trend Chart - Premium Line
        try {
            const revenueTrendCtx = document.getElementById('revenueTrendChart').getContext('2d');
            const revenueTrendData = @json($revenue_trend);
            const rGrad = revenueTrendCtx.createLinearGradient(0, 0, 0, 320);
            rGrad.addColorStop(0, 'rgba(37,99,235,0.3)'); rGrad.addColorStop(0.6, 'rgba(37,99,235,0.08)'); rGrad.addColorStop(1, 'rgba(37,99,235,0)');
            window.revenueTrendChart = new Chart(revenueTrendCtx, {
                type: 'line',
                data: {
                    labels: revenueTrendData.map(d => d.date),
                    datasets: [{
                        label: 'Revenue', data: revenueTrendData.map(d => d.revenue),
                        borderColor: '#2563eb', backgroundColor: rGrad,
                        borderWidth: 3, tension: 0.45, fill: true,
                        pointBackgroundColor: '#fff', pointBorderColor: '#2563eb', pointBorderWidth: 2.5,
                        pointRadius: 5, pointHoverRadius: 8, pointHoverBackgroundColor: '#2563eb'
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(15,23,42,0.95)', padding: 14, cornerRadius: 12,
                            callbacks: { label: ctx => ` Revenue: â‚±${ctx.parsed.y.toLocaleString()}` }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 11, weight: '600' }, color: '#94a3b8', maxRotation: 45 } },
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)', drawBorder: false },
                             ticks: { font: { size: 11 }, color: '#94a3b8', callback: v => 'â‚±' + v.toLocaleString() } }
                    }
                }
            });
        } catch (error) { console.error('Revenue Trend Chart Error:', error); }

        // Unit Performance Chart - Modernized Horizontal Enterprise View
        try {
            const unitPerformanceCtx = document.getElementById('unitPerformanceChart').getContext('2d');
            const unitPerformanceData = @json($unit_performance);
            
            // Create sleek gradients for a premium feel
            const actualGradient = unitPerformanceCtx.createLinearGradient(0, 0, 400, 0);
            actualGradient.addColorStop(0, '#3b82f6'); // Blue 500
            actualGradient.addColorStop(1, '#60a5fa'); // Blue 400
            
            const targetGradient = unitPerformanceCtx.createLinearGradient(0, 0, 400, 0);
            targetGradient.addColorStop(0, '#f59e0b'); // Amber 500
            targetGradient.addColorStop(1, '#fbbf24'); // Amber 400

            window.unitPerformanceChart = new Chart(unitPerformanceCtx, {
                type: 'bar',
                data: {
                    labels: unitPerformanceData.map(d => d.unit),
                    datasets: [
                        {
                            label: 'Actual Collected',
                            data: unitPerformanceData.map(d => d.performance),
                            backgroundColor: actualGradient,
                            borderColor: '#2563eb',
                            borderWidth: 0,
                            borderRadius: 6,
                            barThickness: 12,
                        },
                        {
                            label: 'Monthly Target (30 Days)',
                            data: unitPerformanceData.map(d => d.target),
                            backgroundColor: 'rgba(245, 158, 11, 0.15)', // Subtle target indicator
                            borderColor: '#f59e0b',
                            borderWidth: 1,
                            borderRadius: 6,
                            barThickness: 12,
                            borderDash: [5, 5] // Dashed look for target
                        }
                    ]
                },
                options: {
                    indexAxis: 'y', // Switch to horizontal for better Plate Number readability
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // Using custom legend in sidebar instead
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            padding: 12,
                            cornerRadius: 10,
                            titleFont: { size: 14, weight: 'bold' },
                            callbacks: {
                                label: function(context) {
                                    const val = context.parsed.x || 0;
                                    return ` â‚±${val.toLocaleString()}`;
                                },
                                footer: (items) => {
                                    const index = items[0].dataIndex;
                                    const data = unitPerformanceData[index];
                                    const diff = data.performance - data.target;
                                    const pct = ((data.performance / data.target) * 100).toFixed(1);
                                    return ` Achievement: ${pct}% of target\n Variance: â‚±${diff.toLocaleString()}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false },
                            ticks: { 
                                callback: function (value) { return 'â‚±' + value.toLocaleString(); },
                                font: { size: 10 }
                            }
                        },
                        y: {
                            grid: { display: false, drawBorder: false },
                            ticks: { 
                                font: { size: 11, weight: '700' },
                                color: '#334155'
                            }
                        }
                    }
                }
            });

            // Update Executive Insight: Top Performer
            if (unitPerformanceData && unitPerformanceData.length > 0) {
                const topUnit = unitPerformanceData[0]; // Data is sorted by performance descending
                document.getElementById('insightTopPlate').textContent = topUnit.unit;
            }
        } catch (error) {
            console.error('Unit Performance Chart Error:', error);
        }

        // Expense Breakdown Chart - Premium Pie
        try {
            const expenseBreakdownCtx = document.getElementById('expenseBreakdownChart').getContext('2d');
            let expenseBreakdownData = @json($expense_breakdown);
            let isPlaceholder = false;
            if (!expenseBreakdownData || expenseBreakdownData.length === 0 ||
                (Array.isArray(expenseBreakdownData) && expenseBreakdownData.every(d => d.amount === 0))) {
                isPlaceholder = true;
                expenseBreakdownData = [
                    { category: 'Maintenance', amount: 4500 },
                    { category: 'Fuel & Oil', amount: 3200 },
                    { category: 'Salaries', amount: 8000 },
                    { category: 'Parts', amount: 2100 },
                    { category: 'Others', amount: 1200 }
                ];
            }
            const pieColors = ['#ef4444','#f59e0b','#10b981','#3b82f6','#8b5cf6','#ec4899','#06b6d4'];
            const pieHover = ['#dc2626','#d97706','#059669','#2563eb','#7c3aed','#db2777','#0891b2'];
            window.expenseBreakdownChart = new Chart(expenseBreakdownCtx, {
                type: 'pie',
                data: {
                    labels: expenseBreakdownData.map(d => d.category),
                    datasets: [{ data: expenseBreakdownData.map(d => d.amount), backgroundColor: pieColors, hoverBackgroundColor: pieHover, borderWidth: 3, borderColor: '#fff', hoverOffset: 12 }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { usePointStyle: true, pointStyleWidth: 12, font: { size: 12, weight: '600' }, padding: 16, color: '#374151' } },
                        tooltip: {
                            backgroundColor: 'rgba(15,23,42,0.95)', padding: 14, cornerRadius: 12,
                            callbacks: {
                                label: function(ctx) {
                                    const total = ctx.dataset.data.reduce((a,b) => a+b, 0);
                                    const pct = ((ctx.parsed / total) * 100).toFixed(1);
                                    return ` ${ctx.label}: â‚±${ctx.parsed.toLocaleString()} (${pct}%)`;
                                }
                            }
                        },
                        datalabels: { color: '#fff', font: { weight: 'bold', size: 12 }, formatter: (val, ctx) => { const total = ctx.dataset.data.reduce((a,b)=>a+b,0); const pct = ((val/total)*100).toFixed(0); return pct > 5 ? pct+'%' : ''; } }
                    },
                    animation: { animateRotate: true, duration: 900, easing: 'easeOutQuart' }
                }
            });
        } catch (error) { console.error('Expense Chart Error:', error); }




        // Top Drivers Chart - Premium Horizontal Bar
        try {
            const topDriversCtx = document.getElementById('topDriversChart').getContext('2d');
            let topDriversData = @json($top_drivers);
            let isPlaceholder = false;
            if (!topDriversData || topDriversData.length === 0 ||
                (Array.isArray(topDriversData) && topDriversData.every(d => d.score === 0))) {
                isPlaceholder = true;
                topDriversData = [
                    { name: 'Bernardo Silva', score: 28, total: 42000 },
                    { name: 'Kevin De Bruyne', score: 26, total: 39000 },
                    { name: 'Erling Haaland', score: 25, total: 37500 },
                    { name: 'Phil Foden', score: 22, total: 33000 },
                    { name: 'Rodri Hernandez', score: 20, total: 30000 }
                ];
            }
            const barColors = topDriversData.map((_, i) => i===0?'#2563eb':i===1?'#7c3aed':i===2?'#0891b2':'#64748b');
            window.topDriversChart = new Chart(topDriversCtx, {
                type: 'bar',
                data: {
                    labels: topDriversData.map((d,i) => { const medals=['ðŸ¥‡','ðŸ¥ˆ','ðŸ¥‰']; return `${medals[i]||'  '} ${d.name}`; }),
                    datasets: [{ label: 'Reliability Score', data: topDriversData.map(d => d.score),
                        backgroundColor: barColors, borderColor: barColors, borderWidth: 0,
                        borderRadius: 10, borderSkipped: false, barThickness: 28 }]
                },
                options: {
                    indexAxis: 'y', responsive: true, maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { backgroundColor: 'rgba(15,23,42,0.95)', padding: 14, cornerRadius: 12, displayColors: false,
                            callbacks: {
                                label: ctx => ` â­ Reliability: ${ctx.parsed.x} clean service days`,
                                footer: items => { const amt = topDriversData[items[0].dataIndex].total; return ` â‚± Total Revenue: â‚±${amt.toLocaleString()}`; }
                            }
                        },
                        datalabels: { color: '#fff', font: { weight: 'bold', size: 12 }, anchor: 'end', align: 'start', offset: 8, formatter: v => v>0?v:'' }
                    },
                    scales: {
                        x: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false }, ticks: { font: { size: 11, weight: '500' }, color: '#94a3b8' } },
                        y: { grid: { display: false, drawBorder: false }, ticks: { font: { size: 13, weight: '600' }, color: '#1e293b' } }
                    },
                    animation: { duration: 1200, easing: 'easeOutQuart' }
                }
            });
        } catch (error) { console.error('Top Drivers Chart Error:', error); }




        // Unit Status Distribution Chart - Premium Donut
        try {
            const unitStatusDistCtx = document.getElementById('unitStatusChart').getContext('2d');
            const unitStatusDistData = @json($unit_status_distribution_data);
            const donutColors = ['#10b981','#3b82f6','#f59e0b','#ef4444'];
            const donutHover = ['#059669','#2563eb','#d97706','#dc2626'];
            let distLabels, distValues, distIsPlaceholder = false;
            if (!unitStatusDistData || unitStatusDistData.length === 0 || unitStatusDistData.every(d => d.count === 0)) {
                distIsPlaceholder = true;
                distLabels = ['Active','Maintenance','Coding','Retired'];
                distValues = [5,2,1,0];
            } else {
                distLabels = unitStatusDistData.map(d => d.status);
                distValues = unitStatusDistData.map(d => d.count);
            }
            const totalUnits = distValues.reduce((a,b) => a+b, 0);
            window.unitStatusChart = new Chart(unitStatusDistCtx, {
                type: 'doughnut',
                data: { labels: distLabels, datasets: [{ data: distValues, backgroundColor: donutColors, hoverBackgroundColor: donutHover, borderWidth: 4, borderColor: '#fff', hoverOffset: 16 }] },
                options: {
                    responsive: true, maintainAspectRatio: false, cutout: '72%',
                    plugins: {
                        legend: { position: 'right', labels: { usePointStyle: true, pointStyleWidth: 12, font: { size: 12, weight: '600' }, padding: 18, color: '#374151',
                            generateLabels: (chart) => chart.data.labels.map((label, i) => ({ text: `${label}: ${chart.data.datasets[0].data[i]}`, fillStyle: donutColors[i], strokeStyle: '#fff', lineWidth: 2, index: i })) } },
                        tooltip: { backgroundColor: 'rgba(15,23,42,0.95)', padding: 14, cornerRadius: 12,
                            callbacks: { label: ctx => { const total = ctx.dataset.data.reduce((a,b)=>a+b,0); const pct = total>0?((ctx.parsed/total)*100).toFixed(1):0; return ` ${ctx.label}: ${ctx.parsed} units (${pct}%)`; } } },
                        datalabels: { color: '#fff', font: { weight: 'bold', size: 13 }, formatter: (val, ctx) => { const sum = ctx.dataset.data.reduce((a,b)=>a+b,0); const pct = sum>0?((val/sum)*100).toFixed(0):0; return pct>5?pct+'%':''; } }
                    },
                    animation: { animateRotate: true, duration: 900, easing: 'easeOutQuart' }
                },
                plugins: [{ id: 'donutCenter', afterDraw(chart) {
                    const { ctx, chartArea: { left, top, right, bottom } } = chart;
                    const cx = (left+right)/2, cy = (top+bottom)/2;
                    const currentTotal = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                    ctx.save();
                    ctx.font = 'bold 28px Inter, sans-serif'; ctx.fillStyle = '#0f172a'; ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
                    ctx.fillText(currentTotal, cx, cy-10);
                    ctx.font = '600 11px Inter, sans-serif'; ctx.fillStyle = '#94a3b8';
                    ctx.fillText('TOTAL UNITS', cx, cy+14);
                    ctx.restore();
                }}]
            });
        } catch (error) { console.error('Unit Status Distribution Chart Error:', error); }

        </script><script>// Revenue Trend Period Selection
        function updateRevenueTrend(period) {
            // Update button styles
            document.querySelectorAll('[id^="btn-"]').forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white', 'hover:bg-blue-700');
                btn.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
            });
            
            // Highlight active button
            const activeBtn = document.getElementById('btn-' + period + 'days');
            if (activeBtn) {
                activeBtn.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                activeBtn.classList.add('bg-blue-600', 'text-white', 'hover:bg-blue-700');
            }
            
            // Fetch new data
            fetch(`/api/revenue-trend?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && window.revenueTrendChart) {
                        window.revenueTrendChart.data.labels = data.data.map(d => d.date);
                        window.revenueTrendChart.data.datasets[0].data = data.data.map(d => d.revenue);
                        window.revenueTrendChart.update('none');
                    }
                })
