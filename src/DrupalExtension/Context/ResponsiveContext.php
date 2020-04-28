<?php

declare(strict_types = 1);

namespace NuvoleWeb\Drupal\DrupalExtension\Context;

use Behat\Mink\Exception\ExpectationException;
use NuvoleWeb\Drupal\DrupalExtension\Component\ResolutionComponent;

class ResponsiveContext extends RawMinkContext {

  /**
   * @var array
   */
  protected $defaultDevices = [
    'mobile_portrait' => [
      'width' => 360,
      'height' => 640,
    ],
    'mobile_landscape' => [
      'width' => 640,
      'height' => 360,
    ],
    'tablet_portrait' => [
      'width' => 768,
      'height' => 1024,
    ],
    'tablet_landscape' => [
      'width' => 1024,
      'height' => 768,
    ],
    'laptop' => [
      'width' => 1280,
      'height' => 800,
    ],
    'desktop' => [
      'width' => 2560,
      'height' => 1440,
    ],
  ];

  /**
   * @var \NuvoleWeb\Drupal\DrupalExtension\Component\ResolutionComponent[]
   */
  protected $devices = [];

  /**
   * @param array $devices
   *   List of devices.
   */
  public function __construct(array $devices = []) {
    foreach ($devices + $this->defaultDevices as $name => $size) {
      $this->devices[$name] = (new ResolutionComponent())
        ->setWidth($size['width'])
        ->setHeight($size['height']);
    }
  }

  /**
   * Resize browser window according to the specified device.
   *
   * @Given I view the site on a :device device
   */
  public function doBrowserWindowResizeToDevice(string $device, ?string $windowName = NULL) {
    assert(
      array_key_exists($device, $this->devices),
      "Device '{$device}' not found."
    );

    $this->doBrowserWindowResize(
        $this->devices[$device]->getWidth(),
        $this->devices[$device]->getHeight(),
        $windowName
    );
  }

  /**
   * @Given /^the window size is (?P<width>\d+)(x|×)(?P<height>\d+)$/
   */
  public function doBrowserWindowResize($width, $height, ?string $windowName = NULL) {
    $this->getSession()->resizeWindow(intval($width), intval($height), $windowName);
  }

  /**
   * @param string $width
   *   Expected browser window width in pixel.
   *
   * @Then /^the browser window width should be (?P<width>\d+)$/
   */
  public function assertBrowserWindowWidth($width) {
    $driver = $this->getSession();
    $actual = $driver->evaluateScript('return window.outerWidth;');
    if (intval($actual) !== intval($width)) {
      throw new ExpectationException(
        "Browser window width expected to be {$width} but it is {$actual} instead.",
        $driver
      );
    }
  }

  /**
   * @param string $height
   *   Expected browser window height in pixel.
   *
   * @Then /^the browser window height should be (?P<height>\d+)$/
   */
  public function assertBrowserWindowHeight($height) {
    $driver = $this->getSession();
    $actual = $driver->evaluateScript('return window.outerHeight;');
    if (intval($actual) !== intval($height)) {
      throw new ExpectationException(
        "Browser window height expected to be {$height} but it is {$actual} instead.",
        $driver
      );
    }
  }

}
