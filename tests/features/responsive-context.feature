@javascript
Feature: Responsive Context
  In order to be able to use our custom ResponsiveContext class
  As a developer
  I want to be sure that all the steps defined within are working correctly.

  Scenario Outline: Test responsive context steps.
    Given I am on "/"
    And I view the site on a "<device>" device
    Then the browser window width should be <width>
    And the browser window height should be <height>
    Examples:
      | device           | width | height |
      | mobile_landscape | 640   | 360    |
      | tablet_landscape | 1024  | 768    |
      | laptop           | 1280  | 800    |

  Scenario: Test responsive context steps.
    Given I am on "/"
    And the window size is 640×360
    Then the browser window width should be 640
    And the browser window height should be 360

  Scenario: Test responsive context steps.
    Given I am on "/"
    And the window size is 1024x768
    Then the browser window width should be 1024
    And the browser window height should be 768
