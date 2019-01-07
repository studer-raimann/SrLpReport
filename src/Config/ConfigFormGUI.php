<?php

/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see https://github.com/ILIAS-eLearning/ILIAS/tree/trunk/docs/LICENSE */

namespace srag\Plugins\SrLpReport\Config;

use srag\Plugins\SrLpReport\Utils\SrLpReportTrait;
use ilSrLpReportPlugin;
use ilTextInputGUI;
use srag\ActiveRecordConfig\SrLpReport\ActiveRecordConfigFormGUI;

/**
 * Class ConfigFormGUI
 *
 * Generated by srag\PluginGenerator v0.9.10
 *
 * @package srag\Plugins\SrLpReport\Config
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class ConfigFormGUI extends ActiveRecordConfigFormGUI {

	use SrLpReportTrait;
	const PLUGIN_CLASS_NAME = ilSrLpReportPlugin::class;
	const CONFIG_CLASS_NAME = Config::class;


	/**
	 * @inheritdoc
	 */
	protected function initFields()/*: void*/ {
		$this->fields = [
			Config::KEY_ROLE_OBJ_ID => [
				self::PROPERTY_CLASS => ilTextInputGUI::class,
				self::PROPERTY_REQUIRED => true
			]
		];
		// TODO: Implement ConfigFormGUI
	}
}
