<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace logstore_xapi\admin;

use advanced_testcase;

/**
 * Unit tests for setting_cohort_selector.
 *
 * @package   logstore_xapi
 * @copyright 2025 David Pesce <david.pesce@exputo.com>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers    \logstore_xapi\admin\setting_cohort_selector
 */
final class setting_cohort_selector_test extends advanced_testcase {
    /**
     * Create a setting_cohort_selector instance for testing.
     *
     * @return setting_cohort_selector
     */
    private function make_setting(): setting_cohort_selector {
        return new setting_cohort_selector(
            'logstore_xapi/cohorts',
            'Include cohorts',
            '',
            ''
        );
    }

    /**
     * Create a visible cohort in the database and return its id.
     *
     * @param  string $name    Cohort name.
     * @param  int    $visible 1 = visible, 0 = hidden.
     * @return int Cohort id.
     */
    private function create_cohort(string $name, int $visible = 1): int {
        global $DB;
        return (int)$DB->insert_record('cohort', (object)[
            'name'              => $name,
            'idnumber'          => '',
            'description'       => '',
            'descriptionformat' => FORMAT_HTML,
            'contextid'         => \context_system::instance()->id,
            'visible'           => $visible,
            'timecreated'       => time(),
            'timemodified'      => time(),
            'component'         => '',
        ]);
    }

    /**
     * Writing valid cohort IDs persists them.
     *
     * @return void
     */
    public function test_write_setting_valid_ids(): void {
        $this->resetAfterTest();

        $id1 = $this->create_cohort('Alpha');
        $id2 = $this->create_cohort('Beta');

        $setting = $this->make_setting();
        $result = $setting->write_setting("$id1,$id2");

        $this->assertSame('', $result, 'write_setting should return empty string on success');

        $stored = get_config('logstore_xapi', 'cohorts');
        $storedids = array_map('intval', explode(',', $stored));
        sort($storedids);
        $this->assertSame([$id1, $id2], $storedids);
    }

    /**
     * Writing an empty string stores an empty string.
     *
     * @return void
     */
    public function test_write_setting_empty(): void {
        $this->resetAfterTest();

        $setting = $this->make_setting();
        $result = $setting->write_setting('');

        $this->assertSame('', $result);
        $this->assertSame('', get_config('logstore_xapi', 'cohorts'));
    }

    /**
     * IDs for non-existent cohorts are silently dropped.
     *
     * @return void
     */
    public function test_write_setting_invalid_ids_dropped(): void {
        $this->resetAfterTest();

        $id1 = $this->create_cohort('RealCohort');

        $setting = $this->make_setting();
        $result = $setting->write_setting("$id1,99999");

        $this->assertSame('', $result);

        $stored = get_config('logstore_xapi', 'cohorts');
        $this->assertSame((string)$id1, $stored);
    }

    /**
     * Invisible cohort IDs are dropped.
     *
     * @return void
     */
    public function test_write_setting_invisible_cohort_dropped(): void {
        $this->resetAfterTest();

        $visibleid   = $this->create_cohort('Visible', 1);
        $invisibleid = $this->create_cohort('Hidden', 0);

        $setting = $this->make_setting();
        $result = $setting->write_setting("$visibleid,$invisibleid");

        $this->assertSame('', $result);

        $stored = get_config('logstore_xapi', 'cohorts');
        $this->assertSame((string)$visibleid, $stored);
    }

    /**
     * When all submitted IDs are invalid, an empty string is stored.
     *
     * @return void
     */
    public function test_write_setting_all_invalid(): void {
        $this->resetAfterTest();

        $setting = $this->make_setting();
        $result = $setting->write_setting('99998,99999');

        $this->assertSame('', $result);
        $this->assertSame('', get_config('logstore_xapi', 'cohorts'));
    }

    /**
     * get_setting returns the raw stored value.
     *
     * @return void
     */
    public function test_get_setting_returns_stored_value(): void {
        $this->resetAfterTest();

        set_config('cohorts', '5,12', 'logstore_xapi');

        $setting = $this->make_setting();
        $this->assertSame('5,12', $setting->get_setting());
    }

    /**
     * get_setting returns null when nothing has been stored yet.
     *
     * @return void
     */
    public function test_get_setting_returns_null_when_unset(): void {
        $this->resetAfterTest();

        $setting = $this->make_setting();
        $this->assertEmpty($setting->get_setting());
    }
}
