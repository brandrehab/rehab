<?php

declare(strict_types=1);

namespace App\Service\Menu;

use App\Service\Menu\Link\LinkCollectionInterface;
use App\Service\Menu\Link\LinkFactoryInterface;
use App\Service\Menu\Link\LinkInterface;
use Drupal\Core\Menu\MenuLinkInterface;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Menu\MenuActiveTrailInterface;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Menu\MenuLinkTreeElement;
use Drupal\Core\Path\PathMatcherInterface;

/**
 * Base class to simplify dealing with menus.
 */
abstract class Menu implements MenuInterface {

  /**
   * System name of menu being managed.
   *
   * @var string
   */
  protected $name;

  /**
   * Tree transformers.
   *
   * @var array
   */
  protected $transformations;

  /**
   * Menu service.
   *
   * @var \Drupal\Core\Menu\MenuLinkTreeInterface
   */
  private $menuLinkTree;

  /**
   * Menu active trail.
   *
   * @var \Drupal\Core\Menu\MenuActiveTrailInterface
   */
  private $menuActiveTrail;

  /**
   * Path matcher.
   *
   * @var \Drupal\Core\Path\PathMatcherInterface
   */
  protected $pathMatcher;

  /**
   * Link factory.
   *
   * @var \App\Service\Menu\Link\LinkFactoryInterface
   */
  protected $linkFactory;

  /**
   * Class constructor.
   */
  public function __construct(
    MenuLinkTreeInterface $menu_link_tree,
    MenuActiveTrailInterface $menu_active_trail,
    PathMatcherInterface $path_matcher,
    LinkFactoryInterface $link_factory
  ) {
    $this->menuLinkTree = $menu_link_tree;
    $this->menuActiveTrail = $menu_active_trail;
    $this->pathMatcher = $path_matcher;
    $this->linkFactory = $link_factory;
  }

  /**
   * Build the menu.
   */
  public function build(int $min_depth = 1, int $max_depth = 1): LinkCollectionInterface {
    $active_trail = $this->menuActiveTrail->getActiveTrailIds($this->name);

    $params = new MenuTreeParameters();
    $params->setMinDepth($min_depth);
    $params->setMaxDepth($max_depth);
    $params->setActiveTrail($active_trail);

    $tree = $this->menuLinkTree->transform($this->menuLinkTree->load($this->name, $params), $this->transformations);

    return $this->processTree($tree);
  }

  /**
   * Process a Tree.
   */
  private function processTree(array $tree): LinkCollectionInterface {
    $collection = $this->linkFactory->createCollection();
    foreach ($tree as $branch) {
      if (!$branch->access->isAllowed()) {
        continue;
      }
      if ($link = $this->createNode($branch)) {
        $collection->add($link);
      }
    }
    return $collection;
  }

  /**
   * Create a menu node and process it's child tree (if present).
   */
  private function createNode(MenuLinkTreeElement $branch): ?LinkInterface {
    $link = $branch->link;
    if (!$link->isEnabled()) {
      return NULL;
    }

    $node = $this->linkFactory->createLink();
    $node->setTitle($link->getTitle());

    $url = $link->getUrlObject();
    if (!$url->isRouted()) {
      $node->setUrl($url->getUri());
      return $node;
    }

    $node->setUrl($url->toString());
    $node->setActive($this->isActive($branch, $link));

    if ($branch->hasChildren) {
      $node->setChildren($this->processTree($branch->subtree));
    }

    return $node;
  }

  /**
   * Test whether the node is within the current url trail.
   */
  private function isActive(MenuLinkTreeElement $branch, MenuLinkInterface $link): bool {
    if ($link->getRouteName() == '<front>') {
      return $this->pathMatcher->isFrontPage();
    }

    return $branch->inActiveTrail;
  }

}
