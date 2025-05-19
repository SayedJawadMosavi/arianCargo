@extends('layouts.app')

@section('content')

<div class="row ">



<div class="col-lg-6 col-md-6 col-sm-12 col-xl-6">
        <div class="chart-container">
            <canvas id="myPieChart"></canvas>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-6">
        <div class="chart-container">
            <canvas id="dailyPaymentsChart"></canvas>
        </div>
    </div>


    <div class="col-lg-6 col-md-6 col-sm-12 col-xl-12">
        <div class="chart-container">
        <h3 id="chartTitle">Monthly Report - <span id="yearDisplay"></span></h3>
            <canvas id="monthlySalesChart"></canvas>
        </div>
        <div class="button-container mt-2">
            <button id="previousButton" class="nav-button">Previous</button>
            <button id="nextButton" class="nav-button">Next</button>
        </div>
    </div>



</div>

<script>
      document.addEventListener('DOMContentLoaded', () => {


        const ctx = document.getElementById('myPieChart').getContext('2d');

        const data = {
            labels: ['Users', 'Cargos', 'Staffs','Clients'],
            datasets: [{
                label: 'My First Dataset',
                data: [{!! $users !!}, {!! $cargos !!}, {!! $staffs !!},{!! $clients !!}],
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(255, 99, 1)',
                    'rgb(153, 102, 255)',
                    'rgb(54, 162, 235, 0.6)',

                ],
                hoverOffset: 4
            }]
        };

        const config = {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        padding: 20,
                    },
                    title: {
                        display: true,
                        text: 'Common Information'
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        };

        const myPieChart = new Chart(ctx, config);
    });
    document.addEventListener('DOMContentLoaded', (event) => {
        var payments = {!! json_encode($payments) !!};
        var expenses = {!! json_encode($expenses) !!};
        var incomes = {!! json_encode($incomes) !!};

        function getNestedField(obj, path) {
            return path.split('.').reduce((acc, part) => acc && acc[part], obj);
        }

        function getMonthlyQuantities(data, fieldName, dateFieldName, year) {
            const monthlyQuantities = Array(12).fill(0);

            data.forEach(item => {
                const date = new Date(getNestedField(item, dateFieldName));
                if (date.getFullYear() === year) {
                    const month = date.getMonth();
                    monthlyQuantities[month] += item[fieldName];
                }
            });

            return monthlyQuantities;
        }
        let currentYear = new Date().getFullYear();
        const yearDisplay = document.getElementById('yearDisplay');
        yearDisplay.textContent = currentYear;

        function updateCharts(year) {
            const paymentMonthlyQuantities = getMonthlyQuantities(payments, 'amount', 'miladi_date', year);
            const expenseMonthlyQuantities = getMonthlyQuantities(expenses, 'amount', 'miladi_date', year);
            const incomeMonthlyQuantities = getMonthlyQuantities(incomes, 'amount', 'miladi_date', year);


            monthlySalesChart.data.datasets[0].data = paymentMonthlyQuantities;
            monthlySalesChart.data.datasets[1].data = expenseMonthlyQuantities;
            monthlySalesChart.data.datasets[2].data = incomeMonthlyQuantities;
            monthlySalesChart.options.plugins.title.text = `Monthly Report - ${year}`;
            monthlySalesChart.update();

            yearDisplay.textContent = year;
        }
          const paymentMonthlyQuantities = getMonthlyQuantities(payments, 'amount', 'miladi_date', currentYear);
        const expenseMonthlyQuantities = getMonthlyQuantities(expenses, 'amount', 'miladi_date', currentYear);
        const incomeMonthlyQuantities = getMonthlyQuantities(incomes, 'amount', 'miladi_date', currentYear);

        const monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');
        // Create gradient for Receives
        const paymentGradient = monthlySalesCtx.createLinearGradient(0, 0, 0, 400);
        paymentGradient.addColorStop(0, 'rgba(75, 192, 192, 0.6)');
        paymentGradient.addColorStop(1, 'rgba(75, 192, 192, 0.1)');

        // Create gradient for Transfers
        const expenseGradient = monthlySalesCtx.createLinearGradient(0, 0, 0, 400);
        expenseGradient.addColorStop(0, 'rgba(153, 102, 255, 0.6)');
        expenseGradient.addColorStop(1, 'rgba(153, 102, 255, 0.1)');

        // Create gradient for Distributions
        const incomeGradient = monthlySalesCtx.createLinearGradient(0, 0, 0, 400);
        incomeGradient.addColorStop(0, 'rgba(255, 206, 86, 0.6)');
        incomeGradient.addColorStop(1, 'rgba(255, 206, 86, 0.1)');
     const monthlySalesChart = new Chart(monthlySalesCtx, {
            type: 'line',
            data: {
                labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                datasets: [
            {
                label: 'Cargo Payments',
                data: paymentMonthlyQuantities,
                backgroundColor: paymentGradient,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4, // Adds a smooth curve to the line
                pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                pointRadius: 5, // Increase point size
                pointHoverRadius: 8, // Increase point size on hover
                pointStyle: 'circle' // Change point style
            },
            {
                label: 'Expenses',
                data: expenseMonthlyQuantities,
                backgroundColor: expenseGradient,
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgba(153, 102, 255, 1)',
                pointRadius: 5,
                pointHoverRadius: 8,
                pointStyle: 'circle'
            },
            {
                label: 'Incomes',
                data: incomeMonthlyQuantities,
                backgroundColor: incomeGradient,
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgba(255, 206, 86, 1)',
                pointRadius: 5,
                pointHoverRadius: 8,
                pointStyle: 'circle'
            },

        ]
        },
        options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: `Monthly Report - ${currentYear}`,
                font: {
                    size: 18,
                    weight: 'bold'
                },
                padding: {
                    top: 10,
                    bottom: 30
                }
            },
            legend: {
                display: true,
                position: 'top',
                labels: {
                    usePointStyle: true, // Use point style in legend
                    boxWidth: 10
                }
            },
            tooltip: {
                enabled: true,
                mode: 'nearest',
                intersect: false,
                axis: 'x',
                backgroundColor: 'rgba(0, 0, 0, 0.7)',
                titleFont: {
                    size: 16,
                    weight: 'bold'
                },
                bodyFont: {
                    size: 14
                },
                padding: 10
            }
        },
        interaction: {
            mode: 'nearest',
            axis: 'x',
            intersect: false
        },
        hover: {
            mode: 'nearest',
            intersect: false
        },
        animation: {
            duration: 1000, // Animation duration in ms
            easing: 'easeInOutQuad' // Easing function
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Quantity',
                    font: {
                        size: 14,
                        style: 'italic'
                    }
                },
                grid: {
                    color: 'rgba(200, 200, 200, 0.3)',
                    borderDash: [5, 5]
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Month',
                    font: {
                        size: 14,
                        style: 'italic'
                    }
                },
                grid: {
                    color: 'rgba(200, 200, 200, 0.3)',
                    borderDash: [5, 5]
                }
            }
        }
    }
    });
      const dailyPaymentsCtx = document.getElementById('dailyPaymentsChart').getContext('2d');
    const dailyPayments = {!! json_encode($daily_payments) !!};

    const labels = dailyPayments.map(item => item.date);
    const data = dailyPayments.map(item => item.total);

    const chartData = {
        labels: labels,
        datasets: [{
            label: 'Daily Payments',
            data: data,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1,
            fill: true,
        }]
    };

    const dailyPaymentsChart = new Chart(dailyPaymentsCtx, {
        type: 'doughnut', // use 'line' if you prefer line chart
        data: chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Payment Amount'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                }
            }
        }
    });
 function updateChartData(newData) {
        departmentSalesChart.data.datasets[0].data = newData.quantities;
        departmentSalesChart.data.labels = newData.labels;
        departmentSalesChart.update();
    }




    document.getElementById('nextButton').addEventListener('click', () => {
        currentYear++;
        updateCharts(currentYear);
    });

    document.getElementById('previousButton').addEventListener('click', () => {
        currentYear--;
        updateCharts(currentYear);
    });
    });
</script>

@endsection
<style>

    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }

    .dashboard {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .metrics-row {
        display: flex;
        gap: 20px;
    }

    .card {
        flex: 1;
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        padding: 20px;
        text-align: center;
    }

    .card-content {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .chart-container {
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-top: 20px;
    }

    canvas {
        width: 100% !important;
        height: 150px !important;
    }

    .chart-container canvas {
        height: 300px !important;
    }
    .button-container {
    display: flex;
    justify-content: center;
    margin: 20px 0;
}

.nav-button {
    background-color: #6c5ffc; /* Green background */
    border: none; /* Remove borders */
    color: white; /* White text */
    padding: 10px 20px; /* Smaller padding */
    text-align: center; /* Center the text */
    text-decoration: none; /* Remove underline */
    display: inline-block; /* Make the buttons appear inline */
    font-size: 14px; /* Smaller font size */
    margin: 4px 2px; /* Add some margin */
    cursor: pointer; /* Add a pointer on hover */
    border-radius: 8px; /* Rounded corners */
    transition: background-color 0.3s ease, transform 0.3s ease; /* Smooth transition for background and transform */
}

.nav-button:hover {
    background-color: #6c5ffc; /* Darker green on hover */
    transform: scale(1.1); /* Scale up the button */
}

.nav-button:active {
    transform: scale(0.95); /* Scale down the button when clicked */
}
</style>
