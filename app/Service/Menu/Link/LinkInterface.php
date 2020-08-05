<?php

declare(strict_types=1);

namespace App\Service\Menu\Link;

/**
 * Link interface.
 */
interface LinkInterface {

  /**
   * Title getter.
   */
  public function getTitle(): string;

  /**
   * Url getter.
   */
  public function getUrl(): string;

  /**
   * Node id getter.
   */
  public function getNid(): ?int;

  /**
   * Active getter.
   */
  public function getActive(): bool;

  /**
   * Active getter.
   */
  public function getChildren(): ?LinkCollectionInterface;

  /**
   * Title setter.
   */
  public function setTitle(string $title): void;

  /**
   * Url setter.
   */
  public function setUrl(string $url): void;

  /**
   * Node id setter.
   */
  public function setNid(int $nid): void;

  /**
   * Active setter.
   */
  public function setActive(bool $active): void;

  /**
   * Children setter.
   */
  public function setChildren(LinkCollectionInterface $children): void;

  /**
   * Magic getter.
   *
   * @return mixed
   *   Property of the class.
   */
  public function __get(string $key);

  /**
   * Magic isset (required by twig).
   */
  public function __isset(string $key): bool;

}
