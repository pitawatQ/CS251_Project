document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('.search-input');
    const filterSelect = document.querySelector('.status-filter');

    function filterEmployees() {
        const search = searchInput.value.toLowerCase();
        const filter = filterSelect.value.toLowerCase();

        document.querySelectorAll('.employee-box').forEach(box => {
            const nameText = box.querySelector('.employee-name')?.textContent.toLowerCase() || '';
            const idText = box.querySelector('.employee-details')?.textContent.toLowerCase() || '';
            const role = box.querySelector('.badge')?.textContent.toLowerCase() || '';

            const matchesSearch = nameText.includes(search) || idText.includes(search);
            const matchesFilter = filter === 'ทั้งหมด' || role === filter;

            box.style.display = (matchesSearch && matchesFilter) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterEmployees);
    filterSelect.addEventListener('change', filterEmployees);
});
