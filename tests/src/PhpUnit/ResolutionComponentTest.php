<?php

declare(strict_types = 1);

namespace NuvoleWeb\Drupal\Tests\PhpUnit;

use NuvoleWeb\Drupal\DrupalExtension\Component\ResolutionComponent;

/**
 * @covers \NuvoleWeb\Drupal\DrupalExtension\Component\ResolutionComponent
 */
class ResolutionComponentTest extends AbstractTest {

  public function testCreateFromExpression() {
    $resolution = (new ResolutionComponent())
      ->setWidth(42)
      ->setHeight(84);

    $this->assertSame(42, $resolution->getWidth());
    $this->assertSame(84, $resolution->getHeight());
  }

}
