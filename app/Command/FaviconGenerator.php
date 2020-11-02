<?php

declare(strict_types=1);

namespace App\Command;

use Drush\Commands\DrushCommands;
use Intervention\Image\ImageManager;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\PhpStorage\PhpStorageFactory;
use Drupal\Core\Template\TwigEnvironment;
use Drupal\Core\Cache\Cache;

/**
 * Favicon generator command.
 */
class FaviconGenerator extends DrushCommands {

  /**
   * Android sizes.
   *
   * @var array
   */
  private array $androidSizes = [
    36,
    48,
    72,
    96,
    144,
    192,
    256,
    384,
    512,
  ];

  /**
   * Apple sizes.
   *
   * @var array
   */
  private array $appleSizes = [
    57,
    60,
    72,
    76,
    114,
    120,
    144,
    152,
    167,
    180,
    1024,
  ];

  /**
   * Apple landscape sizes.
   *
   * @var array
   */
  private array $appleLandscapeSizes = [
    [1136, 640],
    [1334, 750],
    [1792, 828],
    [2048, 1536],
    [2160, 1620],
    [2208, 1242],
    [2224, 1668],
    [2388, 1668],
    [2688, 1242],
    [2732, 2048],
  ];

  /**
   * Apple portrait sizes.
   *
   * @var array
   */
  private array $applePortraitSizes = [
    [640, 1136],
    [750, 1334],
    [828, 1792],
    [1125, 2436],
    [1242, 2208],
    [1242, 2688],
    [1536, 2048],
    [1620, 2160],
    [1668, 2388],
    [2048, 2732],
  ];

  /**
   * Microsoft sizes.
   *
   * @var array
   */
  private array $microsoftSizes = [
    70,
    144,
    150,
    310,
  ];

  /**
   * Microsoft landscape sizes.
   *
   * @var array
   */
  private array $microsoftLandscapeSizes = [
    [310, 150],
  ];

  /**
   * Yandex sizes.
   *
   * @var array
   */
  private array $yandexSizes = [
    50,
  ];

  /**
   * Coast sizes.
   *
   * @var array
   */
  private array $coastSizes = [
    228,
  ];

  /**
   * Firefox sizes.
   *
   * @var array
   */
  private array $firefoxSizes = [
    60,
    128,
    512,
  ];

  /**
   * Icon sizes.
   *
   * @var array
   */
  private array $iconSizes = [
    16,
    32,
    48,
    64,
  ];

  /**
   * Config options.
   *
   * @var object
   */
  private object $configuration;

  /**
   * Absolute path to build favicon.
   *
   * @var string
   */
  const BUILD_FAVICON_PATH = DRUPAL_ROOT . '/../' . 'build/images/favicon/favicon.svg';

  /**
   * Absolute path to output directory.
   *
   * @var string
   */
  const ICONS_PATH = DRUPAL_ROOT . '/icons/';

  /**
   * Absolute path to configuration file.
   *
   * @var string
   */
  const CONFIG_PATH = DRUPAL_ROOT . '/../favicon.json';

  /**
   * Absolute path to html twig template.
   *
   * @var string
   */
  const TWIG_PATH = DRUPAL_ROOT . '/modules/custom/head/templates/icons.html.twig';

  /**
   * Random directory name.
   *
   * @var string
   */
  private string $randomDirectory;

  /**
   * Image manager.
   *
   * @var \Intervention\Image\ImageManager
   */
  private ImageManager $imageManager;

  /**
   * File system.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  private FileSystemInterface $fileSystem;

  /**
   * Twig environment.
   *
   * @var \Drupal\Core\Template\TwigEnvironment
   */
  private TwigEnvironment $twig;

  /**
   * Class constructor.
   */
  public function __construct(
    FileSystemInterface $file_system,
    TwigEnvironment $twig
  ) {
    $this->fileSystem = $file_system;
    $this->twig = $twig;
    $this->randomDirectory = md5((string) time());
    $this->imageManager = new ImageManager(['driver' => 'imagick']);
    $this->configuration = json_decode(file_get_contents(self::CONFIG_PATH));
  }

  /**
   * Generate favicons files and manifests.
   *
   * @command app:favicon
   * @aliases af
   * @usage drush app:favicon
   *   Generate favicons files and manifests.
   */
  public function favicon(): int {
    $this->io()->title('Attempting to generate favicons.');
    $this->prepare();
    $this->android();
    $this->apple();
    $this->appleLandscape();
    $this->applePortrait();
    $this->microsoft();
    $this->microsoftLandscape();
    $this->yandex();
    $this->coast();
    $this->firefox();
    $this->icon();
    $this->browserConfig();
    $this->manifest();
    $this->webapp();
    $this->yandexManifest();
    $this->meta();
    $this->clearCache();
    return self::EXIT_SUCCESS;
  }

  /**
   * Prepares icon directory for new favicons run.
   */
  private function prepare(): void {
    if (is_dir(self::ICONS_PATH)) {
      $this->fileSystem->deleteRecursive(self::ICONS_PATH);
      $this->fileSystem->mkdir($this->iconPath(), NULL, TRUE);
    }
  }

  /**
   * Clear twig cache.
   */
  private function clearCache(): void {
    $this->twig->invalidate();
    PhpStorageFactory::get('twig')->deleteAll();
    Cache::invalidateTags(['rendered']);
  }

  /**
   * Concats icon path and random directory.
   */
  private function iconPath(): string {
    return self::ICONS_PATH . $this->randomDirectory . '/';
  }

  /**
   * Generate android chrome favicons.
   */
  private function android(): void {
    foreach ($this->androidSizes as $size) {
      $image = $this->imageManager->make(self::BUILD_FAVICON_PATH);
      $image->resize($size, $size)->save($this->iconPath() . 'android-chrome-' . $size . 'x' . $size . '.png', 100, 'png');
    }
  }

  /**
   * Generate apple favicons.
   */
  private function apple(): void {
    foreach ($this->appleSizes as $size) {
      $image = $this->imageManager->make(self::BUILD_FAVICON_PATH);
      $image->resize($size, $size);
      $base_image = $this->imageManager->canvas($size, $size, $this->configuration->background);
      $base_image->insert($image)->save($this->iconPath() . 'apple-touch-icon-' . $size . 'x' . $size . '.png', 100, 'png');
    }

    $image = $this->imageManager->make(self::BUILD_FAVICON_PATH);
    $image->resize(180, 180);

    $base_image = $this->imageManager->canvas(180, 180, $this->configuration->background);
    $base_image->insert($image)->save($this->iconPath() . 'apple-touch-icon-precomposed.png', 100, 'png');

    $base_image = $this->imageManager->canvas(180, 180, $this->configuration->background);
    $base_image->insert($image)->save($this->iconPath() . 'apple-touch-icon.png', 100, 'png');
  }

  /**
   * Generate apple landscape favicons.
   */
  private function appleLandscape(): void {
    foreach ($this->appleLandscapeSizes as $size) {
      $image = $this->imageManager->make(self::BUILD_FAVICON_PATH);
      $image->resize($size[1], $size[1]);
      $base_image = $this->imageManager->canvas($size[0], $size[1], $this->configuration->background);
      $base_image->insert($image, 'center')->save($this->iconPath() . 'apple-touch-startup-image-' . $size[0] . 'x' . $size[1] . '.png', 100, 'png');
    }
  }

  /**
   * Generate apple portrait favicons.
   */
  private function applePortrait(): void {
    foreach ($this->applePortraitSizes as $size) {
      $image = $this->imageManager->make(self::BUILD_FAVICON_PATH);
      $image->resize($size[0], $size[0]);
      $base_image = $this->imageManager->canvas($size[0], $size[1], $this->configuration->background);
      $base_image->insert($image, 'center')->save($this->iconPath() . 'apple-touch-startup-image-' . $size[0] . 'x' . $size[1] . '.png', 100, 'png');
    }
  }

  /**
   * Generate microsoft favicons.
   */
  private function microsoft(): void {
    foreach ($this->microsoftSizes as $size) {
      $image = $this->imageManager->make(self::BUILD_FAVICON_PATH);
      $image->resize($size, $size)->save($this->iconPath() . 'mstile-' . $size . 'x' . $size . '.png', 100, 'png');
    }
  }

  /**
   * Generate microsoft landscape favicons.
   */
  private function microsoftLandscape(): void {
    foreach ($this->microsoftLandscapeSizes as $size) {
      $image = $this->imageManager->make(self::BUILD_FAVICON_PATH);
      $image->resize($size[1], $size[1]);
      $base_image = $this->imageManager->canvas($size[0], $size[1]);
      $base_image->insert($image, 'center')->save($this->iconPath() . 'mstile-' . $size[0] . 'x' . $size[1] . '.png', 100, 'png');
    }
  }

  /**
   * Generate yandex favicons.
   */
  private function yandex(): void {
    foreach ($this->yandexSizes as $size) {
      $image = $this->imageManager->make(self::BUILD_FAVICON_PATH);
      $image->resize($size, $size)->save($this->iconPath() . 'yandex-browser-' . $size . 'x' . $size . '.png', 100, 'png');
    }
  }

  /**
   * Generate coast favicons.
   */
  private function coast(): void {
    foreach ($this->coastSizes as $size) {
      $image = $this->imageManager->make(self::BUILD_FAVICON_PATH);
      $image->resize($size, $size);
      $base_image = $this->imageManager->canvas($size, $size, $this->configuration->background);
      $base_image->insert($image)->save($this->iconPath() . 'coast-' . $size . 'x' . $size . '.png', 100, 'png');
    }
  }

  /**
   * Generate firefox favicons.
   */
  private function firefox(): void {
    foreach ($this->firefoxSizes as $size) {
      $image = $this->imageManager->make(self::BUILD_FAVICON_PATH);
      $image->resize($size, $size)->save($this->iconPath() . 'firefox_app_' . $size . 'x' . $size . '.png', 100, 'png');
    }
  }

  /**
   * Generate icon favicons.
   */
  private function icon(): void {
    foreach ($this->iconSizes as $size) {
      $image = $this->imageManager->make(self::BUILD_FAVICON_PATH);
      $image->resize($size, $size)->save($this->iconPath() . 'favicon-' . $size . 'x' . $size . '.png', 100, 'png');
    }
  }

  /**
   * Generate browserconfig.
   */
  private function browserConfig(): void {
    $directory = $this->randomDirectory;
    $background = $this->configuration->background;

    $xml = <<<END
    <?xml version="1.0" encoding="utf-8"?>
    <browserconfig>
        <msapplication>
            <tile>
                <square70x70logo src="/icons/$directory/mstile-70x70.png"/>
                <square150x150logo src="/icons/$directory/mstile-150x150.png"/>
                <wide310x150logo src="/icons/$directory/mstile-310x150.png"/>
                <square310x310logo src="/icons/$directory/mstile-310x310.png"/>
                <TileColor>$background</TileColor>
            </tile>
        </msapplication>
    </browserconfig>
    END;

    file_put_contents($this->iconPath() . 'browserconfig.xml', $xml);
  }

  /**
   * Generate manifest.
   */
  private function manifest(): void {
    $directory = $this->randomDirectory;

    $icons = [];
    foreach ($this->androidSizes as $size) {
      $icons[] = [
        'src' => '/icons/' . $directory . '/android-chrome-' . $size . 'x' . $size . '.png',
        'sizes' => $size . 'x' . $size,
        'type' => 'image/png',
      ];
    }

    $manifest = [
      'name' => $this->configuration->appName,
      'short_name' => $this->configuration->appShortName,
      'description' => $this->configuration->appDescription,
      'dir' => $this->configuration->dir,
      'lang' => $this->configuration->lang,
      'display' => $this->configuration->display,
      'orientation' => $this->configuration->orientation,
      'scope' => $this->configuration->scope,
      'start_url' => $this->configuration->startURL,
      'background_color' => $this->configuration->background,
      'theme_color' => $this->configuration->themeColor,
      'icons' => $icons,
    ];

    file_put_contents($this->iconPath() . 'manifest.json', json_encode($manifest, JSON_UNESCAPED_SLASHES));
  }

  /**
   * Generate webapp.
   */
  private function webapp(): void {
    $directory = $this->randomDirectory;

    $icons = [];
    foreach ($this->firefoxSizes as $size) {
      $icons[] = [
        $size => '/icons/' . $directory . '/firefox_app_' . $size . 'x' . $size . '.png',
      ];
    }

    $webapp = [
      'version' => $this->configuration->version,
      'name' => $this->configuration->appName,
      'short_name' => $this->configuration->appShortName,
      'description' => $this->configuration->appDescription,
      'icons' => $icons,
      'developer' => [
        'name' => $this->configuration->developerName,
        'url' => $this->configuration->developerURL,
      ],
    ];

    file_put_contents($this->iconPath() . 'manifest.webapp', json_encode($webapp, JSON_UNESCAPED_SLASHES));
  }

  /**
   * Generate yandex manifest.
   */
  private function yandexManifest(): void {
    $directory = $this->randomDirectory;

    $manifest = [
      'version' => $this->configuration->version,
      'api_version' => $this->configuration->api,
      'layout' => [
        'logo' => '/icons/' . $directory . '/yandex-browser-50x50.png',
        'color' => $this->configuration->background,
        'show_title' => TRUE,
      ],
    ];

    file_put_contents($this->iconPath() . 'yandex-browser-manifest.json', json_encode($manifest, JSON_UNESCAPED_SLASHES));
  }

  /**
   * Generate meta tags.
   */
  private function meta(): void {
    $directory = $this->randomDirectory;
    $background = $this->configuration->background;
    $theme = $this->configuration->themeColor;
    $name = $this->configuration->appName;
    $bar = $this->configuration->appleStatusBarStyle;

    $html = <<<END
      <link rel="icon" type="image/png" sizes="16x16" href="/icons/$directory/favicon-16x16.png">
      <link rel="icon" type="image/png" sizes="32x32" href="/icons/$directory/favicon-32x32.png">
      <link rel="icon" type="image/png" sizes="48x48" href="/icons/$directory/favicon-48x48.png">
      <link rel="icon" type="image/png" sizes="64x64" href="/icons/$directory/favicon-64x64.png">
      <link rel="manifest" href="/icons/$directory/manifest.json">
      <meta name="mobile-web-app-capable" content="yes">
      <meta name="theme-color" content="$theme">
      <meta name="application-name" content="$name">
      <link rel="apple-touch-icon" sizes="57x57" href="/icons/$directory/apple-touch-icon-57x57.png">
      <link rel="apple-touch-icon" sizes="60x60" href="/icons/$directory/apple-touch-icon-60x60.png">
      <link rel="apple-touch-icon" sizes="72x72" href="/icons/$directory/apple-touch-icon-72x72.png">
      <link rel="apple-touch-icon" sizes="76x76" href="/icons/$directory/apple-touch-icon-76x76.png">
      <link rel="apple-touch-icon" sizes="114x114" href="/icons/$directory/apple-touch-icon-114x114.png">
      <link rel="apple-touch-icon" sizes="120x120" href="/icons/$directory/apple-touch-icon-120x120.png">
      <link rel="apple-touch-icon" sizes="144x144" href="/icons/$directory/apple-touch-icon-144x144.png">
      <link rel="apple-touch-icon" sizes="152x152" href="/icons/$directory/apple-touch-icon-152x152.png">
      <link rel="apple-touch-icon" sizes="167x167" href="/icons/$directory/apple-touch-icon-167x167.png">
      <link rel="apple-touch-icon" sizes="180x180" href="/icons/$directory/apple-touch-icon-180x180.png">
      <link rel="apple-touch-icon" sizes="1024x1024" href="/icons/$directory/apple-touch-icon-1024x1024.png">
      <meta name="apple-mobile-web-app-capable" content="yes">
      <meta name="apple-mobile-web-app-status-bar-style" content="$bar">
      <meta name="apple-mobile-web-app-title" content="$name">
      <link rel="apple-touch-startup-image" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)"    href="/icons/$directory/apple-touch-startup-image-640x1136.png">
      <link rel="apple-touch-startup-image" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)"    href="/icons/$directory/apple-touch-startup-image-750x1334.png">
      <link rel="apple-touch-startup-image" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)"    href="/icons/$directory/apple-touch-startup-image-828x1792.png">
      <link rel="apple-touch-startup-image" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)"    href="/icons/$directory/apple-touch-startup-image-1125x2436.png">
      <link rel="apple-touch-startup-image" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)"    href="/icons/$directory/apple-touch-startup-image-1242x2208.png">
      <link rel="apple-touch-startup-image" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)"    href="/icons/$directory/apple-touch-startup-image-1242x2688.png">
      <link rel="apple-touch-startup-image" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)"   href="/icons/$directory/apple-touch-startup-image-1536x2048.png">
      <link rel="apple-touch-startup-image" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)"   href="/icons/$directory/apple-touch-startup-image-1668x2224.png">
      <link rel="apple-touch-startup-image" media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)"   href="/icons/$directory/apple-touch-startup-image-1668x2388.png">
      <link rel="apple-touch-startup-image" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)"  href="/icons/$directory/apple-touch-startup-image-2048x2732.png">
      <link rel="apple-touch-startup-image" media="(device-width: 810px) and (device-height: 1080px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)"  href="/icons/$directory/apple-touch-startup-image-1620x2160.png">
      <link rel="apple-touch-startup-image" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)"   href="/icons/$directory/apple-touch-startup-image-1136x640.png">
      <link rel="apple-touch-startup-image" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)"   href="/icons/$directory/apple-touch-startup-image-1334x750.png">
      <link rel="apple-touch-startup-image" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)"   href="/icons/$directory/apple-touch-startup-image-1792x828.png">
      <link rel="apple-touch-startup-image" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)"   href="/icons/$directory/apple-touch-startup-image-2436x1125.png">
      <link rel="apple-touch-startup-image" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)"   href="/icons/$directory/apple-touch-startup-image-2208x1242.png">
      <link rel="apple-touch-startup-image" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)"   href="/icons/$directory/apple-touch-startup-image-2688x1242.png">
      <link rel="apple-touch-startup-image" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)"  href="/icons/$directory/apple-touch-startup-image-2048x1536.png">
      <link rel="apple-touch-startup-image" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)"  href="/icons/$directory/apple-touch-startup-image-2224x1668.png">
      <link rel="apple-touch-startup-image" media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)"  href="/icons/$directory/apple-touch-startup-image-2388x1668.png">
      <link rel="apple-touch-startup-image" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" href="/icons/$directory/apple-touch-startup-image-2732x2048.png">
      <link rel="apple-touch-startup-image" media="(device-width: 810px) and (device-height: 1080px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)"  href="/icons/$directory/apple-touch-startup-image-2160x1620.png">
      <link rel="icon" type="image/png" sizes="228x228" href="/icons/$directory/coast-228x228.png">
      <meta name="msapplication-TileColor" content="$background">
      <meta name="msapplication-TileImage" content="/icons/$directory/mstile-144x144.png">
      <meta name="msapplication-config" content="/icons/$directory/browserconfig.xml">
      <link rel="yandex-tableau-widget" href="/icons/$directory/yandex-browser-manifest.json">
    END;

    file_put_contents(self::TWIG_PATH, $html);
  }

}
