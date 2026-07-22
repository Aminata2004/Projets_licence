// Service worker minimal : cache uniquement les fichiers statiques (CSS/JS/images).
// Les pages PHP dynamiques (réservations, connexion, AJAX...) passent toujours par le réseau.
const CACHE_NAME = 'transgest-shell-v1';

self.addEventListener('install', (event) => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => cache.addAll(['./manifest.json']))
            .catch(() => {})
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((keys) => Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k))))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    const req = event.request;

    // Ne jamais intercepter les requêtes non-GET (formulaires, réservations, paiements...)
    if (req.method !== 'GET') {
        return;
    }

    const url = new URL(req.url);
    const isStaticAsset = /\.(css|js|png|jpe?g|svg|webp|gif|woff2?|ttf|ico)$/i.test(url.pathname);

    if (!isStaticAsset) {
        // Pages PHP / routes dynamiques : toujours réseau, jamais de cache
        return;
    }

    event.respondWith(
        caches.open(CACHE_NAME).then((cache) =>
            cache.match(req).then((cached) => {
                const network = fetch(req)
                    .then((res) => {
                        if (res && res.ok) {
                            cache.put(req, res.clone());
                        }
                        return res;
                    })
                    .catch(() => cached);
                return cached || network;
            })
        )
    );
});
