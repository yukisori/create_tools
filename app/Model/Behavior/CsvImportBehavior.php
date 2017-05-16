<?php
/**
 * Copyright 2009 - 2013, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009 - 2013, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Utils Plugin
 *
 * Utils Csv Import Behavior
 *
 * @package utils
 * @subpackage utils.models.behaviors
 */
class CsvImportBehavior extends ModelBehavior {

	public $settings = array();
	public $errors = array();
	protected $_subscribers = array();

	protected function _getCSVLine(Model &$Model, SplFileObject $handle) {
		if ($handle->eof()) {
			return false;
		}
		return $handle->fgetcsv(
			$this->settings[$Model->alias]['delimiter'],
			$this->settings[$Model->alias]['enclosure']
		);
	}

	protected function _getHeader(Model &$Model, SplFileObject $handle) {
		if ($this->settings[$Model->alias]['hasHeader'] === true) {
			$header = $this->_getCSVLine($Model, $handle);
		} else {
			$header = array_keys($Model->schema());
		}
		return $header;
	}

	public function setup(Model $Model, $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = array(
				'delimiter' => ';',
				'enclosure' => '"',
				'hasHeader' => true
			);
		}
		$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], $settings);
	}
	public function importCSV(Model &$Model, $file, $fixed = array(), $returnSaved = false) {
		$handle = new SplFileObject($file, 'rb');
		$header = $this->_getHeader($Model, $handle);
		$db = $Model->getDataSource();
		$db->begin($Model);
		$saved = array();
		$i = 0;
		while (($row = $this->_getCSVLine($Model, $handle)) !== false) {
			$data = array();
			foreach ($header as $k => $col) {
				// get the data field from Model.field
				if (strpos($col, '.') !== false) {
					$keys = explode('.', $col);
					if (isset($keys[2])) {
						$data[$keys[0]][$keys[1]][$keys[2]]= (isset($row[$k])) ? $row[$k] : '';
					} else {
						$data[$keys[0]][$keys[1]]= (isset($row[$k])) ? $row[$k] : '';
					}
				} else {
					$data[$Model->alias][$col]= (isset($row[$k])) ? $row[$k] : '';
				}
			}

			$data = Set::merge($data, $fixed);
			$Model->create();
			$Model->id = isset($data[$Model->alias][$Model->primaryKey]) ? $data[$Model->alias][$Model->primaryKey] : false;

			//callback
			if (method_exists($Model, 'beforeImport')) {
				$data = $Model->beforeImport($data);
			}

			$error = false;
			$Model->set($data);
			if (!$Model->validates()) {
				$this->errors[$Model->alias][$i]['validation'] = $Model->validationErrors;
				$error = true;
				$this->_notify($Model, 'onImportError', $this->errors[$Model->alias][$i]);
			}

			// save the row
			if (!$error && !$Model->saveAll($data, array('validate' => false,'atomic' => false))) {
				$this->errors[$Model->alias][$i]['save'] = sprintf(__d('utils', '%s for Row %d failed to save.'), $Model->alias, $i);
				$error = true;
				$this->_notify($Model, 'onImportError', $this->errors[$Model->alias][$i]);
			}

			if (!$error) {
				$this->_notify($Model, 'onImportRow', $data);
				if ($returnSaved) {
					$saved[] = $i;
				}
			}

			$i++;
		}

		$success = empty($this->errors);
		if (!$returnSaved && !$success) {
			$db->rollback($Model);
			return false;
		}

		$db->commit($Model);

		if ($returnSaved) {
			return $saved;
		}

		return true;
	}

	public function getImportErrors(Model &$Model) {
		if (empty($this->errors[$Model->alias])) {
			return array();
		}
		return $this->errors[$Model->alias];
	}

	public function attachImportListener(Model $Model, $listener) {
		$this->_subscribers[$Model->alias][] = $listener;
	}

	protected function _notify(Model $Model, $action, $data = null) {
		if (empty($this->_subscribers[$Model->alias])) {
			return;
		}
		foreach ($this->_subscribers[$Model->alias] as $object) {
			if (method_exists($object, $action)) {
				$object->{$action}($data);
			}
			if (is_callable($object)) {
				call_user_func($object, $action, $data);
			}
		}
	}
}
