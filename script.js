function toggleTable(sectionId) {
             
    const allTables = document.querySelectorAll('.tables-section .container');
    allTables.forEach(table => table.classList.add('hidden'));

    
    const selectedTable = document.getElementById(sectionId);
    selectedTable.classList.remove('hidden');
}

 
async function fetchData(endpoint, tableBodyId) {
    try {
        const response = await fetch(endpoint);
        const data = await response.json();
        const tableBody = document.getElementById(tableBodyId);

        data.forEach(row => {
            const tableRow = document.createElement('tr');

            Object.values(row).forEach(cellData => {
                const cell = document.createElement('td');
                cell.textContent = cellData;
                tableRow.appendChild(cell);
            });

            tableBody.appendChild(tableRow);
        });
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

 
document.addEventListener('DOMContentLoaded', () => {
    fetchData('?action=get_grading_criteria', 'grading-criteria-body');
     
});

function toggleTable(sectionId) {
    const allTables = document.querySelectorAll('.content .container');
    allTables.forEach(table => table.classList.add('hidden'));

    const selectedTable = document.getElementById(sectionId);
    selectedTable.classList.remove('hidden');
}

function toggleTable(tableId) {
    var table = document.getElementById(tableId);
    if (table.style.display === 'none') {
        table.style.display = 'block';
    } else {
        table.style.display = 'none';
    }
}


function navigateToSection(sectionId) {
    // Map sectionId to demo URLs
    const demoUrls = {
        'government-office': 'demo-government-office.html',
        'agricultural-officer': 'demo-agricultural-officer.html',
        'packaged-batch': 'packagedbatch.php',
        'warehouse': 'demo-warehouse.html',
        'market-place': 'demo-market-place.html'
    };

    // Navigate to the corresponding demo page or toggle the section
    if (demoUrls[sectionId]) {
        window.location.href = demoUrls[sectionId];
    } else {
        console.error('Invalid section ID or demo URL missing.');
    }
}