<?php

namespace Drupal\purge_ui\Tests;

/**
 * Tests \Drupal\purge_ui\Controller\DashboardController::buildDiagnosticReport.
 *
 * @group purge_ui
 */
class DashboardDiagnosticsTest extends DashboardTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = [
    'purge_check_test',
    'purge_check_error_test',
    'purge_check_warning_test',
  ];

  /**
   * Test the visual status report.
   *
   * @see \Drupal\purge_ui\Controller\DashboardController::buildDiagnosticReport
   */
  public function testDiagnosticReport() {
    $this->drupalLogin($this->adminUser);
    $this->drupalGet($this->route);
    $this->assertRaw('purge-ui-diagnostic-report');
    $this->assertRaw('purge-ui-diagnostic-report__entry--warning');
    $this->assertRaw('purge-ui-diagnostic-report__entry--error');
    $this->assertRaw('purge-ui-diagnostic-report__status-title');
    $this->assertRaw('purge-ui-diagnostic-report__entry__value');
    $this->assertRaw('purge-ui-diagnostic-report__entry__description');
    $this->assertRaw('open="open"');
    $this->assertText('Status');
    $this->assertText('Capacity');
    $this->assertText('Queuers');
    $this->assertText('Always a warning');
    $this->assertText('Always informational');
    $this->assertText('Always ok');
    $this->assertText('Always an error');
  }

}
