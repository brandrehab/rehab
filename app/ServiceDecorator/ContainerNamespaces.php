<?php

declare(strict_types=1);

namespace App\ServiceDecorator;

/**
 * Provides namespaces and corresponding paths for plugin discovery.
 */
class ContainerNamespaces extends \ArrayObject {

  /**
   * Original service object.
   *
   * @var \ArrayObject
   */
  protected $originalService;

  /**
   * Class constructor.
   */
  public function __construct(\ArrayObject $original_service, array $namespaces) {
    $this->originalService = $original_service;
    $namespaces = $this->appendNamespace($namespaces, 'App', '../app');
    parent::__construct($namespaces);
  }

  /**
   * Appends a namespace to the container service array.
   */
  protected function appendNamespace(array $namespaces, string $namespace, string $relative_path): array {
    if (!array_key_exists('App', $namespaces)) {
      $namespaces[$namespace] = $relative_path;
    }
    return $namespaces;
  }

}
