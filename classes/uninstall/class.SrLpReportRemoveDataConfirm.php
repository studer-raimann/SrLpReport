<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use srag\Plugins\SrLpReport\Utils\SrLpReportTrait;
use srag\RemovePluginDataConfirm\SrLpReport\AbstractRemovePluginDataConfirm;

/**
 * Class SrLpReportRemoveDataConfirm
 *
 * @author            studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy SrLpReportRemoveDataConfirm: ilUIPluginRouterGUI
 */
class SrLpReportRemoveDataConfirm extends AbstractRemovePluginDataConfirm {

	use SrLpReportTrait;
	const PLUGIN_CLASS_NAME = ilSrLpReportPlugin::class;
}
