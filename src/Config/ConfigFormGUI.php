<?php

namespace srag\Plugins\SrLpReport\Config;

use ilCheckboxInputGUI;
use ilNumberInputGUI;
use ilSrLpReportConfigGUI;
use ilSrLpReportPlugin;
use srag\ActiveRecordConfig\SrLpReport\ActiveRecordConfigFormGUI;
use srag\CustomInputGUIs\SrLpReport\MultiSelectSearchNewInputGUI\MultiSelectSearchNewInputGUI;
use srag\Plugins\SrLpReport\Staff\CourseAdministration\CourseAdministrationStaffGUI;
use srag\Plugins\SrLpReport\Utils\SrLpReportTrait;

/**
 * Class ConfigFormGUI
 *
 * @package srag\Plugins\SrLpReport\Config
 *
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
		self::dic()->language()->loadLanguageModule("trac");
		self::dic()->language()->loadLanguageModule("notes");

		$this->fields = [
            Config::KEY_ENABLE_COURSES_VIEW => [
                self::PROPERTY_CLASS => ilCheckboxInputGUI::class,
                "setTitle"           => self::plugin()->translate("enable_view", self::LANG_MODULE, [self::dic()->language()->txt("courses")])
            ],
            Config::KEY_ENABLE_USERS_VIEW   => [
                self::PROPERTY_CLASS => ilCheckboxInputGUI::class,
                "setTitle"           => self::plugin()->translate("enable_view", self::LANG_MODULE, [self::dic()->language()->txt("users")])
            ],
            Config::KEY_ENABLE_COURSE_ADMINISTRATION => [
                self::PROPERTY_CLASS    => ilCheckboxInputGUI::class,
                "setTitle"              => self::plugin()->translate("enable_view", self::LANG_MODULE, [self::plugin()->translate("title", CourseAdministrationStaffGUI::LANG_MODULE)]),
                self::PROPERTY_SUBITEMS => [
                    Config::KEY_COURSE_ADMINISTRATION_COURSES => [
                        self::PROPERTY_CLASS   => MultiSelectSearchNewInputGUI::class,
                        self::PROPERTY_OPTIONS => self::ilias()->searchCourses(),
                        "setAjaxLink"          => self::dic()->ctrl()->getLinkTarget($this->parent, ilSrLpReportConfigGUI::CMD_GET_COURSES_AUTO_COMPLETE, "", true, false)
                    ],
                    Config::KEY_COURSE_ADMINISTRATION_MARK    => [
                        self::PROPERTY_CLASS => ilNumberInputGUI::class,
                        "setSuffix"          => $this->txt(CourseAdministrationStaffGUI::LANG_MODULE . "_mark_days")
                    ]
                ]
            ],
            Config::KEY_ENABLE_COMMENTS => [
            self::PROPERTY_CLASS => ilCheckboxInputGUI::class,
            "setTitle" => self::dic()->language()->txt("trac_learning_progress") . " " . self::dic()->language()->txt("notes_comments")
        ],
		];
	}
}
