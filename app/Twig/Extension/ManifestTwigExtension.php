<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Provide twig with the ability to fetch versioned resources from the manifest.
 */
class ManifestTwigExtension extends AbstractExtension {

  /**
   * Manifest relating to the current page.
   *
   * @var array|null
   */
  static protected ?array $manifest = NULL;

  /**
   * {@inheritdoc}
   */
  public function getFunctions(): array {
    return [
      new TwigFunction('manifest', [$this, 'getManifestResource']),
    ];
  }

  /**
   * Attempt to retrieve a verionsed resource from the manifest.
   */
  public function getManifestResource(string $resource): ?string {
    $manifest = $this->getManifest();
    return array_key_exists($resource, $manifest) ? $manifest[$resource] : NULL;
  }

  /**
   * Get the resource manifest.
   */
  private function getManifest(): array {
    if (!self::$manifest) {
      $this->setManifest();
    }
    return self::$manifest;
  }

  /**
   * Set the resource manifest.
   */
  private function setManifest(): void {
    self::$manifest = json_decode(file_get_contents(DRUPAL_ROOT . '/dist/manifest.json'), TRUE);
  }

}
