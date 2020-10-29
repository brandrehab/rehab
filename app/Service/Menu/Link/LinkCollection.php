<?php

declare(strict_types=1);

namespace App\Service\Menu\Link;

/**
 * Link collection class.
 */
class LinkCollection implements LinkCollectionInterface {

  /**
   * Iterator key.
   *
   * @var int
   */
  private int $key;

  /**
   * A collection of menu links \App\Menu\LinkInterface.
   *
   * @var array
   */
  private array $collection = [];

  /**
   * Add a link to the collection.
   */
  public function add(LinkInterface $link): void {
    $this->collection[] = $link;
  }

  /**
   * Get the iterator key.
   */
  public function key(): int {
    return $this->key;
  }

  /**
   * Get the current iteration.
   */
  public function current(): LinkInterface {
    return $this->collection[$this->key];
  }

  /**
   * Get the next iteration.
   */
  public function next(): void {
    ++$this->key;
  }

  /**
   * Reset the iterator.
   */
  public function rewind(): void {
    $this->key = 0;
  }

  /**
   * Does the iteration exist.
   */
  public function valid(): bool {
    return array_key_exists($this->key, $this->collection);
  }

}
