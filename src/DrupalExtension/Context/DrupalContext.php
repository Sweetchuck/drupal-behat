<?php

namespace NuvoleWeb\Drupal\DrupalExtension\Context;

use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Exception\ElementNotFoundException;

/**
 * Extends Drupal Extension DrupalContext class.
 *
 * Load this context instead of Drupal\DrupalExtension\Context\DrupalContext.
 *
 * @package NuvoleWeb\Drupal\DrupalExtension\Context
 */
class DrupalContext extends RawDrupalContext {

  /**
   * Assert access denied page.
   *
   * @Then I should get an access denied error
   */
  public function assertAccessDenied() {
    $this->assertSession()->statusCodeEquals(403);
  }

  /**
   * Pause execution for given number of seconds.
   *
   * @Then I wait :seconds seconds
   */
  public function iWaitSeconds($seconds) {
    sleep((int) $seconds);
  }

  /**
   * Creates content by filling specified form fields via the UI.
   *
   * Use as follow:
   *
   *  | Title    | Author     | Label | of the field      |
   *  | My title | Joe Editor | 1     | 2014-10-17 8:00am |
   *  | ...      | ...        | ...   | ...               |
   *
   * @Given I create :type content:
   */
  public function manuallyCreateNodes($type, TableNode $nodesTable) {
    $type = $this->getCore()->convertLabelToNodeTypeId($type);

    foreach ($nodesTable->getHash() as $nodeHash) {
      $this->getSession()->visit($this->locatePath("/node/add/$type"));
      $element = $this->getSession()->getPage();
      // Fill in the form.
      foreach ($nodeHash as $field => $value) {
        $element->fillField($field, $value);
      }
      $submit = $element->findButton($this->getDrupalText('node_submit_label'));
      if (empty($submit)) {
        throw new \Exception(sprintf("No submit button at %s", $this->getSession()->getCurrentUrl()));
      }
      // Submit the form.
      $submit->click();
    }

  }

  /**
   * Submit node form using "node_submit_label" value.
   *
   * @When I submit the content form
   */
  public function submitContentForm() {
    $locator = $this->getDrupalText('node_submit_label');

    $driver = $this->getSession();

    $button = $driver
      ->getPage()
      ->findButton($locator);

    if (!$button) {
      throw new ElementNotFoundException(
        $driver,
        'input',
        'named',
        $locator
      );
    }

    $elementId = $button->getAttribute('id');
    $elementIdSafe = var_export($elementId, TRUE);
    $function = "(function () {document.getElementById($elementIdSafe).scrollIntoView(false); })();";
    try {
      $driver->executeScript($function);
    }
    catch (\Exception $e) {
      // Do nothing.
    }

    $button->press();
  }

  /**
   * Assert string in HTTP response header.
   *
   * @Then I should see in the header :header::value
   */
  public function iShouldSeeInTheHeader($header, $value) {
    $headers = $this->getSession()->getResponseHeaders();
    if ($headers[$header] != $value) {
      throw new \Exception(sprintf("Did not see %s with value %s.", $header, $value));
    }
  }

  /**
   * Invalidate cache tags.
   *
   * Usage:
   *
   * Given I invalidate the following cache tags:
   *   | tag_one |
   *   | tag_two |
   *
   * @Given I invalidate the following cache tags:
   */
  public function invalidateCacheTags(TableNode $table) {
    $tags = [];
    foreach ($table->getRows() as $row) {
      $tags[] = $row[0];
    }
    $this->getCore()->invalidateCacheTags($tags);
  }

}
