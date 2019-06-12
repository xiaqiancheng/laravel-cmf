<?php

namespace XADMIN\LaravelCmf\Tests\Unit;

use Illuminate\Support\Facades\Config;
use XADMIN\LaravelCmf\Facades\LaravelCmf;
use XADMIN\LaravelCmf\Tests\TestCase;

class LaravelCmfTest extends TestCase
{
    /**
     * Dimmers returns collection of widgets.
     *
     * This test will make sure that the dimmers method will give us a
     * collection of the configured widgets.
     */
    public function testDimmersReturnsCollectionOfConfiguredWidgets()
    {
        Config::set('laravel-cmf.dashboard.widgets', [
            'XADMIN\\LaravelCmf\\Tests\\Stubs\\Widgets\\AccessibleDimmer',
            'XADMIN\\LaravelCmf\\Tests\\Stubs\\Widgets\\AccessibleDimmer',
        ]);

        $dimmers = LaravelCmf::dimmers();

        $this->assertEquals(2, $dimmers->count());
    }

    /**
     * Dimmers returns collection of widgets which should be displayed.
     *
     * This test will make sure that the dimmers method will give us a
     * collection of the configured widgets which also should be displayed.
     */
    public function testDimmersReturnsCollectionOfConfiguredWidgetsWhichShouldBeDisplayed()
    {
        Config::set('laravel-cmf.dashboard.widgets', [
            'XADMIN\\LaravelCmf\\Tests\\Stubs\\Widgets\\AccessibleDimmer',
            'XADMIN\\LaravelCmf\\Tests\\Stubs\\Widgets\\InAccessibleDimmer',
            'XADMIN\\LaravelCmf\\Tests\\Stubs\\Widgets\\InAccessibleDimmer',
        ]);

        $dimmers = LaravelCmf::dimmers();

        $this->assertEquals(1, $dimmers->count());
    }
}
