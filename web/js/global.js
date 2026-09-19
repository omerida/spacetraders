/* Makes cells with a countdown timer tick down. */
up.compiler('table.ship-cooldown', function(table) {
    const countdownCell = table.querySelector('td[data-countdown="true"]');
    if (!countdownCell) {
      return;
    }

    const countdownInterval = setInterval(() => {
        let currentValue = parseInt(countdownCell.textContent, 10);

        if (isNaN(currentValue) || currentValue <= 0) {
            countdownCell.textContent = '0';
            clearInterval(countdownInterval);
        } else {
            currentValue--;
            countdownCell.textContent = currentValue;
        }
    }, 1000);

    // Clean up timer if Unpoly swaps/removes this table element
    return function() {
        clearInterval(countdownInterval);
    };
});
