<?php

declare(strict_types=1);

namespace App\Plugin\Block;

use App\Base\BlockBase;
use App\Storage\TeamMemberStorageInterface;
use Drupal\taxonomy\TermStorageInterface;
use Drupal\taxonomy\TermInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The layouts team block.
 *
 * @Block(
 *  id = "app.layouts.team",
 *  admin_label = @Translation("Layouts Team Block"),
 * )
 */
class LayoutsTeam extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Cache settings.
   *
   * @var array
   */
  protected array $cache = [
    'contexts' => [
      'route',
    ],
    'tags' => [],
  ];

  /**
   * Team member storage.
   *
   * @var \App\Storage\TeamMemberStorageInterface
   */
  private TeamMemberStorageInterface $teamMemberStorage;

  /**
   * Taxonomy term storage.
   *
   * @var \Drupal\taxonomy\TermStorageInterface
   */
  private TermStorageInterface $taxonomyTermStorage;

  /**
   * Dependency Injection.
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ): self {
    return new self(
     $configuration,
     $plugin_id,
     $plugin_definition,
     $container->get('entity_type.manager')
    );
  }

  /**
   * Class constructor.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityTypeManagerInterface $entity_type_manager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->teamMemberStorage = $entity_type_manager->getStorage('team_member');
    $this->taxonomyTermStorage = $entity_type_manager->getStorage('taxonomy_term');
  }

  /**
   * Build the render array.
   */
  public function build(): array {
    return [
      '#theme' => 'layouts_team',
      '#team' => $this->getTeamMembers(),
      '#cache' => $this->cache,
    ];
  }

  /**
   * Get the team members.
   */
  private function getTeamMembers(): ?array {
    $this->setTeamMembersListTag();
    $this->setDepartmentsVocabularyListTag();
    $config = $this->getConfiguration();

    if ($department = $config['team']['department']) {
      return $this->getDepartmentTeamMembers($department->entity);
    }
    return $this->getAllTeamMembers();
  }

  /**
   * Get team members of a specific department.
   */
  private function getDepartmentTeamMembers(TermInterface $department): ?array {
    $dept_id = (int) $department->id();
    if (!$members = $this->teamMemberStorage->getByDeptId($dept_id)) {
      return NULL;
    }

    return [
      $department->label() => $members,
    ];
  }

  /**
   * Get team members from all departments.
   */
  private function getAllTeamMembers(): ?array {
    return $this->teamMemberStorage->getAllByDept();
  }

  /**
   * Set a list cache tag based on the team members entity.
   */
  private function setTeamMembersListTag(): void {
    $team_member_type = $this->teamMemberStorage->getEntityType();
    $this->appendEntityTypeListCacheTags($team_member_type);
  }

  /**
   * Set a list cache tag based on the departments taxonomy vocabulary.
   */
  private function setDepartmentsVocabularyListTag(): void {
    $taxonomy_term_type = $this->taxonomyTermStorage->getEntityType();
    $this->appendEntityTypeListCacheTags($taxonomy_term_type, 'team_departments');
  }

}
