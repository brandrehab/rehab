<?php

/**
 * Valet driver for Drupal 9 rehab.
 */
class RehabValetDriver extends ValetDriver {

  /**
   * Determine if the driver serves the request.
   */
  public function serves($sitePath, $siteName, $uri) {
    if (file_exists($sitePath . '/.rehab')) {
      return TRUE;
    }
  }

  /**
   * Determine if the incoming request is for a static file.
   */
  public function isStaticFile($sitePath, $siteName, $uri) {
    if (file_exists($sitePath . $uri) && !is_dir($sitePath . $uri) && pathinfo($sitePath . $uri)['extension'] != 'php') {
      return $sitePath . $uri;
    }
    return NULL;
  }

  /**
   * Get the fully resolved path to the application's front controller.
   */
  public function frontControllerPath($sitePath, $siteName, $uri) {
    if (!isset($_GET['q']) && !empty($uri) && $uri !== '/') {
      $_GET['q'] = $uri;
    }

    $matches = [];
    if (preg_match('/^\/(.*?)\.php/', $uri, $matches)) {
      $filename = $matches[0];
      if (file_exists($sitePath . $filename) && !is_dir($sitePath . $filename)) {
        $_SERVER['SCRIPT_FILENAME'] = $sitePath . $filename;
        $_SERVER['SCRIPT_NAME'] = $filename;
        return $sitePath . $filename;
      }
    }

    $_SERVER['SCRIPT_FILENAME'] = $sitePath . '/index.php';
    $_SERVER['SCRIPT_NAME'] = '/index.php';
    return $sitePath . '/index.php';
  }

}
