<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Provide twig with a base64 filter.
 */
class Base64TwigExtension extends AbstractExtension {

  /**
   * {@inheritdoc}
   */
  public function getFilters(): array {
    return [
      new TwigFilter('base64', [$this, 'encodeString']),
    ];
  }

  /**
   * Base64 encode a given string.
   */
  public static function encodeString(string $string): string {
    return base64_encode($string);
  }

}
