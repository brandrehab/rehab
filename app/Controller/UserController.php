<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * User controller.
 */
class UserController {

  /**
   * Handle /user/{id} requests.
   */
  public function view(): RedirectResponse {
    return new RedirectResponse('/admin/content');
  }

}
