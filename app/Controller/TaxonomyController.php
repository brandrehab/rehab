<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Taxonomy controller.
 */
class TaxonomyController {

  /**
   * Handle taxonomy requests (disable everything to the front of the site).
   */
  public function disable(): void {
    throw new NotFoundHttpException();
  }

}
