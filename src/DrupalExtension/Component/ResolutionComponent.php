<?php

declare(strict_types = 1);

namespace NuvoleWeb\Drupal\DrupalExtension\Component;

class ResolutionComponent {

  /**
   * @var int
   */
  protected $width = 0;

  public function getWidth(): int {
    return $this->width;
  }

  /**
   * @return $this
   */
  public function setWidth(int $width) {
    $this->width = $width;

    return $this;
  }

  /**
   * @var int
   */
  protected $height = 0;

  public function getHeight(): int {
    return $this->height;
  }

  /**
   * @return $this
   */
  public function setHeight(int $height) {
    $this->height = $height;

    return $this;
  }

}
