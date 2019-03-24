<?php

/*
 * This file is part of a XenForo add-on.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jrahmy\LatestActivity\Widget;

use XF\Entity\NewsFeed;

/**
 * A widget for displaying the latest activity feed.
 */
class LatestActivity extends \XF\Widget\AbstractWidget
{
    /**
     * @var array
     */
    protected $defaultOptions = [
        'limit' => 5
    ];

    /**
     * @return \XF\Widget\WidgetRenderer
     */
    public function render()
    {
        if (!$this->app->options()->enableNewsFeed) {
            return null;
        }

        /** @var \XF\Repository\NewsFeed $newsFeedRepo */
        $newsFeedRepo = $this->repository('XF:NewsFeed');
        $limit = $this->options['limit'];

        $finder = $newsFeedRepo->findNewsFeed();

        /** @var \XF\Mvc\Entity\AbstractCollection $items */
        $items = $finder->fetch(max(($limit * 2), 10));
        $newsFeedRepo->addContentToNewsFeedItems($items);

        $items = $items->filter(function (NewsFeed $item) {
            $visitor = \XF::visitor();

            /** @var \XF\Entity\NewsFeed $item */
            return ($item->canView() && !$visitor->isIgnoring($item->user_id));
        });
        $items = $items->filterViewable();
        $items = $items->slice(0, $limit);

        $firstItem = $items->first();
        $newestItemId = $firstItem ? $firstItem->news_feed_id : 0;

        $viewParams = [
            'newsFeedItems' => $items,
            'newestItemId'  => $newestItemId,
            'limit'         => $limit
        ];
        return $this->renderer('j_la_widget_latest_activity', $viewParams);
    }

    /**
     * @param \XF\Http\Request $request
     * @param array            $options
     * @param string           $error
     *
     * @return bool
     */
    public function verifyOptions(
        \XF\Http\Request $request,
        array &$options,
        &$error = null
    ) {
        $options = $request->filter([
            'limit' => 'uint'
        ]);

        $options['limit'] = ($options['limit'] > 0) ? $options['limit'] : 1;

        return true;
    }
}
