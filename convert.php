<?php

foreach (glob(__DIR__ ."/tests/json/*.json") as $filename) {
	$json = json_decode(file_get_contents($filename));
	$name = str_replace('.json', '', array_pop(explode('/', $filename)));
	if(!file_exists(__DIR__ . '/tests/php/'. $name .'.php')) {
		saveFile($json, $name);
	}
}

function saveFile($json, $name) {

	$start_url = $json->steps[0]->url;
	unset($json->steps[0]);
	$function = array();

	foreach($json->steps as $step) {
		switch($step->type) {
			case 'clickElement':
				switch($step->locator->type) {
					case 'link text':
						$function[] = '$this->byLinkText("'. $step->locator->value .'")->click();';
						break;
					case 'id':
						$function[] = '$this->byId("' . $step->locator->value .'")->click();';
						break;
					case 'xpath':
						$function[] = '$this->byXPath("' . $step->locator->value .'")->click();';
						break;				
				}
				break;
			case 'verifyTextPresent':
				$function[] = '$this->assertContains("'. $step->text .'", $this->byCssSelector("body")->text());';
				break;
			case 'setElementText' : 
				switch($step->locator->type) {
					case 'id':
						$function[] = '$this->byId("'. $step->locator->value .'")->value("'. $step->text .'");';
						break;
					case 'xpath':
						$function[] = '$this->byXPath("'. $step->locator->value .'")->value("'. $step->text .'");';
						break;

				}
		}
	}

	$contents = '<?php
	require_once "vendor/autoload.php";

	class s4Login extends Sauce\Sausage\WebDriverTestCase
	{

	  public static $browsers = array(
	        // run FF15 on Windows 7 on Sauce
	        array(
	            "browserName" => "firefox",
	            "desiredCapabilities" => array(
	                "version" => "15",
	                "platform" => "Windows 7"
	            ),
	        )
	    );

	  public function setUpPage()
	  {
	      $this->url("'. $start_url .'");
	  }

	  public function test'. $name .'() {
	    $this->timeouts()->implicitWait(3000);
	  	'. implode("\n\t\t", $function) .'
	  }
	}';

	$file = fopen('tests/php/'. $name .'.php', 'w');
	fwrite($file, $contents);
	fclose($file);
}