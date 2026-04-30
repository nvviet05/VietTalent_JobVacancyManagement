<?php $activeLang = currentLang(); ?>
<div class="language-switcher" aria-label="<?= e(t('language')) ?>">
    <a class="<?= $activeLang === 'en' ? 'active' : '' ?>" href="<?= e(langUrl('en')) ?>">EN</a>
    <a class="<?= $activeLang === 'vi' ? 'active' : '' ?>" href="<?= e(langUrl('vi')) ?>">VI</a>
</div>
