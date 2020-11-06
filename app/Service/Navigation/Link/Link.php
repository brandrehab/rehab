<?php

declare(strict_types=1);

namespace App\Service\Navigation\Link;

/**
 * Navigation link class.
 */
class Link implements LinkInterface {

  /**
   * Title.
   *
   * @var string
   */
  private string $title;

  /**
   * Url.
   *
   * @var string
   */
  private string $url;

  /**
   * Node id.
   *
   * @var int|null
   */
  private ?int $nid = NULL;

  /**
   * Active.
   *
   * @var bool
   */
  private bool $active;

  /**
   * Child links.
   *
   * @var \App\Service\Navigation\Link\LinkCollectionInterface|null
   */
  private ?LinkCollectionInterface $children = NULL;

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
   * Node id getter.
   */
  public function getNid(): ?int {
    return $this->nid;
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
   * Node id setter.
   */
  public function setNid(int $nid): void {
    $this->nid = $nid;
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
