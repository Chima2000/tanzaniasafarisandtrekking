<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$catalog = $app->make(App\Support\TourCatalog::class);
$packages = $catalog->packages();

echo 'Packages: '.$packages->count().PHP_EOL;
echo 'First: '.$packages->first()['name'].PHP_EOL;
echo 'Image: '.$packages->first()['image'].PHP_EOL;
echo 'Vibes: '.$catalog->vibes()->count().PHP_EOL;
echo 'Destinations: '.$catalog->destinations()->count().PHP_EOL;
echo 'Gallery: '.count($catalog->galleryImages()).PHP_EOL;

$response = $app->make(Illuminate\Contracts\Http\Kernel::class)->handle(
    Illuminate\Http\Request::create('/', 'GET')
);

echo 'HTTP status: '.$response->getStatusCode().PHP_EOL;
$content = $response->getContent();
echo 'Has Tanzania Safaris: '.(str_contains($content, 'Tanzania Safaris') ? 'yes' : 'no').PHP_EOL;
echo 'Has package name: '.(str_contains($content, 'Serengeti') ? 'yes' : 'no').PHP_EOL;
echo 'Has photos path: '.(str_contains($content, '/photos/') ? 'yes' : 'no').PHP_EOL;
echo 'Has aventour assets: '.(str_contains($content, '/aventour/assets/') ? 'yes' : 'no').PHP_EOL;
echo 'Content length: '.strlen($content).PHP_EOL;

if ($response->getStatusCode() >= 400) {
    echo substr(strip_tags($content), 0, 1000).PHP_EOL;
    exit(1);
}
