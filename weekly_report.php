<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Weekly Report</title>
    <link rel="stylesheet" type="text/css" href="css/weekly_report.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js -->
</head>
<body>

<div class="container">
    <div class="report-box">
        <p>📈 ภาพรวมรายได้รายสัปดาห์</p>
        <canvas id="weeklyChart"></canvas> <!-- Canvas for the bar chart -->
    </div>
</div>

<div class="profile-box">
    <img src="img/picture/Profile_guy.png" alt="Profile Picture">
    <div class="profile-info">
        <p class="profile-name">ธีธัต</p>
        <p class="profile-id">ID: SC-316</p>
    </div>
</div>
<div class="home-button" onclick="location.href='index.php'">
    <img src="img/picture/Home_icon.png" alt="Home Icon">
    <p>หน้าหลัก</p>
</div>
<div class="exit-button" onclick="location.href='login.php'">
    <img src="img/picture/Exit_door.png" alt="Exit">
</div>

<script>
    // JavaScript to render the bar chart
    const ctx = document.getElementById('weeklyChart').getContext('2d');
    const weeklyChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['จันทร์', 'อังคาร', 'พุธ', 'พฤหัส', 'ศุกร์', 'เสาร์', 'อาทิตย์'], // Days of the week
            datasets: [{
                label: 'รายได้ (บาท)',
                data: [7120, 8500, 9200, 8700, 9400, 12000, 8900], // Example data
                backgroundColor: '#28c95b', // Bar color
                borderColor: '#1e8f47', // Border color
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true, // Ensure the chart respects the container's aspect ratio
            layout: {
                padding: {
                    top: 10, // Add padding to prevent labels from overlapping
                    bottom: 50
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 3500 // Adjust step size for better readability
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `รายได้: ฿${context.raw}`; // Custom tooltip format
                        }
                    }
                }
            }
        }
    });
</script>

</body>
</html>