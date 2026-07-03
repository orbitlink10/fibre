<?php

$baseUrl = 'https://fiberopticskenya.co.ke';
$email = 'admin@demo.com';
$password = 'admin123';
$slug = 'fiber-optic-technicians-kenya';
$store = __DIR__.'/../storage/app/private/pages.json';
$heroPath = realpath(__DIR__.'/../storage/app/public/pages/fiber-optic-technicians-kenya-hero.jpg');

if (! $heroPath || ! file_exists($heroPath)) {
    fwrite(STDERR, "Hero image not found.\n");
    exit(1);
}

$pages = json_decode(file_get_contents($store), true);
$article = null;
foreach ($pages as $page) {
    if (($page['slug'] ?? '') === $slug) {
        $article = $page;
        break;
    }
}

if (! $article) {
    fwrite(STDERR, "Article not found in pages.json. Run publish_fiber_optic_technicians_kenya_article.php first.\n");
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
        CURLOPT_TIMEOUT => 45,
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

[$status, , $formHtml] = request($baseUrl.'/new-post');
if ($status !== 200) {
    throw new RuntimeException("New post form returned HTTP {$status}.");
}

$postToken = csrf($formHtml);

[$status, $headers, $body] = request($baseUrl.'/pages', [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => [
        '_token' => $postToken,
        'meta_title' => $article['meta_title'],
        'meta_description' => $article['meta_description'],
        'title' => $article['title'],
        'alt' => $article['alt'],
        'heading_2' => $article['heading_2'],
        'type' => $article['type'],
        'description' => $article['description'],
        'image' => new CURLFile($heroPath, 'image/jpeg', 'fiber-optic-technicians-kenya-hero.jpg'),
    ],
]);

if ($status !== 302) {
    file_put_contents(__DIR__.'/../storage/logs/fiber_technicians_remote_publish_failed.html', $body);
    throw new RuntimeException("Post submission returned HTTP {$status}. Response saved to storage/logs/fiber_technicians_remote_publish_failed.html");
}

[$status, , $publicHtml] = request($baseUrl.'/'.$slug);
if ($status !== 200) {
    throw new RuntimeException("Public article returned HTTP {$status}.");
}

$plain = trim(preg_replace('/\s+/', ' ', strip_tags($publicHtml)));
preg_match_all('/<img\b/i', $publicHtml, $images);
preg_match_all('/<a\s+[^>]*href=/i', $publicHtml, $links);

echo $baseUrl.'/'.$slug.PHP_EOL;
echo 'public_status: '.$status.PHP_EOL;
echo 'public_words: '.str_word_count($plain).PHP_EOL;
echo 'public_images: '.count($images[0]).PHP_EOL;
echo 'public_links: '.count($links[0]).PHP_EOL;
