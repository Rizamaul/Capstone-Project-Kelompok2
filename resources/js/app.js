import Alpine from 'alpinejs';
import flatpickr from 'flatpickr';
import { Indonesian } from 'flatpickr/dist/l10n/id.js';

window.Alpine = Alpine;

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-datepicker="date"]').forEach((el) => {
        flatpickr(el, {
            locale: Indonesian,
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            altInputClass: el.className,
            maxDate: el.getAttribute('max') || null,
            allowInput: true,
        });
    });

    document.querySelectorAll('[data-datepicker="datetime"]').forEach((el) => {
        flatpickr(el, {
            locale: Indonesian,
            enableTime: true,
            time_24hr: true,
            dateFormat: 'Y-m-d\\TH:i',
            altInput: true,
            altFormat: 'd/m/Y H:i',
            altInputClass: el.className,
            minDate: el.getAttribute('min') || null,
            allowInput: true,
        });
    });
});

Alpine.start();
