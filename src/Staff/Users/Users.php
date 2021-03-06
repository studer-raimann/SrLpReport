<?php

namespace srag\Plugins\SrLpReport\Staff\Users;

use Closure;
use ilMStListCourse as ilMStListCourse54;
use ILIAS\MyStaff\ListCourses\ilMStListCourse;
use ilMStListUser as ilMStListUser54;
use ILIAS\MyStaff\ListUsers\ilMStListUser;
use ilMStListUsers as ilMStListUsers54;
use ILIAS\MyStaff\ListUsers\ilMStListUsers;
use ilMStShowUserCourses as ilMStShowUserCourses54;
use ILIAS\MyStaff\Courses\ShowUser\ilMStShowUserCourses;
use ilMyStaffAccess as ilMyStaffAccess54;
use ILIAS\MyStaff\ilMyStaffAccess;
use ilObjOrgUnitTree;
use ilOrgUnitOperation;
use ilOrgUnitPathStorage;
use ilSrLpReportPlugin;
use ilSrLpReportUIHookGUI;
use ilUIPluginRouterGUI;
use ilUserSearchOptions;
use srag\DIC\SrLpReport\DICTrait;
use srag\Plugins\SrLpReport\Report\Reports;
use srag\Plugins\SrLpReport\Staff\StaffGUI;
use srag\Plugins\SrLpReport\Staff\User\UserStaffGUI;
use srag\Plugins\SrLpReport\Utils\SrLpReportTrait;

/**
 * Class Users
 *
 * @package srag\Plugins\SrLpReport\Staff\Users
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Users
{

    use DICTrait;
    use SrLpReportTrait;
    const PLUGIN_CLASS_NAME = ilSrLpReportPlugin::class;
    /**
     * @var self
     */
    protected static $instance = null;


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * Users constructor
     */
    private function __construct()
    {

    }


    /**
     * @return array
     */
    public function getColumns() : array
    {
        return ilUserSearchOptions::getSelectableColumnInfo();
    }


    /**
     * @param int    $usr_id
     * @param array  $filter
     * @param string $order
     * @param string $order_direction
     * @param int    $limit_start
     * @param int    $limit_end
     *
     * @return array
     */
    public function getData(int $usr_id, array $filter, string $order, string $order_direction, int $limit_start, int $limit_end) : array
    {
        $data = [];

        $users = self::access()->getUsersForUser($usr_id);

        $filter['activation'] = "active";

        $options = [
            "filters" => $filter,
            "limit"   => [],
            "count"   => true,
            "sort"    => [
                "field"     => $order,
                "direction" => $order_direction
            ]
        ];

        if (self::version()->is6()) {
            $data["max_count"] = (new ilMStListUsers(self::dic()->dic()))->getData($users, $options);
        } else {
        $data["max_count"] = ilMStListUsers54::getData($users, $options);
        }

        $options["limit"] = [
            "start" => $limit_start,
            "end"   => $limit_end
        ];
        $options["count"] = false;

        //TODO Performance Killer!
        $data["data"] = array_map(
        /**
         * @param ilMStListUser|ilMStListUser54 $user
         */
            function (/*ilMStListUser*/ $user) : array {
            $vars = Closure::bind(function () : array {
                $vars = get_object_vars($this);

                $vars["usr_obj"] = $this->returnIlUserObj();

                return $vars;
            }, $user, self::version()->is6() ? ilMStListUser::class : ilMStListUser54::class)();

            $vars["interests_general"] = $vars["usr_obj"]->getGeneralInterestsAsText();

            $vars["interests_help_offered"] = $vars["usr_obj"]->getOfferingHelpAsText();

            $users = self::access()->getUsersForUserOperationAndContext(self::dic()->user()
                ->getId(), ilOrgUnitOperation::OP_ACCESS_ENROLMENTS, ilSrLpReportUIHookGUI::TYPE_CRS);
            $options = [
                "filters" => [
                    "usr_id" => $vars["usr_id"],
                ]
            ];
            $vars["learning_progress_courses"] = array_map(function (
                /**
                 * @var ilMStListCourse|ilMStListCourse54 $course
                 */
                /*ilMStListCourse*/ $course) : int {
                return self::dic()->objDataCache()->lookupObjId($course->getCrsRefId());
            }, self::version()->is6() ? (new ilMStShowUserCourses(self::dic()->dic()))->getData($users, $options) : ilMStShowUserCourses54::getData($users, $options));

            return $vars;
        },  self::version()->is6() ? (new ilMStListUsers(self::dic()->dic()))->getData($users, $options) : ilMStListUsers54::getData($users, $options));

        return $data;
    }


    /**
     * @return array
     */
    public function getOrgUnits() : array
    {
        $where = ilOrgUnitPathStorage::orderBy("path");

        $paths = $where->getArray("ref_id", "path");

        return $paths;
    }


    /**
     * @return array
     */
    public function getActionsArray() : array
    {
        return [
            self::dic()->ui()->factory()->link()->standard(self::dic()->language()->txt("courses"), $this->getUserCoursesLink(self::reports()
                ->getUsrId()))
        ];
    }


    /**
     * @param int $usr_id
     *
     * @return string
     */
    public function getUserCoursesLink(int $usr_id) : string
    {
        self::dic()->ctrl()->setParameterByClass(UserStaffGUI::class, Reports::GET_PARAM_USR_ID, $usr_id);

        return self::dic()->ctrl()->getLinkTargetByClass([
            ilUIPluginRouterGUI::class,
            StaffGUI::class,
            UserStaffGUI::class
        ]);
    }


    /**
     * @param int $org_unit_id
     *
     * @return string
     */
    public function getOrgUnitFilterLink(int $org_unit_id) : string
    {
        self::dic()->ctrl()->setParameterByClass(UsersStaffGUI::class, Reports::GET_PARAM_ORG_UNIT_ID, $org_unit_id);

        return self::dic()->ctrl()->getLinkTargetByClass([
            ilUIPluginRouterGUI::class,
            StaffGUI::class,
            UsersStaffGUI::class
        ], UsersStaffGUI::CMD_SET_ORG_UNIT_FILTER);
    }
}
