document.addEventListener('DOMContentLoaded', function () {
    let currentAlertIndex = 0;

    function showNextAlert() {
        const alertText = document.getElementById('alert-text');
        if (!alerts || alerts.length === 0) {
            alertText.textContent = "✅ ไม่มีรายการใกล้หมด";
            return;
        }

        alertText.textContent = alerts[currentAlertIndex];
        currentAlertIndex = (currentAlertIndex + 1) % alerts.length;
    }

    // เริ่มต้นแสดงแจ้งเตือนแรก
    showNextAlert();

    // ปุ่มถัดไป
    const nextBtn = document.getElementById('next-alert-btn');
    if (nextBtn) {
        nextBtn.addEventListener('click', showNextAlert);
    }
});
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('.search-input');
    const filterSelect = document.querySelector('.status-filter');

    function filterTable() {
        const search = searchInput.value.toLowerCase();
        const filter = filterSelect.value;

        document.querySelectorAll('tbody tr').forEach(tr => {
            const rowText = tr.innerText.toLowerCase();
            const status = tr.querySelector('.status')?.textContent.trim();

            const matchesSearch = rowText.includes(search);
            const matchesStatus = filter === "ทั้งหมด" || filter === status;

            tr.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
    filterSelect.addEventListener('change', filterTable);
});
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.action-btn.view').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('popup').style.display = 'flex';

            document.getElementById('popup-id').textContent = this.dataset.id || '-';
            document.getElementById('popup-name').textContent = this.dataset.name || '-';
            document.getElementById('popup-qty').textContent = this.dataset.qty + ' ' + this.dataset.unit;
            document.getElementById('popup-expire').textContent = this.dataset.expire;
            document.getElementById('popup-import').textContent = this.dataset.import;
            document.getElementById('popup-update').textContent = this.dataset.update || '-';

            // ถ้าเมนูใช้ในหลายรายการ ให้แยกเป็น list
            const menus = this.dataset.menu ? this.dataset.menu.split(',') : [];
            const menuList = document.getElementById('popup-menu');
            menuList.innerHTML = '';
            menus.forEach(item => {
                const li = document.createElement('li');
                li.textContent = item.trim();
                menuList.appendChild(li);
            });

            document.getElementById('popup-by').textContent = this.dataset.by || '-';
            document.getElementById('popup-note').textContent = this.dataset.note || '-';
        });
    });
});
