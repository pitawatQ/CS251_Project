document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('.search-input');

    function filterTable() {
        const search = searchInput.value.toLowerCase();

        document.querySelectorAll('tbody tr').forEach(tr => {
            const rowText = tr.innerText.toLowerCase();
            tr.style.display = rowText.includes(search) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterTable);
});
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.querySelector('.search-input');
  const table = document.querySelector('table');
  const tbody = table.querySelector('tbody');

  // ค้นหา
  searchInput.addEventListener('input', () => {
    const keyword = searchInput.value.toLowerCase();
    document.querySelectorAll('tbody tr').forEach(tr => {
      const text = tr.innerText.toLowerCase();
      tr.style.display = text.includes(keyword) ? '' : 'none';
    });
  });

  const originalRows = Array.from(tbody.querySelectorAll('tr')); // เก็บลำดับเริ่มต้น

  // การเรียง
  document.querySelectorAll('.sortable').forEach(header => {
    let clickCount = 0;

    header.addEventListener('click', () => {
      const index = Array.from(header.parentNode.children).indexOf(header);

      // รีเซต class ทุก column
      document.querySelectorAll('.sortable').forEach(h => h.classList.remove('asc', 'desc'));

      clickCount = (clickCount + 1) % 3;

      if (clickCount === 0) {
        // กลับค่า default
        originalRows.forEach(row => tbody.appendChild(row));
      } else {
        const isAsc = clickCount === 1;
        const rows = Array.from(tbody.querySelectorAll('tr'));

        rows.sort((a, b) => {
          const aText = a.children[index].textContent.trim();
          const bText = b.children[index].textContent.trim();
          const aDate = new Date(aText);
          const bDate = new Date(bText);
          return isAsc ? aDate - bDate : bDate - aDate;
        });

        header.classList.add(isAsc ? 'asc' : 'desc');
        rows.forEach(row => tbody.appendChild(row));
      }
    });
  });
});

