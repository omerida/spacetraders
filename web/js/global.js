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

up.compiler('.jettison-btn', function(button) {
  button.addEventListener('click', () => {
    // Read the dynamic dataset values from the button
    const { good, units, symbol } = button.dataset;

    // Grab template content
    const template = document.querySelector('#jettison-template');
    if (!template) return;

    // Replace placeholders with real values
    let modalHTML = template.innerHTML
        .replace(/\${good}/g, good)
        .replace(/\${units}/g, units)
        .replace(/\${symbol}/g, symbol);

    // Open Unpoly modal with rendered HTML
    up.layer.open({
      mode: 'modal',
      content: modalHTML
    });
  });
});