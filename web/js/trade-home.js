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
                    // Prevent default anchor tag navigation
                    e.preventDefault();

                    var clickedGood = cell.getValue();

                    // Set the filter on the "good" column to match the clicked value
                    table.setFilter("good", "=", clickedGood);
                }
            }
        ]
    });

    document.getElementById("clear-good-filter").addEventListener("click", function() {
        table.clearFilter(); // Removes all active filters
    });

});