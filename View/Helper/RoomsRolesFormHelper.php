<?php
/**
 * RoomsRolesFormHelper
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppHelper', 'View/Helper');

/**
 * RoomsRolesFormHelper
 *
 * @package NetCommons\Rooms\View\Helper
 */
class RoomsRolesFormHelper extends AppHelper {

/**
 * 使用するヘルパー
 *
 * @var array
 */
	public $helpers = array(
		'NetCommons.NetCommonsForm',
		'NetCommons.NetCommonsHtml',
	);

/**
 * Before render callback. beforeRender is called before the view file is rendered.
 *
 * Overridden in subclasses.
 *
 * @param string $viewFile The view file that is going to be rendered
 * @return void
 */
	public function beforeRender($viewFile) {
		$this->NetCommonsHtml->script(array(
			'/rooms/js/role_permissions.js',
			'/rooms/js/room_role_permissions.js'
		));
		parent::beforeRender($viewFile);
	}

/**
 * Outputs room roles radio
 *
 * @param string $fieldName Name attribute of the RADIO
 * @param array $attributes The HTML attributes of the select element.
 * @return string Formatted RADIO element
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#options-for-select-checkbox-and-radio-inputs
 */
	public function checkboxRoomRoles($fieldName, $attributes = array()) {
		list($model, $permission) = explode('.', $fieldName);

		$html = '';

		$html .= '<div class="form-group">';

		//ラベル
		if (isset($attributes['label'])) {
			$html .= $this->NetCommonsForm->label($fieldName, $attributes['label']);
		}
		$attributes = Hash::remove($attributes, 'label');

		$initialize = NetCommonsAppController::camelizeKeyRecursive(
			array('roles' => $this->_View->viewVars['roles'])
		);
		$html .= '<div ng-controller="RoomRolePermissions" ' .
					'ng-init="RolePermission.initialize(' .
						h(json_encode($initialize, JSON_FORCE_OBJECT)) .
					')' .
				'">';

		//権限のチェックボックス
		$html .= '<div class="form-inline">';
		foreach ($this->_View->request->data[$model][$permission] as $roleKey => $role) {
			if (! $role['value'] && $role['fixed']) {
				continue;
			}

			$html .= $this->NetCommonsForm->hidden($fieldName . '.' . $roleKey . '.id');

			$options = Hash::merge(array(
				'div' => array('class' => 'checkbox checkbox-inline'),
				'disabled' => (bool)$role['fixed'],
			), $attributes);
			if (! $options['disabled']) {
				$options['ng-click'] = 'RolePermission.clickRole(' . '$event, \'' .
						$model . '\', \'' . $permission . '\', \'' . Inflector::variable($roleKey) . '\')';
			}

			$options['label'] = $this->_View->request->data['Role'][$roleKey]['name'];
			$html .= $this->NetCommonsForm->checkbox($fieldName . '.' . $roleKey . '.value', $options);
		}

		$html .= '</div>';

		if (Hash::get($attributes, 'help')) {
			$html .= $this->NetCommonsForm->help(Hash::get($attributes, 'help'));
		}
		$html .= '</div>';

		$html .= '</div>';

		return $html;
	}

/**
 * ルーム内の役割のデフォルト値のSELECTタグ
 *
 * @param string $fieldName フィールド名
 * @param array $attributes HTML属性オプション
 * @return string HTMLタグ
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#options-for-select-checkbox-and-radio-inputs
 */
	public function selectDefaultRoomRoles($fieldName, $attributes = array()) {
		//Option
		$defaultRoles = Hash::merge(
			array(' ' => $this->_View->viewVars['defaultRoles']),
			Hash::get($attributes, 'options', array())
		);
		$attributes = Hash::remove($attributes, 'options');

		//OptionのFormat
		$optionFormat = Hash::get($attributes, 'optionFormat', '%s');
		$attributes = Hash::remove($attributes, 'optionFormat');

		//OptionのFormat変換
		$flatten = Hash::flatten($defaultRoles);
		foreach ($flatten as $key => $value) {
			$flatten[$key] = sprintf($optionFormat, $value);
		}
		$defaultRoles = Hash::expand($flatten);

		$html = '';

		if (isset($attributes['label'])) {
			if (is_array($attributes['label'])) {
				$label = $attributes['label']['label'];
				unset($attributes['label']['label']);

				$html .= $this->NetCommonsForm->label($fieldName, $label, $attributes['label']) . ' ';
			} else {
				$html .= $this->NetCommonsForm->label($fieldName, $attributes['label']) . ' ';
			}
			unset($attributes['label']);
		}
		$attributes = Hash::merge(array(
			'type' => 'select',
			'class' => 'form-control',
			'empty' => false
		), $attributes);
		$html .= $this->NetCommonsForm->select($fieldName, $defaultRoles, $attributes);

		return $html;
	}

}
