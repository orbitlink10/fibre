<?php

require __DIR__.'/fiber_to_the_home_kenya_article.php';

$store = __DIR__.'/../storage/app/private/pages.json';
$pages = file_exists($store) ? json_decode(file_get_contents($store), true) : [];
if (! is_array($pages)) {
    $pages = [];
}

$article = fiber_to_the_home_kenya_article();
$nextId = empty($pages) ? 1 : (max(array_map(fn ($page) => (int) ($page['id'] ?? 0), $pages)) + 1);
$article['id'] = $nextId;

$found = false;
foreach ($pages as $index => $page) {
    if (($page['slug'] ?? '') === $article['slug'] || ($page['title'] ?? '') === $article['title']) {
        $article['id'] = (int) ($page['id'] ?? $article['id']);
        $pages[$index] = $article;
        $found = true;
        break;
    }
}

if (! $found) {
    $pages[] = $article;
}

usort($pages, fn ($a, $b) => (int) ($b['id'] ?? 0) <=> (int) ($a['id'] ?? 0));
file_put_contents($store, json_encode($pages, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

$plain = trim(preg_replace('/\s+/', ' ', strip_tags($article['description'])));
preg_match_all('/<a\s+[^>]*href=/i', $article['description'], $links);
preg_match_all('/<img\s+[^>]*src=/i', $article['description'], $inlineImages);

echo $article['slug'].PHP_EOL;
echo 'words: '.str_word_count($plain).PHP_EOL;
echo 'links: '.count($links[0]).PHP_EOL;
echo 'inline_images: '.count($inlineImages[0]).PHP_EOL;
echo 'visible_images_including_hero: '.(count($inlineImages[0]) + 1).PHP_EOL;
echo 'hero: '.$article['image'].PHP_EOL;
