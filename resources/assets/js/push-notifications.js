import Pusher from 'pusher-websocket-iso';

const bindPusher = () => {
    if (IS_DJ) {
        const pusher = new Pusher(PUSHER_APP_KEY, {
            authEndpoint: PUSHER_ENDPOINT,
            encrypted: true,
            cluster: PUSHER_CLUSTER,
            auth: {
                headers: {
                    'X-CSRF-Token': CSRF_TOKEN
                }
            }
        });

        const channel = pusher.subscribe('private-dj');
        channel.bind('request', (data) => {
            new Notification('New Request', {
                body: `${data.msg} - ${data.sender}`
            });
        });
    }
};

if ("Notification" in window) {
    if (Notification.permission !== 'granted') {
        Notification.requestPermission().then((result) => {
            if (result === 'granted') {
                bindPusher();
            }
        });
    } else {
        bindPusher();
    }
} else {
    alert('This browser doesn\'t support notifications. Please upgrade!');
}
