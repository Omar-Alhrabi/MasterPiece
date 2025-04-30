// Wait for document to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Helper function for formatting numbers
    function number_format(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    // Chart.js default configuration to reduce duplicate code
    if (typeof Chart !== 'undefined') {
        Chart.defaults.global.tooltips.backgroundColor = "rgb(255,255,255)";
        Chart.defaults.global.tooltips.bodyFontColor = "#858796";
        Chart.defaults.global.tooltips.titleMarginBottom = 10;
        Chart.defaults.global.tooltips.titleFontColor = '#6e707e';
        Chart.defaults.global.tooltips.titleFontSize = 14;
        Chart.defaults.global.tooltips.borderColor = '#dddfeb';
        Chart.defaults.global.tooltips.borderWidth = 1;
        Chart.defaults.global.tooltips.xPadding = 15;
        Chart.defaults.global.tooltips.yPadding = 15;
        Chart.defaults.global.tooltips.displayColors = false;
        Chart.defaults.global.tooltips.caretPadding = 10;
        Chart.defaults.global.legend.display = false;
        Chart.defaults.global.maintainAspectRatio = false;
    }

    // Check for admin user and initialize admin charts
    const isAdmin = document.body.classList.contains('admin-dashboard');
    if (isAdmin && typeof Chart !== 'undefined') {
        // Only initialize charts if elements exist
        const earningsAreaChart = document.getElementById('earningsAreaChart');
        if (earningsAreaChart) {
            initEarningsChart(earningsAreaChart);
        }

        const projectStatusPieChart = document.getElementById('projectStatusPieChart');
        if (projectStatusPieChart) {
            initProjectStatusChart(projectStatusPieChart);
        }

        const departmentBarChart = document.getElementById('departmentBarChart');
        if (departmentBarChart) {
            initDepartmentChart(departmentBarChart);
        }

        const taskCompletionLineChart = document.getElementById('taskCompletionLineChart');
        if (taskCompletionLineChart) {
            initTaskCompletionChart(taskCompletionLineChart);
        }
    }

    // Initialize progress bars for employee projects
    initProjectProgressBars();
    
    // Todo list functionality - Common for both admin and employee
    initTodoFunctionality();

    // Function to initialize project progress bars
    function initProjectProgressBars() {
        const progressBars = document.querySelectorAll('[id^="projectProgress"]');
        progressBars.forEach(function(bar) {
            const progress = parseInt(bar.style.width);
            
            // Set color based on progress
            if (progress < 30) {
                bar.classList.add('bg-danger');
            } else if (progress < 60) {
                bar.classList.add('bg-warning');
            } else if (progress < 90) {
                bar.classList.add('bg-info');
            } else {
                bar.classList.add('bg-success');
            }
        });
    }

    // Todo list functionality
    function initTodoFunctionality() {
        const taskCheckboxes = document.querySelectorAll('.task-checkbox');
        const deleteTaskButtons = document.querySelectorAll('.delete-task');
        
        // Add change event for task checkboxes
        taskCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const taskId = this.dataset.taskId;
                const todoItem = this.closest('.todo-item');
                
                if (this.checked) {
                    // Send AJAX request to update task status
                    updateTaskStatus(taskId, todoItem);
                }
            });
        });
        
        // Add click event for delete buttons
        deleteTaskButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const taskId = this.dataset.taskId;
                const todoItem = this.closest('.todo-item');
                
                if (confirm('Are you sure you want to delete this task?')) {
                    // Send AJAX request to delete task
                    deleteTask(taskId, todoItem);
                }
            });
        });
    }
    
    // Function to update task status via AJAX
    function updateTaskStatus(taskId, todoItem) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('/tasks/' + taskId, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: 'Completed' })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Fade out and remove todo item
            fadeOutElement(todoItem);
            
            // Update task counters if they exist
            updateTaskCounters();
        })
        .catch(error => {
            console.error('Error updating task:', error);
            // Uncheck checkbox on error
            const checkbox = todoItem.querySelector('.task-checkbox');
            if (checkbox) checkbox.checked = false;
        });
    }
    
    // Function to delete task via AJAX
    function deleteTask(taskId, todoItem) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('/tasks/' + taskId, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Fade out and remove todo item
            fadeOutElement(todoItem);
            
            // Update task counters if they exist
            updateTaskCounters();
        })
        .catch(error => {
            console.error('Error deleting task:', error);
            alert('Error deleting task. Please try again.');
        });
    }
    
    // Helper function to fade out element
    function fadeOutElement(element) {
        element.style.transition = 'opacity 0.5s';
        element.style.opacity = '0';
        
        setTimeout(() => {
            element.style.display = 'none';
            if (element.parentNode) {
                element.parentNode.removeChild(element);
            }
        }, 500);
    }
    
    // Update task counters when tasks change
    function updateTaskCounters() {
        // This function can be implemented to update task count displays
        // after completing or deleting a task
        const pendingCountElement = document.querySelector('.pending-task-count');
        const completedCountElement = document.querySelector('.completed-task-count');
        
        if (pendingCountElement) {
            const currentCount = parseInt(pendingCountElement.textContent);
            if (!isNaN(currentCount)) {
                pendingCountElement.textContent = currentCount - 1;
            }
        }
        
        if (completedCountElement) {
            const currentCount = parseInt(completedCountElement.textContent);
            if (!isNaN(currentCount)) {
                completedCountElement.textContent = currentCount + 1;
            }
        }
    }
}); // Earnings Chart for Admin
var earningsChart = document.getElementById('earningsAreaChart');
if (earningsChart) {
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
                    data: {!! json_encode($earningsData ?? [0, 10000, 5000, 15000, 10000, 20000, 15000, 25000, 20000, 30000, 25000, 40000]) !!},
                }],
            },
            options: {
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
                            unit: 'date'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
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
                tooltips: {
                    intersect: false,
                    mode: 'index',
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            return 'Earnings: $' + number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });
    }

    // Function to initialize Project Status Chart for Admin
    function initProjectStatusChart(chartElement) {
        // Get project stats from data attributes if available
        const dataContainer = document.getElementById('project-stats-data');
        let projectStats = {};
        
        if (dataContainer) {
            projectStats = {
                pending: parseInt(dataContainer.dataset.pending || 0),
                inProgress: parseInt(dataContainer.dataset.inProgress || 0),
                completed: parseInt(dataContainer.dataset.completed || 0),
                onHold: parseInt(dataContainer.dataset.onHold || 0),
                cancelled: parseInt(dataContainer.dataset.cancelled || 0)
            };
        }
        
        const ctx = chartElement.getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ["Pending", "In Progress", "Completed", "On Hold", "Cancelled"],
                datasets: [{
                    data: [
                        projectStats.pending || 3,
                        projectStats.inProgress || 5,
                        projectStats.completed || 8,
                        projectStats.onHold || 2,
                        projectStats.cancelled || 1
                    ],
                    backgroundColor: ['#f6c23e', '#4e73df', '#1cc88a', '#36b9cc', '#e74a3b'],
                    hoverBackgroundColor: ['#dda20a', '#2e59d9', '#17a673', '#2c9faf', '#be2617'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                cutoutPercentage: 80,
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            const label = data.labels[tooltipItem.index];
                            const value = data.datasets[0].data[tooltipItem.index];
                            const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        });
    }

    // Function to initialize Department Chart for Admin
    function initDepartmentChart(chartElement) {
        const ctx = chartElement.getContext('2d');
        
        // Get department data from the data attributes if available
        const dataContainer = document.getElementById('department-stats-data');
        let departmentNames = [];
        let departmentCounts = [];
        
        if (dataContainer) {
            try {
                departmentNames = JSON.parse(dataContainer.dataset.names || '[]');
                departmentCounts = JSON.parse(dataContainer.dataset.counts || '[]');
            } catch (e) {
                console.error('Error parsing department data:', e);
                // Use fallback data
                departmentNames = ["HR", "IT", "Marketing", "Finance", "Operations"];
                departmentCounts = [4, 12, 8, 6, 10];
            }
        } else {
            // Fallback data if not available
            departmentNames = ["HR", "IT", "Marketing", "Finance", "Operations"];
            departmentCounts = [4, 12, 8, 6, 10];
        }

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: departmentNames,
                datasets: [{
                    label: "Staff Count",
                    backgroundColor: "#4e73df",
                    hoverBackgroundColor: "#2e59d9",
                    borderColor: "#4e73df",
                    data: departmentCounts,
                }],
            },
            options: {
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 6
                        },
                        maxBarThickness: 25,
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            max: Math.max(...departmentCounts) + 2,
                            maxTicksLimit: 5,
                            padding: 10,
                            callback: function(value) {
                                return value;
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
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            return 'Staff: ' + tooltipItem.yLabel;
                        }
                    }
                }
            }
        });
    }

    // Function to initialize Task Completion Chart for Admin
    function initTaskCompletionChart(chartElement) {
        const ctx = chartElement.getContext('2d');
        
        // Generate last 6 months labels
        const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        const now = new Date();
        const currentMonth = now.getMonth();
        
        let taskLabels = [];
        for (let i = 5; i >= 0; i--) {
            const monthIndex = (currentMonth - i + 12) % 12;
            taskLabels.push(months[monthIndex]);
        }
        
        // Get task data from data attributes if available
        const dataContainer = document.getElementById('task-completion-data');
        let taskData = [];
        
        if (dataContainer) {
            try {
                taskData = JSON.parse(dataContainer.dataset.values || '[]');
            } catch (e) {
                console.error('Error parsing task completion data:', e);
                // Use random data as fallback
                for (let i = 0; i < 6; i++) {
                    taskData.push(Math.floor(Math.random() * 40) + 10);
                }
            }
        } else {
            // Generate random data if not available
            for (let i = 0; i < 6; i++) {
                taskData.push(Math.floor(Math.random() * 40) + 10);
            }
        }
        
        new Chart(ctx, {
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
                scales: {
                    xAxes: [{
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
                            callback: function(value) {
                                return value;
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
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            return tooltipItem.yLabel + ' tasks completed';
                        }
                    }
                }
            }
        });
    }
