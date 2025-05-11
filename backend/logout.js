function confirmLogout() {
  if (confirm("คุณแน่ใจหรือไม่ว่าต้องการออกจากระบบ?")) {
    window.location.href = 'logout.php';
  }
}
