<?php

declare(strict_types=1);

namespace App\Service\Navigation;

use App\Service\Navigation\Link\LinkCollectionInterface;
use App\Service\Navigation\Link\LinkFactoryInterface;
use App\Service\Navigation\Link\LinkInterface;
use Drupal\Core\Menu\MenuLinkInterface;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Menu\MenuActiveTrailInterface;
use Drupal\Core\Menu\MenuTreeParameters;
use Drupal\Core\Menu\MenuLinkTreeElement;
use Drupal\Core\Path\PathMatcherInterface;

/**
 * Builds navigations from menus.
 */
class NavigationBuilder implements NavigationBuilderInterface {

  /**
   * Tree transformers.
   *
   * @var array
   */
  protected array $transformations = [
    ['callable' => 'menu.default_tree_manipulators:checkNodeAccess'],
    ['callable' => 'menu.default_tree_manipulators:checkAccess'],
    ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
  ];

  /**
   * Menu link tree.
   *
   * @var \Drupal\Core\Menu\MenuLinkTreeInterface
   */
  private MenuLinkTreeInterface $menuLinkTree;

  /**
   * Menu active trail.
   *
   * @var \Drupal\Core\Menu\MenuActiveTrailInterface
   */
  private MenuActiveTrailInterface $menuActiveTrail;

  /**
   * Path matcher.
   *
   * @var \Drupal\Core\Path\PathMatcherInterface
   */
  protected PathMatcherInterface $pathMatcher;

  /**
   * Link factory.
   *
   * @var \App\Service\Navigation\Link\LinkFactoryInterface
   */
  protected LinkFactoryInterface $linkFactory;

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
   * Build a navigation.
   */
  public function build(
    string $menu_name,
    ?int $min_depth = 1,
    ?int $max_depth = 1,
    ?string $root = ''
  ): LinkCollectionInterface {
    $active_trail = $this->menuActiveTrail->getActiveTrailIds($menu_name);

    $params = new MenuTreeParameters();
    $params->setMinDepth($min_depth);
    $params->setMaxDepth($max_depth);
    $params->onlyEnabledLinks();
    $params->setRoot($root);
    $params->setActiveTrail($active_trail);

    $tree = $this->menuLinkTree->transform($this->menuLinkTree->load($menu_name, $params), $this->transformations);

    return $this->processTree($tree);
  }

  /**
   * Get nids from a link collection as one-dimensional array.
   */
  public function getNids(LinkCollectionInterface $links): array {
    $nids = [];
    foreach ($links as $link) {
      if (!$link->nid) {
        continue;
      }
      $nids[] = $link->nid;
    }

    return $nids;
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
      $link = $this->createNode($branch);
      if ($link != NULL) {
        $collection->add($link);
      }
    }
    return $collection;
  }

  /**
   * Create a menu node and process it's child tree (if present).
   */
  private function createNode(MenuLinkTreeElement $branch): LinkInterface {
    $link = $branch->link;

    $node = $this->linkFactory->createLink();
    $node->setTitle($link->getTitle());

    $url = $link->getUrlObject();
    if (!$url->isRouted()) {
      $node->setUrl($url->getUri());
      return $node;
    }

    $params = $url->getRouteParameters();

    if (array_key_exists('node', $params)) {
      $node->setNid((int) $params['node']);
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
