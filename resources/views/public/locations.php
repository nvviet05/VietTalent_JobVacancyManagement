<section class="section">
    <div class="container">
        <header style="margin-bottom: 1.5rem;">
            <h1>Our Locations</h1>
            <p>Visit one of our offices or partner hubs across Vietnam. Click any card to open it in Google Maps.</p>
        </header>

        <?php if (empty($locations)): ?>
            <div class="card" style="padding: 2rem; text-align: center;">
                <p>No store locations are configured yet.</p>
            </div>
        <?php else: ?>
            <div class="locations-grid">
                <?php foreach ($locations as $loc): ?>
                    <?php
                        // Build a privacy-friendly Google Maps embed (no API key required).
                        $query = $loc['map_query'] ?: ($loc['address'] . ', ' . $loc['city_name']);
                        $embedSrc = 'https://www.google.com/maps?q=' . rawurlencode($query) . '&output=embed';
                        $openSrc  = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($query);
                    ?>
                    <article class="location-card card"
                             itemscope
                             itemtype="https://schema.org/LocalBusiness">
                        <h2 itemprop="name"><?= e($loc['name']) ?></h2>
                        <div class="location-map" style="aspect-ratio: 16/10; border-radius: 8px; overflow: hidden; margin: 0.6rem 0;">
                            <iframe
                                src="<?= e($embedSrc) ?>"
                                width="100%" height="100%"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                title="Map for <?= e($loc['name']) ?>"></iframe>
                        </div>
                        <p itemprop="address" itemscope itemtype="https://schema.org/PostalAddress" style="margin: 0.4rem 0;">
                            <span itemprop="streetAddress"><?= e($loc['address']) ?></span>,
                            <span itemprop="addressLocality"><?= e($loc['city_name']) ?></span>,
                            <span itemprop="addressCountry"><?= e($loc['country_name']) ?></span>
                        </p>
                        <?php if (!empty($loc['phone'])): ?>
                            <p style="margin: 0.2rem 0;">
                                <strong>Phone:</strong>
                                <a itemprop="telephone" href="tel:<?= e(preg_replace('/\s+/', '', $loc['phone'])) ?>"><?= e($loc['phone']) ?></a>
                            </p>
                        <?php endif; ?>
                        <?php if (!empty($loc['email'])): ?>
                            <p style="margin: 0.2rem 0;">
                                <strong>Email:</strong>
                                <a itemprop="email" href="mailto:<?= e($loc['email']) ?>"><?= e($loc['email']) ?></a>
                            </p>
                        <?php endif; ?>
                        <?php if (!empty($loc['hours'])): ?>
                            <p style="margin: 0.2rem 0;">
                                <strong>Hours:</strong>
                                <span itemprop="openingHours"><?= e($loc['hours']) ?></span>
                            </p>
                        <?php endif; ?>
                        <p style="margin-top: 0.8rem;">
                            <a class="btn btn-outline btn-sm" target="_blank" rel="noopener" href="<?= e($openSrc) ?>">Open in Google Maps</a>
                        </p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
    .locations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 1.2rem;
    }
    .location-card { padding: 1rem 1.2rem 1.2rem; }
    .location-card h2 { margin: 0 0 0.4rem; font-size: 1.15rem; }
    @media (max-width: 600px) {
        .locations-grid { grid-template-columns: 1fr; }
    }
</style>
