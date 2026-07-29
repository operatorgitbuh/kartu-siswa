import './bootstrap';
import Swal from 'sweetalert2';
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);

window.Alpine = Alpine;
window.Swal = Swal;
Alpine.start();