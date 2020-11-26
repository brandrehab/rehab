<?php

declare(strict_types=1);

namespace App\Storage;

use App\Entity\PageInterface;
use App\Service\Storage\NodeStorageInterface as BaseStorageInterface;

/**
 * Defines an interface for the node entity storage class.
 */
interface NodeStorageInterface extends BaseStorageInterface {

  /**
   * Get page by title.
   */
  public function getPageByTitle(string $title): ?PageInterface;

  /**
   * Get links.
   */
  public function getLinks(string $bundle): ?array;

  /**
   * Get article links.
   */
  public function getArticleLinks(): ?array;

  /**
   * Get service links.
   */
  public function getServiceLinks(): ?array;

}
