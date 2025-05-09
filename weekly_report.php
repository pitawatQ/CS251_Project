<?php
session_start();
include 'backend/db_connect.php';
include 'backend/auth.php';  // ตรวจสอบ session
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

<div class="container">
    <div class="report-box">
        <p>📈 ภาพรวมรายได้รายสัปดาห์</p>
        <canvas id="weeklyChart"></canvas>
    </div>
</div>

<div class="profile-box">
    <img src="img/picture/Profile_guy.png" alt="Profile Picture">
    <div class="profile-info">
        <p class="profile-name"><?= $_SESSION['FName'] ?? 'ไม่ทราบชื่อ' ?></p>
        <p class="profile-id">ID: <?= $_SESSION['EmployeeID'] ?? '??' ?></p>
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
