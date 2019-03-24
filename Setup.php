<?php

/*
 * This file is part of a XenForo add-on.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jrahmy\LatestActivity;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

/**
 * Handles installation, upgrades, and uninstallation of the add-on.
 */
class Setup extends AbstractSetup
{
    use StepRunnerInstallTrait;
    use StepRunnerUpgradeTrait;
    use StepRunnerUninstallTrait;

    /**
     * Adds default widgets.
     */
    public function installStep1()
    {
        foreach ($this->getDefaultWidgetSetup() as $widgetKey => $widgetFn) {
            $widgetFn($widgetKey);
        }
    }

    /**
     * @return array
     */
    protected function getDefaultWidgetSetup()
    {
        return [
            'j_la_forum_overview_latest_activity' => function (
                $key,
                array $options = []
            ) {
                $this->createWidget($key, 'j_la_latest_activity', [
                    'positions' => [
                        'forum_list_sidebar'      => 45,
                        'forum_new_posts_sidebar' => 35
                    ],
                    'options' => $options
                ]);
            }
        ];
    }
}
