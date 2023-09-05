<?php
/**
 * Matomo - free/libre analytics platform
 *
 * @link https://matomo.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\SegmentAuthorization;

//we are adding classes here from https://developer.matomo.org
use Piwik\Piwik;
use Piwik\Plugins\CoreAdminHome\Controller as CoreAdminController;

use Piwik\Settings\Setting;
use Piwik\Settings\FieldConfig;
use Piwik\Validators\NotEmpty;

class SystemSettings extends \Piwik\Settings\Plugin\SystemSettings
{
    /** we changed $metric to $segmentauthorization */
    public $segmentauthorization;

    protected function init()
    {
        // we make this feature only accessible to super users
        $isWritable = Piwik::hasUserSuperUserAccess() && CoreAdminController::isGeneralSettingsAdminEnabled();
        // we changed metric to segmentauthorization and createMetricSetting() to changeSegmentAuthorization
        $this->segmentauthorization = $this->changeSegmentAuthorization();
        // we make this feature only accessible to super users
        $this->segmentauthorization->setIsWritableByCurrentUser($isWritable);

    }
// we changed createMetricSetting() to changeSegmentAuthorization
    private function changeSegmentAuthorization()
    {
		// we changed makeSetting to makeSettingManagedInConfigOnly
        return $this->makeSettingManagedInConfigOnly(
        // we added a parameter to indicate which part of the config file needs to be changed
        "General",
        // we changed metric to adding_segment_requires_access which corresponds to the settings to change
        'adding_segment_requires_access',
         $default = 'admin',
         FieldConfig::TYPE_STRING, function (FieldConfig $field) {
            $field->title = 'Minimum authorization rights to create segments';
            $field->uiControl = FieldConfig::UI_CONTROL_SINGLE_SELECT;
            $field->availableValues = array('admin' => 'admin', 'superuser' => 'superuser', 'write' => 'write', 'view' => 'view');
            $field->description = 'Choose the minimum authorization level in order to create segments.';
            $field->validators[] = new NotEmpty();
        });
    }

}
