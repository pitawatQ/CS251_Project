<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>จัดการสถานะโต๊ะ</title>
  <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/table_status.css">
</head>
<body>
  <div class="top-bar">
    <div class="home-button">
      <img src="pics/Home_icon.png" alt="home">
      <span>หน้าหลัก</span>
    </div>
    <div class="profile-box">
      <img src="pics/male.png" alt="profile">
      <div class="profile-info">
        <div class="profile-name">ปราชญ์</div>
        <div class="profile-id">ID: ST-143</div>
      </div>
    </div>
  </div>

  <div class="main-container">
    <h2>🍽️ จัดการสถานะโต๊ะ</h2>

    <div class="table-grid">
      <?php
        $tables = [
          ['id' => 1, 'status' => 'ว่าง', 'people' => 4],
          ['id' => 2, 'status' => 'จองไว้', 'people' => 2],
          ['id' => 3, 'status' => 'กำลังใช้งาน', 'people' => 2, 'time' => '12:00'],
          ['id' => 4, 'status' => 'ว่าง'],
          ['id' => 5, 'status' => 'กำลังใช้งาน', 'people' => 3, 'time' => '12:15'],
          ['id' => 6, 'status' => 'ว่าง'],
          ['id' => 7, 'status' => 'รอเช็กบิล', 'people' => 2],
          ['id' => 8, 'status' => 'กำลังใช้งาน', 'people' => 2, 'time' => '12:25'],
        ];
        foreach ($tables as $table) {
          $statusClass = match ($table['status']) {
            'ว่าง' => 'green',
            'จองไว้' => 'orange',
            'กำลังใช้งาน' => 'red',
            'รอเช็กบิล' => 'blue',
            default => 'gray',
          };
          echo "<div class='table-box {$statusClass}'>";
          echo "<div class='table-title'>โต๊ะ {$table['id']}</div>";
          if (isset($table['people'])) {
            echo "<div class='people-time'>";
            echo "<div class='people'>🧍‍♂️ {$table['people']} คน</div>";
            if (isset($table['time'])) echo "<div class='time'>🕐 {$table['time']}</div>";
            echo "</div>";
            echo "<div class='staff-name'>ดูแล: ปราชญ์</div>";
          }
          echo "<div class='status-line'>สถานะ: {$table['status']}</div>";
          echo "<select class='status-dropdown'>
                  <option selected disabled>อัปเดตสถานะ</option>
                  <option>ว่าง</option>
                  <option>จองไว้</option>
                  <option>กำลังใช้งาน</option>
                  <option>รอเช็กบิล</option>
                </select>";
          echo "</div>";
        }
      ?>
    </div>
  </div>

  <div class="exit-button">
    <img src="pics/Exit_door.png" alt="exit">
  </div>
</body>
</html>
