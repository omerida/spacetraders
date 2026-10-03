document.addEventListener("DOMContentLoaded", function () {
    // Custom filter function for Tabulator checkboxes
    function typeFilterFunction(data) {
        let checkedBoxes = document.querySelectorAll('.type-filter:checked');

        // If no checkboxes exist or none are checked, display all rows by default
        if (checkedBoxes.length === 0) {
            return true;
        }

        let checkedTypes = Array.from(checkedBoxes).map(cb => cb.value.toUpperCase());
        return data.type && checkedTypes.includes(data.type.toUpperCase());
    }

    let table = new Tabulator("#trade-data", {
        ajaxURL: "/trade/prices",
        ajaxParams: { mode: "latest" },
        layout: "fitColumns",
        pagination: "local",
        paginationSize: 30,
        columns: [
            {
                title: "Good",
                field: "symbol",
                formatter: function (cell) {
                    var value = cell.getValue();
                    return `<a href="#" class="good-filter-link" style="color: #0066cc; text-decoration: underline; cursor: pointer;">${value}</a>`;
                },
                cellClick: function (e, cell) {
                    e.preventDefault();
                    let clickedGood = cell.getValue();

                    let currentFilters = table.getFilters();
                    let symbolFilter = currentFilters.find(f => f.field === "symbol");

                    if (symbolFilter && symbolFilter.value === clickedGood) {
                        table.removeFilter("symbol", "=", clickedGood);
                    } else {
                        table.setFilter([
                            ...currentFilters.filter(f => f.field !== "symbol"),
                            { field: "symbol", type: "=", value: clickedGood }
                        ]);
                    }
                }
            },
            { title: "Type", field: "type" },
            { title: "Supply", field: "supply" },
            { title: "Activity", field: "activity" },
            { title: "Volume", field: "tradeVolume" },
            { title: "Buy Price", field: "purchasePrice" },
            { title: "Sell Price", field: "sellPrice" },
            { title: "Market", field: "waypointSymbol.waypoint" },
            {
                title: "Timestamp",
                field: "timestamp",
                formatter: function (cell) {
                    let value = cell.getValue();
                    if (!value) return "";
                    if (typeof value === "object" && value.date) {
                        value = value.date;
                    }
                    const date = new Date(value.replace(' ', 'T'));
                    return new Intl.DateTimeFormat('en-GB', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    }).format(date).toUpperCase();
                },
            },
        ]
    });

    // Apply filters and bind listeners AFTER table built and initial ajax data is loaded
    table.on("dataLoaded", function () {
        // Apply type filter only if user has actively checked any type filter boxes
        if (document.querySelectorAll('.type-filter:checked').length > 0) {
            table.setFilter(typeFilterFunction);
        }
    });

    table.on("tableBuilt", function () {
        document.querySelectorAll('.type-filter').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                // Apply or refresh the filter when checkboxes are toggled
                table.setFilter(typeFilterFunction);
            });
        });

        const clearBtn = document.getElementById("clear-good-filter");
        if (clearBtn) {
            clearBtn.addEventListener("click", function () {
                table.removeFilter("symbol", "=");
            });
        }

        const latestToggle = document.getElementById('latest-prices-toggle');
        if (latestToggle) {
            latestToggle.addEventListener('change', (e) => {
                table.setData(
                    "/trade/prices",
                    { mode: e.target.checked ? "latest" : "all" }
                );
            });
        }
    });
});