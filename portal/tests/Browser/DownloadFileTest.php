<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DownloadFileTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    public function testExample(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://nhattruyenmin.com/truyen-tranh/fantasista-ban-vip-9782')
                    ->pause(1000);
            $output = $browser->script("var list = [];for (a = 0;a < $('#nt_listchapter > nav > ul > li > div.col-xs-5.chapter > a').length + 2; a++) { list.push( $( '#nt_listchapter > nav > ul > li:nth-child(' + a + ') > div.col-xs-5.chapter > a').attr('href') ); }listNew = list.filter(function (x) { return x != undefined;}); return listNew;");
            foreach ($output as $item){
                var_dump($item);
            }
        });
    }
}
