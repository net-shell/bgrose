<?php

namespace Drupal\block_welcome\Controller;

use Drupal\block_welcome\Utility\DescriptionTemplateTrait;

/**
 * Controller routines for block welcome routes.
 */
class BlockWelcomeController {
  use DescriptionTemplateTrait;

  /**
   * {@inheritdoc}
   */
  protected function getModuleName() {
    return 'block_welcome';
  }

}
