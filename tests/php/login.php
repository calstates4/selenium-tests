<?php

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
	      $this->url("http://dev-s4-training.gotpantheon.com/");
	  }

	  public function testlogin() {
	    $this->timeouts()->implicitWait(3000);
	  	$this->byLinkText("Login")->click();
		$this->byId("edit-name")->value("staff");
		$this->byId("edit-pass")->value("staff");
		$this->byId("edit-submit")->click();
		$this->assertContains("Staff actions", $this->byCssSelector("body")->text());
	  }
	}