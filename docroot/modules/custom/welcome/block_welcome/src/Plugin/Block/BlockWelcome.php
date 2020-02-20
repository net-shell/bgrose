<?php

namespace Drupal\block_welcome\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

/**
 * Provides a 'block_welcome' block.
 *
 * Drupal\Core\Block\BlockBase gives us a very useful set of basic functionality
 * for this configurable block. We can just fill in a few of the blanks with
 * defaultConfiguration(), blockForm(), blockSubmit(), and build().
 *
 * @Block(
 *   id = "block_welcome",
 *   admin_label = @Translation("Welcome block")
 * )
 */
class BlockWelcome extends BlockBase
{

  /**
   * {@inheritdoc}
   *
   * This method sets the block default configuration. This configuration
   * determines the block's behavior when a block is initially placed in a
   * region. Default values for the block configuration form should be added to
   * the configuration array. System default configurations are assembled in
   * BlockBase::__construct() e.g. cache setting and block title visibility.
   *
   * @see \Drupal\block\BlockBase::__construct()
   */
  public function defaultConfiguration()
  {
    return [
      'block_welcome_text' => $this->t('Welcome, '),
      'block_welcome_image' => ['value' => ''],
    ];
  }

  /**
   * {@inheritdoc}
   *
   * This method defines form elements for custom block configuration. Standard
   * block configuration fields are added by BlockBase::buildConfigurationForm()
   * (block title and title visibility) and BlockFormController::form() (block
   * visibility settings).
   *
   * @see \Drupal\block\BlockBase::buildConfigurationForm()
   * @see \Drupal\block\BlockFormController::form()
   */
  public function blockForm($form, FormStateInterface $form_state)
  {
    $form['block_welcome_string_text'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Block contents'),
      '#description' => $this->t('This text will appear before the user name.'),
      '#default_value' => $this->configuration['block_welcome_text'],
    ];
    $form['block_welcome_image'] = array(
      '#type' => 'managed_file',
      '#upload_location' => 'public://images/',
      '#title' => $this->t('Image'),
      '#description' => t("Image to show"),
      '#default_value' => $this->configuration['block_welcome_image'],
      '#upload_validators' => array(
        'file_validate_extensions' => array('gif png jpg jpeg'),
        'file_validate_size' => array(25600000),
      ),
      '#states'        => array(
        'visible'      => array(
          ':input[name="image_type"]' => array('value' => t('Upload New Image(s)')),
        )
      )
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   *
   * This method processes the blockForm() form fields when the block
   * configuration form is submitted.
   *
   * The blockValidate() method can be used to validate the form submission.
   */
  public function blockSubmit($form, FormStateInterface $form_state)
  {

    if(isset($this->configuration['block_welcome_image']) && !empty($this->configuration['block_welcome_image'])) {
      /* Fetch the array of the file stored temporarily in database */
      $image = $form_state->getValue('block_welcome_image');

      $this->configuration['block_welcome_image'] = $image;

      /* Load the object of the file by it's fid */
      $file = \Drupal\file\Entity\File::load($image[0]);

      /* Set the status flag permanent of the file object */
      $file->setPermanent();

      $this->configuration['block_welcome_text'] = $form_state->getValue('block_welcome_string_text');
    }
  }

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    $image = null;
    if(isset($this->configuration['block_welcome_image']) && !empty($this->configuration['block_welcome_image'])) {
      $image_field = $this->configuration['block_welcome_image'];
      $image_uri = File::load($image_field[0]);
      $image = $image_uri->createFileUrl();
    }
    $text = $this->configuration['block_welcome_text'];
    $userName = \Drupal::currentUser()->getDisplayName();
    return [
      '#theme' => 'welcome_template',
      '#username' => $userName,
      '#text' => $text,
      '#image' => $image,
    ];
  }
}
