import './bootstrap';
import Alpine from 'alpinejs';
import 'flowbite';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

window.Alpine = Alpine;
Alpine.start();

// Make Chart.js available globally
window.Chart = Chart;
