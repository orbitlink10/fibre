<?php

require __DIR__.'/single_mode_fiber_cable_kenya_article.php';

$baseUrl = 'https://fiberopticskenya.co.ke';
$email = 'admin@demo.com';
$password = 'admin123';
$article = single_mode_fiber_cable_kenya_article();
$heroPath = realpath(__DIR__.'/../storage/app/public/'.$article['image']);

if (! $heroPath || ! file_exists($heroPath)) {
    fwrite(STDERR, "Hero image not found.\n");
    exit(1);
}

$cookie = tempnam(sys_get_temp_dir(), 'fiber-cookies-');

function request(string $url, array $options = []): array
{
    global $cookie;

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_COOKIEJAR => $cookie,
        CURLOPT_COOKIEFILE => $cookie,
        CURLOPT_USERAGENT => 'Codex publisher',
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_TIMEOUT => 60,
    ] + $options);

    $raw = curl_exec($ch);
    if ($raw === false) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new RuntimeException($error);
    }

    $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($raw, 0, $headerSize);
    $body = substr($raw, $headerSize);
    curl_close($ch);

    return [$status, $headers, $body];
}

function csrf(string $html): string
{
    if (! preg_match('/name="_token"\s+value="([^"]+)"/', $html, $match)) {
        throw new RuntimeException('CSRF token not found.');
    }

    return $match[1];
}

function findPageId(string $html, string $title): ?int
{
    if (! preg_match_all('/<tr\b.*?<\/tr>/is', $html, $rows)) {
        return null;
    }

    foreach ($rows[0] as $row) {
        if (strip_tags($row) !== '' && str_contains(html_entity_decode(strip_tags($row), ENT_QUOTES), $title)) {
            if (preg_match('/\/pages\/(\d+)\/edit/', $row, $match)) {
                return (int) $match[1];
            }
        }
    }

    return null;
}

[$status, , $loginHtml] = request($baseUrl.'/login');
if ($status !== 200) {
    throw new RuntimeException("Login page returned HTTP {$status}.");
}

$loginToken = csrf($loginHtml);

[$status, $headers] = request($baseUrl.'/login', [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        '_token' => $loginToken,
        'email' => $email,
        'password' => $password,
    ]),
    CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
]);

if ($status !== 302 || stripos($headers, '/dashboard') === false) {
    throw new RuntimeException("Login failed or returned unexpected HTTP {$status}.");
}

[$status, , $pagesHtml] = request($baseUrl.'/pages');
if ($status !== 200) {
    throw new RuntimeException("Pages list returned HTTP {$status}.");
}

$pageId = findPageId($pagesHtml, $article['title']);
$isUpdate = $pageId !== null;
$formUrl = $isUpdate ? $baseUrl.'/pages/'.$pageId.'/edit' : $baseUrl.'/new-post';
[$status, , $formHtml] = request($formUrl);
if ($status !== 200) {
    throw new RuntimeException("Post form returned HTTP {$status}.");
}

$postToken = csrf($formHtml);
$postUrl = $isUpdate ? $baseUrl.'/pages/'.$pageId : $baseUrl.'/pages';
$fields = [
    '_token' => $postToken,
    'meta_title' => $article['meta_title'],
    'meta_description' => $article['meta_description'],
    'title' => $article['title'],
    'alt' => $article['alt'],
    'heading_2' => $article['heading_2'],
    'type' => $article['type'],
    'description' => $article['description'],
    'image' => new CURLFile($heroPath, 'image/png', 'single-mode-fiber-cable-kenya-hero.png'),
];

if ($isUpdate) {
    $fields['_method'] = 'PUT';
}

[$status, $headers, $body] = request($postUrl, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $fields,
]);

if ($status !== 302) {
    file_put_contents(__DIR__.'/../storage/logs/single_mode_fiber_remote_publish_failed.html', $body);
    throw new RuntimeException("Post submission returned HTTP {$status}. Response saved to storage/logs/single_mode_fiber_remote_publish_failed.html");
}

[$status, , $publicHtml] = request($baseUrl.'/'.$article['slug']);
if ($status !== 200) {
    throw new RuntimeException("Public article returned HTTP {$status}.");
}

$plain = trim(preg_replace('/\s+/', ' ', strip_tags($publicHtml)));
preg_match_all('/<img\b/i', $publicHtml, $images);
preg_match_all('/<a\s+[^>]*href=/i', $publicHtml, $links);

echo $baseUrl.'/'.$article['slug'].PHP_EOL;
echo 'mode: '.($isUpdate ? 'updated' : 'created').PHP_EOL;
echo 'public_status: '.$status.PHP_EOL;
echo 'public_words: '.str_word_count($plain).PHP_EOL;
echo 'public_images: '.count($images[0]).PHP_EOL;
echo 'public_links: '.count($links[0]).PHP_EOL;
