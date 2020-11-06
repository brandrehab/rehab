<?php

declare(strict_types=1);

namespace App\Form\Navigation;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the navigation entity edit forms.
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
      $this->messenger()->addStatus($this->t('New navigation %label has been created.', $arguments));
      $this->logger('navigation')->notice('Created new navigation %label', $arguments);
    }
    else {
      $this->messenger()->addStatus($this->t('The navigation %label has been updated.', $arguments));
      $this->logger('navigation')->notice('Updated new navigation %label.', $arguments);
    }

    if ($this->entity->id()) {
      $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    }
    else {
      $this->messenger()->addError($this->t('The navigation could not be saved.', $arguments));
      $this->logger('navigation')->notice('Failed to save navigation %label', $arguments);
      $form_state->setRebuild();
    }

    return $status;
  }

}
