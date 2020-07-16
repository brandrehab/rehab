<?php

declare(strict_types=1);

namespace App\Service\Menu\Link;

/**
 * Menu link class.
 */
class Link implements LinkInterface {

  /**
   * Title.
   *
   * @var string
   */
  private $title;

  /**
   * Url.
   *
   * @var string
   */
  private $url;

  /**
   * Active.
   *
   * @var bool
   */
  private $active;

  /**
   * Child links.
   *
   * @var \App\Service\Menu\Link\LinkCollectionInterface
   */
  private $children;

  /**
   * Title getter.
   */
  public function getTitle(): string {
    return $this->title;
  }

  /**
   * Url getter.
   */
  public function getUrl(): string {
    return $this->url;
  }

  /**
   * Active getter.
   */
  public function getActive(): bool {
    return $this->active;
  }

  /**
   * Children getter.
   */
  public function getChildren(): ?LinkCollectionInterface {
    return $this->children;
  }

  /**
   * Title setter.
   */
  public function setTitle(string $title): void {
    $this->title = $title;
  }

  /**
   * Url setter.
   */
  public function setUrl(string $url): void {
    $this->url = $url;
  }

  /**
   * Active setter.
   */
  public function setActive(bool $active): void {
    $this->active = $active;
  }

  /**
   * Children setter.
   */
  public function setChildren(LinkCollectionInterface $children): void {
    $this->children = $children;
  }

  /**
   * Magic getter.
   *
   * @return mixed
   *   Property of the class.
   */
  public function __get(string $key) {
    if (property_exists($this, $key)) {
      return $this->$key;
    }
  }

  /**
   * Magic isset (required by twig).
   */
  public function __isset(string $key): bool {
    if (!property_exists($this, $key)) {
      return FALSE;
    }

    if (!$this->$key) {
      return FALSE;
    }

    return TRUE;
  }

}
