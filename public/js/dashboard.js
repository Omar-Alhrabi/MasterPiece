// dashboard.js - Optimized chart initialization for admin dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Initialize earnings chart if it exists
    initializeEarningsChart();
    
    // Initialize project status chart if it exists
    initializeProjectStatusChart();
    
    // Initialize department chart if it exists
    initializeDepartmentChart();
    
    // Initialize task completion chart if it exists
    initializeTaskCompletionChart();
});

/**
 * Initialize the main earnings area chart
 */
function initializeEarningsChart() {
    var earningsChart = document.getElementById('earningsAreaChart');
    if (!earningsChart) return;
    
    var ctx = earningsChart.getContext('2d');
    
    // Get earnings data from the element or use default data
    var earningsData = [];
    try {
        var dataElement = document.getElementById('earnings-data');
        if (dataElement) {
            earningsData = JSON.parse(dataElement.getAttribute('data-earnings'));
        } else {
            // Default data if no element is found
            earningsData = [0, 10000, 5000, 15000, 10000, 20000, 15000, 25000, 20000, 30000, 25000, 40000];
        }
    } catch (e) {
        console.error('Error parsing earnings data:', e);
        earningsData = [0, 10000, 5000, 15000, 10000, 20000, 15000, 25000, 20000, 30000, 25000, 40000];
    }
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: "Earnings",
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: earningsData,
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                xAxes: [{
                    time: {
                        unit: 'month'
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 12
                    }
                }],
                yAxes: [{
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                        // Include a dollar sign in the ticks
                        callback: function(value, index, values) {
                            return '$' + number_format(value);
                        }
                    },
                    gridLines: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }],
            },
            legend: {
                display: false
            },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': $' + number_format(tooltipItem.yLabel);
                    }
                }
            }
        }
    });
}

/**
 * Initialize the project status pie chart
 */
function initializeProjectStatusChart() {
    var projectStatusCtx = document.getElementById('projectStatusPieChart');
    if (!projectStatusCtx) return;
    
    // Get project status data
    var pending = 0, inProgress = 0, completed = 0, onHold = 0, cancelled = 0;
    
    try {
        var statsElement = document.getElementById('project-stats-data');
        if (statsElement) {
            pending = parseInt(statsElement.getAttribute('data-pending')) || 0;
            inProgress = parseInt(statsElement.getAttribute('data-in-progress')) || 0;
            completed = parseInt(statsElement.getAttribute('data-completed')) || 0;
            onHold = parseInt(statsElement.getAttribute('data-on-hold')) || 0;
            cancelled = parseInt(statsElement.getAttribute('data-cancelled')) || 0;
        } else {
            // Default values
            pending = 3;
            inProgress = 5;
            completed = 8;
            onHold = 2;
            cancelled = 1;
        }
    } catch (e) {
        console.error('Error parsing project stats data:', e);
        // Default values on error
        pending = 3;
        inProgress = 5;
        completed = 8;
        onHold = 2;
        cancelled = 1;
    }
    
    new Chart(projectStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ["Pending", "In Progress", "Completed", "On Hold", "Cancelled"],
            datasets: [{
                data: [pending, inProgress, completed, onHold, cancelled],
                backgroundColor: ['#f6c23e', '#4e73df', '#1cc88a', '#36b9cc', '#e74a3b'],
                hoverBackgroundColor: ['#dda20a', '#2e59d9', '#17a673', '#2c9faf', '#be2617'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var total = dataset.data.reduce(function(previousValue, currentValue) {
                            return previousValue + currentValue;
                        });
                        var currentValue = dataset.data[tooltipItem.index];
                        var percentage = Math.floor(((currentValue / total) * 100) + 0.5);
                        return data.labels[tooltipItem.index] + ': ' + currentValue + ' (' + percentage + '%)';
                    }
                }
            },
            legend: {
                display: false
            },
            cutoutPercentage: 70,
        },
    });
}

/**
 * Initialize the department bar chart
 */
function initializeDepartmentChart() {
    var departmentBarCtx = document.getElementById('departmentBarChart');
    if (!departmentBarCtx) return;
    
    // Get department data
    var departmentNames = [];
    var departmentCounts = [];
    
    try {
        var deptElement = document.getElementById('department-stats-data');
        if (deptElement) {
            departmentNames = JSON.parse(deptElement.getAttribute('data-names') || '[]');
            departmentCounts = JSON.parse(deptElement.getAttribute('data-counts') || '[]');
        }
        
        // If we don't have data, use placeholders
        if (departmentNames.length === 0 || departmentCounts.length === 0) {
            departmentNames = ["HR", "IT", "Marketing", "Sales", "Finance"];
            departmentCounts = [4, 8, 6, 12, 5];
        }
    } catch (e) {
        console.error('Error parsing department data:', e);
        departmentNames = ["HR", "IT", "Marketing", "Sales", "Finance"];
        departmentCounts = [4, 8, 6, 12, 5];
    }
    
    new Chart(departmentBarCtx, {
        type: 'bar',
        data: {
            labels: departmentNames,
            datasets: [{
                label: "Employees",
                backgroundColor: "#4e73df",
                hoverBackgroundColor: "#2e59d9",
                borderColor: "#4e73df",
                data: departmentCounts,
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 10
                    },
                    maxBarThickness: 25,
                }],
                yAxes: [{
                    ticks: {
                        min: 0,
                        maxTicksLimit: 5,
                        padding: 10,
                    },
                    gridLines: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }],
            },
            legend: {
                display: false
            },
            tooltips: {
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
        }
    });
}

/**
 * Initialize the task completion line chart
 */
function initializeTaskCompletionChart() {
    var taskCtx = document.getElementById('taskCompletionLineChart');
    if (!taskCtx) return;
    
    // Generate last 6 months labels
    const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    const now = new Date();
    const currentMonth = now.getMonth();
    
    let taskLabels = [];
    for (let i = 5; i >= 0; i--) {
        const monthIndex = (currentMonth - i + 12) % 12;
        taskLabels.push(months[monthIndex]);
    }
    
    // Get task data or use random data for demo
    var taskData = [];
    try {
        var taskDataElement = document.getElementById('task-data');
        if (taskDataElement) {
            taskData = JSON.parse(taskDataElement.getAttribute('data-tasks'));
        }
        
        // If no data, use random data for demo
        if (!taskData || taskData.length === 0) {
            taskData = [15, 22, 19, 27, 30, 25];
        }
    } catch (e) {
        console.error('Error parsing task data:', e);
        taskData = [15, 22, 19, 27, 30, 25];
    }
    
    new Chart(taskCtx, {
        type: 'line',
        data: {
            labels: taskLabels,
            datasets: [{
                label: "Tasks Completed",
                lineTension: 0.3,
                backgroundColor: "rgba(28, 200, 138, 0.05)",
                borderColor: "rgba(28, 200, 138, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(28, 200, 138, 1)",
                pointBorderColor: "rgba(28, 200, 138, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(28, 200, 138, 1)",
                pointHoverBorderColor: "rgba(28, 200, 138, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: taskData,
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                xAxes: [{
                    time: {
                        unit: 'month'
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 6
                    }
                }],
                yAxes: [{
                    ticks: {
                        min: 0,
                        maxTicksLimit: 5,
                        padding: 10,
                    },
                    gridLines: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }],
            },
            legend: {
                display: false
            },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
            }
        }
    });
}

/**
 * Utility function for formatting numbers with commas
 */
function number_format(number, decimals, dec_point, thousands_sep) {
    // Default values
    decimals = decimals || 0;
    dec_point = dec_point || '.';
    thousands_sep = thousands_sep || ',';
    
    // Format number
    number = parseFloat(number);
    if (isNaN(number)) return '0';
    
    number = number.toFixed(decimals);
    
    // Split into parts
    var parts = number.split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_sep);
    
    return parts.join(dec_point);
}