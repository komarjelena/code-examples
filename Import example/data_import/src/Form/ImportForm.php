<?php

namespace Drupal\data_import\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\file\Entity\File as File;
use Drupal\taxonomy\Entity\Term as Term;
use Drupal\Core\Url;

use Drupal\data_import\DataImportStorage;

/**
 * Implements an example form.
 */
class ImportForm extends FormBase
{

  private $mandatoryFields = [
    0 => 'item_name',
    1 => 'product_type',
    2 => 'manufacturer_brand',
    3 => 'collection_name',
    4 => 'sku',
    5 => 'design_style',
    6 => 'width',
    7 => 'height',
    8 => 'depth',
    9 => 'image_filename_or_url_1',
//    0,1,2,3,4,5,6,7,8,9
  ];


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'upload_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form = [];

    $form['file_upload_fieldset'] = array(
      '#type' => 'fieldset',
      '#title' => t('Upload your furniture list here:'),
      '#attributes' => [
        'class' => ['container-inline'],
      ],
      '#collapsible' => TRUE,
    );


    $form['file_upload_fieldset']['import_type'] = array(
      '#title' => t('Choose import type'),
      '#type' => 'select',
      '#options' => [
        0 => 'Full',
        1 => 'Partial',
      ],
      '#description' => t('<b>Partial import </b> will increasingly update current state. </br> <b>Full import</b> will delete items that are not in file and belongs to chosen manufacturer and then run the import process'),
      '#required' => TRUE,
    );

    $options = $this->loadManufacturers();

    $form['file_upload_fieldset']['manufacturer'] = array(
      '#title' => t('Choose manufecturer'),
      '#type' => 'select',
      '#description' => t('Choose matching manufacturer from sheet.'),
      '#required' => TRUE,
      '#options' => $options,
    );

    $form['file_upload_fieldset']['add_manfacturer'] = array(
      '#title' => t('If you can\'t find your manufacturer. Click here to add new one:'),
      '#type' => 'link',
      '#url' => Url::fromRoute('entity.taxonomy_term.add_form', [
        'taxonomy_vocabulary' => 'manufacturer'
      ],   [
        'query' => ['destination' => 'admin/config/development/import'],
        'absolute' => TRUE,
      ]),
    );


    $form['file_upload_fieldset']['upload_file'] = array(
      '#title' => $this->t('Choose TSV file'),
      '#type' => 'managed_file',
      '#description' => t('The uploaded csv file will be uploaded, extracted and temporarily stored.This form accepts onyl .csv files.'),
      '#upload_location' => 'public://import/',
      '#required' => true,
      '#autoupload' => TRUE,
      '#upload_validators' => ['file_validate_extensions' => ['tsv']],
      '#required' => true,
    );

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('TSV Upload'),
      '#button_type' => 'primary',
    );

    //$form['#theme'] = 'import_form';

    return $form;

  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $import_type = $form_state->getValue('import_type');
    $manufacturer_tid = $form_state->getValue('manufacturer');

    $term = Term::load($manufacturer_tid);

    $name = $term->getName();

    $manufacturer = \Drupal::entityTypeManager()
      ->getListBuilder('taxonomy_term')
      ->getStorage()
      ->loadByProperties(['tid' => $manufacturer_tid]);

    $fid = current($form_state->getValue('upload_file'));


    $entity_type = \Drupal::entityTypeManager()
      ->getListBuilder('node')
      ->getStorage()
      ->loadByProperties(['type' => 'furniture_item']);


    // Load the object of the file by its fid.
    $csv_file = File::load($fid);
    $csv_file = file($csv_file->getFileUri());


    //Clean - up
    foreach ($csv_file as $line_no => $line) {
      if (
        strpos(strtolower($line), 'mandatory fields') !== false ||
        strpos(strtolower($line), '256 char') !== false ||
        empty(str_getcsv($line, $delimiter = "\t")[0])
      ) {
        unset($csv_file[$line_no]);
      }
    }

    //Reset line numbers
    $csv_file = array_values($csv_file);

    $header = [];

    $operations = [];

    foreach ($csv_file as $line_no => &$line) {

      if (empty($header)) {
        // Set the header and unset the first line
        $header = str_getcsv($line, $delimiter = "\t");
        unset($csv_file[$line_no]);

      } else {

        // Parse the CSV
        $line = str_getcsv($line, $delimiter = "\t");

        // Compare array item count
        if (count($line) == count($header)) {

          // Check for mandatory fields
          foreach ($this->mandatoryFields as $field => $label) {

            if (empty($line[$field])) {


              drupal_set_message("" . json_encode($line) . "  | Mandatory field in column number $line[$field] on  $line_no line number is submited file.", 'error');

              $user = \Drupal::currentUser();

              data_import_log(
                $user->id(),
                $line[4],
                "Pre Import Check | Missing Mandatory Field",
                "Cancelled",
                "Mandatory field :field_name is missing for submitted file on line :line_no <pre>:data</pre>",
                [':field_name' => $label, ':line_no' => $line_no, ':data' => json_encode($line, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)]
              );

              unset($csv_file[$line_no]);
            }

          }

          if ($line[2] === $name) {

            if ($import_type === "0") {
              _data_import_delete_node($manufacturer_tid, $context);
            }

            // If all is well, create operations for the batch
            $operations[] = [
              '_data_import_action',
              [$line, $line_no],
            ];

          } else {
            drupal_set_message(t('Manufacturer doesn\'t match.'), 'error');

            $user = \Drupal::currentUser();

            DataImportStorage::insert(
              $user->id(),
              $line[4],
              "Pre Import Check | Manufacturer does not match",
              "Cancelled",
              "Manufacturer does not match for :man_name",
              [':man_name' => $name]
            );
            return null;
          }

        } else {
          drupal_set_message("Corrupted line no'. $line_no ", 'error');
        }
      }
    }

    $batch = [
      'operations' => $operations,
      'title' => 'Import processing',
      'finished' => '\Drupal\Form\ImportForm::finish',// Last function to call.
      'init_message' => t('Importing...it may take a few minutes'),
      'progress_message' => t('Processed @current out of @total.'),
      'error_message' => t('Import feeds has encountered an error.'),
    ];

    batch_set($batch);

  }

  public function finish($success, $operations) {

    if ($success) {
      $message = 'Performance plan added.';
    } else {
      $message = t('Encountered an error during bulk performance plan creation.');
    }
    drupal_set_message($message);
  }


  public function loadManufacturers() {
    $vid = 'manufacturer';
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
    foreach ($terms as $term) {
      $term_data[$term->tid] = $term->name;

    }
    return $term_data;
  }
}


