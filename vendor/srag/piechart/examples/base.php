<?php

use ILIAS\Data\Color;
use srag\PieChart\SrLpReport\Implementation\Factory;
use srag\PieChart\SrLpReport\Implementation\PieChartItem;

/**
 * @return string
 */
function base() : string
{
    global $DIC;

    $c = (new Factory())/*$DIC->ui()->factory()->chart()*/ ->pieChart([
        new PieChartItem("In Progress", 1, new Color(255, 255, 0)),
        new PieChartItem("Done", 2, new Color(0, 255, 0))
    ]);

    return $DIC->ui()->renderer()->render($c);
}
