/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';

import { saveChat } from './save-chat';
import { multiSelect } from './multi-select';

document.addEventListener('alpine:init', () => {
    Alpine.data('saveChat', saveChat);
    Alpine.data('multiSelect', multiSelect);
});
