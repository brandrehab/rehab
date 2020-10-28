<?php

declare(strict_types=1);

namespace App\Form\TeamMember;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the team-member entity edit forms.
 */
class EditForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state): int {
    $entity = $this->getEntity();
    $status = $entity->save();

    $arguments = ['%label' => $this->entity->label()];

    if ($status == SAVED_NEW) {
      $this->messenger()->addStatus($this->t('New team member %label has been created.', $arguments));
      $this->logger('team')->notice('Created new team member %label', $arguments);
    }
    else {
      $this->messenger()->addStatus($this->t('The team member %label has been updated.', $arguments));
      $this->logger('team')->notice('Updated new team member %label.', $arguments);
    }

    if ($this->entity->id()) {
      $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    }
    else {
      $this->messenger()->addError($this->t('The team member could not be saved.', $arguments));
      $this->logger('team')->notice('Failed to save team member %label', $arguments);
      $form_state->setRebuild();
    }

    return $status;
  }

}
