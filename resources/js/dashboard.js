export function initDashboardCharts(data) {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    if (document.getElementById('revenueChart')) {
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    data: Object.values(data.revenue),
                    borderColor: '#4F46E5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }

    if (document.getElementById('transactionChart')) {
        new Chart(document.getElementById('transactionChart'), {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    data: Object.values(data.transactions),
                    backgroundColor: '#10B981',
                    borderRadius: 6
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }

    if (document.getElementById('statusChart')) {
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Berhasil', 'Pending', 'Gagal'],
                datasets: [{
                    data: [data.status.success, data.status.pending, data.status.failed],
                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444']
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }
}