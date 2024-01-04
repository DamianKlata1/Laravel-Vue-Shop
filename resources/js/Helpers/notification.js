import Swal from 'sweetalert2';
import { usePage } from '@inertiajs/vue3';

export function displayNotification(typeOfNotification) {
    Swal.fire({
        toast: true,
        icon: typeOfNotification,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        title: usePage().props.flash[typeOfNotification]
    });
}

export function displayAllNotifications(page) {
    if (!page) return;
    for(const [type, message] of Object.entries(page.props.flash)){
        if(message) {
            displayNotification(type)
        };
    }
}
