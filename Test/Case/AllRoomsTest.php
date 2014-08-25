<?php
/**
 * Rooms All Test Case
 *
 * @author      Noriko Arai <arai@nii.ac.jp>
 * @author      Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link        http://www.netcommons.org NetCommons Project
 * @license     http://www.netcommons.org/license.txt NetCommons License
 * @copyright   Copyright 2014, NetCommons Project
 * @package     app.Plugin.Rooms.Model
 */

/**
 * Rooms All Test Case
 *
 * @author        Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package       app.Plugin.Rooms.Test.Case
 * @codeCoverageIgnore
 */
class AllRoomsTest extends CakeTestSuite {

/**
 * All test suite
 *
 * @author   Shohei Nakajima <nakajimashouhei@gmail.com>
 * @return   CakeTestSuite
 */
	public static function suite() {
		$plugin = preg_replace('/^All([\w]+)Test$/', '$1', __CLASS__);
		$suite = new CakeTestSuite(sprintf('All %s Plugin tests', $plugin));
		$suite->addTestDirectoryRecursive(CakePlugin::path($plugin) . 'Test' . DS . 'Case');
		return $suite;
	}
}