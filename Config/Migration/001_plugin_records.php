<?php
/**
 * Add plugin migration
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * Add plugin migration
 *
 * @package NetCommons\PluginManager\Config\Migration
 */
class PluginRecords extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'plugin_records';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(),
		'down' => array(),
	);

/**
 * plugin data
 *
 * @var array $migration
 */
	public $records = array(
		'Plugin' => array(
			//日本語
			array(
				'language_id' => '2',
				'key' => 'rooms',
				'is_origin' => true,
				'is_translation' => true,
				'namespace' => 'netcommons/rooms',
				'name' => 'ルーム管理',
				'type' => 2,
				'default_action' => 'rooms/index/4',
				'default_setting_action' => '',
				'weight' => 2,
				'is_m17n' => null,
			),
			//英語
			array(
				'language_id' => '1',
				'key' => 'rooms',
				'is_origin' => false,
				'is_translation' => true,
				'namespace' => 'netcommons/rooms',
				'name' => 'Room Manager',
				'type' => 2,
				'default_action' => 'rooms/index/4',
				'default_setting_action' => '',
				'weight' => 2,
				'is_m17n' => null,
			),
		),
		'PluginsRole' => array(
			array(
				'role_key' => 'system_administrator',
				'plugin_key' => 'rooms',
			),
			array(
				'role_key' => 'administrator',
				'plugin_key' => 'rooms',
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		$this->loadModels([
			'Plugin' => 'PluginManager.Plugin',
		]);

		if ($direction === 'down') {
			$this->Plugin->uninstallPlugin($this->records['Plugin'][0]['key']);
			return true;
		}

		foreach ($this->records as $model => $records) {
			if (!$this->updateRecords($model, $records)) {
				return false;
			}
		}
		return true;
	}
}
