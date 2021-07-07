require('./bootstrap');

import { createApp } from 'vue'

// import DatePicker from './components/DatePicker.vue'
import Flash from './components/Flash.vue'
import ImagePicker from './components/ImagePicker.vue'
import Modal from './components/Modal.vue'
import Sidebar from './components/Sidebar.vue'
createApp({
    components: {
        Flash, ImagePicker, Modal, Sidebar,
    },
    data() {
        return {
            showModal: false,
            showSidebar: false,
        };
    },
}).mount('#app');

