<?php
session_start();
include 'backend/db_connect.php';
include 'backend/auth.php';

if (!isset($_SESSION['EmployeeID'])) {
    header("Location: login.php");
    exit();
}

$employeeID = $_SESSION['EmployeeID'];

$stmt = $conn->prepare("SELECT FName, EmployeeID FROM Employee WHERE EmployeeID = ?");
$stmt->bind_param("i", $employeeID);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Weekly Report</title>
    <link rel="stylesheet" type="text/css" href="css/weekly_report.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

  <div class="top-bar">
    <div class="home-button" onclick="location.href='admin_dashboard.php'">
      <img src="pics/Home_icon.png">
      <p>หน้าหลัก</p>
    </div>
    <div class="profile-box">
      <img src="img/picture/Profile_guy.png" alt="Profile Picture">
      <div class="profile-label">
        <p class="profile-name"><?php echo htmlspecialchars($profile['FName']); ?></p>
        <p class="profile-id">ID: <?php echo htmlspecialchars($profile['EmployeeID']); ?></p>
      </div>
    </div>
  </div>
<div class="container">
    <div class="report-box">
        <p>📈 ภาพรวมรายได้รายสัปดาห์</p>
        <canvas id="weeklyChart"></canvas>
    </div>
</div>


<script>
fetch('backend/get_weekly_sales.php')
    .then(res => res.json())
    .then(data => {
        const ctx = document.getElementById('weeklyChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['จันทร์', 'อังคาร', 'พุธ', 'พฤหัส', 'ศุกร์', 'เสาร์', 'อาทิตย์'],
                datasets: [{
                    label: 'รายได้ (บาท)',
                    data: data,
                    backgroundColor: '#28c95b',
                    borderColor: '#1e8f47',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                layout: {
                    padding: { top: 10, bottom: 50 }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 3000 }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: context => `รายได้: ฿${context.raw}`
                        }
                    }
                }
            }
        });
    });
</script>

</body>
</html>
