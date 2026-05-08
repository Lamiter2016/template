<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Chrome\ChromeProcess;
use App\Models\Comic;
class CloneDataController extends Controller
{
    public function cloneCommic(){
        $linkComic= 'https://nettruyenco.vn/truyen-tranh/fantasista-ban-vip-3000018316';
        $nameComic = str_replace('https://nettruyenco.vn/truyen-tranh/','',$linkComic);
        $scriptListChap = "var list = [];for (a = 0;a < $('#nt_listchapter > nav > ul > li > div.col-xs-5.chapter > a').length + 2; a++) { list.push( $( '#nt_listchapter > nav > ul > li:nth-child(' + a + ') > div.col-xs-5.chapter > a').attr('href') ); }listNew = list.filter(function (x) { return x != undefined;}); return listNew;";
        $scriptImgChap= "var list = [];for (a = 0;a < $('#ctl00_divCenter > div > div.reading-detail.box_doc > .page-chapter').length + 2; a++) { list.push( $( '#page_'+ a +' > img').attr('src') ); }listNew = list.filter(function (x) { return x != undefined;}); return listNew;";
        $listChap = $this->initBrowser($linkComic, $scriptListChap);
        $chapNum = count($listChap[0]);
        // foreach ($listChap[0] as $chapLink ) {
        //     $chapNum--;
        //     dd($chapLink);
        //     //Comic::saveComic($nameComic, $chapNum, $chapLink);
        //     // $listImg = $this->initBrowser($chapLink, $scriptImgChap);
        //     // foreach ($listImg[0] as $linkImg ) {
        //     //     Comic::saveComic($nameComic, $chapNum, $linkImg);
        //     // }
        // }
        dd($listChap);
    }
    public function initBrowser($linkComic, $script){
        $process = (new ChromeProcess)->toProcess();
        //$process->start();
        $process->start(null, [
            'SystemRoot' => 'C:\\WINDOWS',
            'TEMP' => 'C:\\Users\\lamit\\AppData\\Local\\Temp',
        ]);
        $options = (new ChromeOptions)->addArguments(['--disable-gpu', '--headless']);
        //$options = (new ChromeOptions)->addArguments([]);
        //$options = new ChromeOptions;
        $options->setBinary("C:\Program Files\Google\Chrome\Application\chrome.exe");
        $capabilities = DesiredCapabilities::chrome()->setCapability(ChromeOptions::CAPABILITY, $options);
        $driver = retry(5, function () use($capabilities) {
            return RemoteWebDriver::create('http://localhost:9515', $capabilities);
        }, 50);
        $browser = new Browser($driver);
        $browser->visit($linkComic)
                ->pause(1000);
        $output = $browser->script($script);
        $browser->quit();
        $process->stop();
        return $output;
    }
    public function getListChap(){

    }

}
