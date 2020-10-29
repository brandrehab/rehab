<?php

declare(strict_types=1);

namespace App\Controller;

use Hhxsv5\SSE\SSE;
use Hhxsv5\SSE\Event;
use Hhxsv5\SSE\StopSSEException;
use Drupal\Core\Render\RendererInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\PhpStorage\PhpStorageFactory;
use Drupal\Core\Template\TwigEnvironment;
use Drupal\Core\Cache\Cache;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Development controller.
 */
class DevelopmentController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  private RendererInterface $renderer;

  /**
   * Twig environment.
   *
   * @var \Drupal\Core\Template\TwigEnvironment
   */
  private TwigEnvironment $twig;

  /**
   * Dependency injection.
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('renderer'),
      $container->get('twig')
    );
  }

  /**
   * Class constructor.
   */
  public function __construct(RendererInterface $renderer, TwigEnvironment $twig) {
    $this->renderer = $renderer;
    $this->twig = $twig;
  }

  /**
   * Functionality test bed.
   */
  public function test(): string {
    echo 'hello';
    die();
  }

  /**
   * Handles requests for nodes of type article.
   */
  public function hotReload(Request $request): StreamedResponse {

    $response = new StreamedResponse();
    $response->headers->set('Content-Type', 'text/event-stream');
    $response->headers->set('Cache-Control', 'no-cache');
    $response->headers->set('Connection', 'keep-alive');
    $response->headers->set('X-Accel-Buffering', 'no');
    $response->setCallback(function () {
        $hash = filemtime(DRUPAL_ROOT . '/../build.version');
        $callback = function () use (&$hash) {

          if (connection_status() != CONNECTION_NORMAL or connection_aborted()) {
              throw new StopSSEException();
          }

          clearstatcache();
          $current_hash = filemtime(DRUPAL_ROOT . '/../build.version');

          if ($hash == $current_hash) {
            return FALSE;
          }
          $hash = $current_hash;

          $this->twig->invalidate();
          PhpStorageFactory::get('twig')->deleteAll();
          Cache::invalidateTags(['rendered']);

          $reload = [
            [
              'hash' => $hash,
            ],
          ];

          $hash = $current_hash;

          return json_encode(compact('reload'));
        };
        (new SSE(new Event($callback, 'reload')))->start(3);
    });
    return $response;
  }

}
