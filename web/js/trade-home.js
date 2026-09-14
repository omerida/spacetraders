document.addEventListener("DOMContentLoaded", function () {
    // Convert the existing HTML table to a dynamic Tabulator table
    let table = new Tabulator("#trade-data", {
        // Tabulator automatically imports headers and data from HTML table,
        // but you can add features here:
        layout: "fitColumns", // Stretch columns to fill table width
        pagination: "local",  // Enable local pagination
        paginationSize: 30,
        columns: [
            {
                title: "Good",
                field: "good",
                // 1. Style the cell content so it looks like a clickable link/button
                formatter: function (cell, formatterParams, onRendered) {
                    var value = cell.getValue();
                    return `<a href="#" class="good-filter-link" style="color: #0066cc; text-decoration: underline; cursor: pointer;">${value}</a>`;
                },
                // 2. Handle the click event on the cell
                cellClick: function (e, cell) {
                    e.preventDefault();
                    let clickedGood = cell.getValue();
                    let currentFilters = table.getFilters();

                    // Check if we are already filtering by this good
                    let isFiltered = currentFilters.some(function (filter) {
                        return filter.field === "good" && filter.value === clickedGood;
                    });

                    if (isFiltered) {
                        table.clearFilter(); // Clear filter if clicked again
                    } else {
                        table.setFilter("good", "=", clickedGood); // Apply filter
                    }
                }
            }
        ]
    });

    // Custom filter function for Tabulator
    function typeFilterFunction(data) {
        // Collect all currently checked checkbox values
        var checkedTypes = Array.from(document.querySelectorAll('.type-filter:checked'))
            .map(cb => cb.value.toUpperCase());

        // Show row if its type is included in the checked values list
        return data.type && checkedTypes.includes(data.type.toUpperCase());
    }

    // Apply the initial filter when table loads
    table.setFilter(typeFilterFunction);

    // Re-trigger the filter whenever any checkbox changes state
    document.querySelectorAll('.type-filter').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            table.refreshFilter(); // Re-evaluates active filters
        });
    });

    document.getElementById("clear-good-filter").addEventListener("click", function () {
        table.clearFilter(); // Removes all active filters
    });

});