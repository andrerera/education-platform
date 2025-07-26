const CACHE_NAME = "Kyulearn-v1";
const urlToCache = [
    './icons/android-chrome-16x16.png',
    './icons/android-chrome-32x32.png',
    './icons/android-chrome-48x48.png',
    './icons/android-chrome-180x180.png',
    './icons/android-chrome-192x192.png',
    './icons/android-chrome-512x512.png',
    './icons/apple-touch-icon.png',
    './icons/favicon-16x16.png',
    './icons/favicon-32x32.png',
    './icons/favicon.ico',
    './icons/logo-circle.png',
    './assets/offline-illustration.json',
    './offline.html'
];

const libraryCache = [
  'https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js',
];

urlToCache.push(...libraryCache);

//Install Service Worker
self.addEventListener("install", function(event) {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function(cache) {
          return cache.addAll(urlToCache);
      })
  )
});

self.addEventListener('fetch', (event) => {
  event.respondWith(
    fetch(event.request).catch(() => {
      return caches.match(event.request)
      .then((response) => {
        if (response) {
          return response;
        } else if (event.request.headers.get('accept').includes('text/html')) {
          return caches.match('./offline.html');
        }
      });
    })
  );
});

//Activate Service Worker
self.addEventListener("activate", event => {
  event.waitUntil(
      caches.keys().then(cacheNames => {
        return Promise.all(
          cacheNames.map(cacheName => {
            if (cacheName != CACHE_NAME) {
              console.log(`ServiceWorker: cache ${cacheName} deleted`);
              return caches.delete(cacheName);
            }
          })
        );
      })
    );
});