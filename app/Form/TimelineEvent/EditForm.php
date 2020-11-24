<?php

declare(strict_types=1);

namespace App\Form\TimelineEvent;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the timeline event entity edit forms.
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
      $this->messenger()->addStatus($this->t('New timeline event %label has been created.', $arguments));
      $this->logger('timeline')->notice('Created new timeline event %label', $arguments);
    }
    else {
      $this->messenger()->addStatus($this->t('The timeline event %label has been updated.', $arguments));
      $this->logger('timeline')->notice('Updated new team member %label.', $arguments);
    }

    if ($this->entity->id()) {
      $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    }
    else {
      $this->messenger()->addError($this->t('The timeline event could not be saved.', $arguments));
      $this->logger('timeline')->notice('Failed to save timeline event %label', $arguments);
      $form_state->setRebuild();
    }

    return $status;
  }

}
