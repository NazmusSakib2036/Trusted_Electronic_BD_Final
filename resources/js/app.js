import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

// Make Alpine.js and Chart.js available globally
window.Alpine = Alpine;
window.Chart = Chart;

// Start Alpine.js
Alpine.start();

// Admin Dashboard functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize any charts or components when the page loads
    if (window.initDashboard) {
        window.initDashboard();
    }
});
