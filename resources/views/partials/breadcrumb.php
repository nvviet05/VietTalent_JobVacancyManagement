<?php /** @var array $bc list of ['label'=>..., 'url'=>...] */ ?>
<nav aria-label="Breadcrumb" class="breadcrumb-nav">
    <div class="container">
        <ol class="breadcrumb-list" itemscope itemtype="https://schema.org/BreadcrumbList">
            <?php foreach ($bc as $i => $crumb): ?>
                <?php $isLast = $i === array_key_last($bc); ?>
                <li class="breadcrumb-item<?= $isLast ? ' is-current' : '' ?>"
                    itemprop="itemListElement"
                    itemscope
                    itemtype="https://schema.org/ListItem">
                    <?php if (!empty($crumb['url']) && !$isLast): ?>
                        <a itemprop="item" href="<?= e($crumb['url']) ?>">
                            <span itemprop="name"><?= e($crumb['label']) ?></span>
                        </a>
                    <?php else: ?>
                        <span itemprop="name" aria-current="page"><?= e($crumb['label']) ?></span>
                    <?php endif; ?>
                    <meta itemprop="position" content="<?= $i + 1 ?>">
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</nav>
