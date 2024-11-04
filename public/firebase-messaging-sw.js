// firebase-messaging-sw.js

// Import Firebase scripts for Service Workers (non-module)
importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js');

// Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyBsv1XYf2bVeZE-wMTk2OVxZlp9ayTLwDg",
    authDomain: "nithitex-8c776.firebaseapp.com",
    projectId: "nithitex-8c776",
    storageBucket: "nithitex-8c776.appspot.com",
    messagingSenderId: "41860351611",
    appId: "1:41860351611:web:658657ff55604843bf04de",
    measurementId: "G-06RSSGHG6F"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// Retrieve an instance of Firebase Messaging
const messaging = firebase.messaging();

// Background message handling
messaging.onBackgroundMessage((payload) => {
    console.log('Received background message ', payload);
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: payload.notification.image,
        data: { url: payload.notification.click_action }
    };

    // Show notification
    self.registration.showNotification(notificationTitle, notificationOptions);
});

// Notification click event
self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    if (event.notification.data && event.notification.data.url) {
        event.waitUntil(
            clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
                for (let client of clientList) {
                    if (client.url === event.notification.data.url && 'focus' in client) {
                        return client.focus();
                    }
                }
                if (clients.openWindow) {
                    return clients.openWindow(event.notification.data.url);
                }
            })
        );
    }
});
