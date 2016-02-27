<?php

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\InclusionIn;

class Character extends Model
{
	public function validation()
	{
		//Character name must be unique
		$this->validate(
			new Uniqueness(
				array(
					"field"		=> "name",
					"message"	=> "The character name must be unique"
				)
			)
		);
		
		//Character stats cannot be less than zero
		if ($this->stat1 < 0) {
			$this->appendMessage(new Message("The stat cannot be less than zero"));
		}
		
		//Check if any messages have been produces
		if ($this->validationHasFailed() == true) {
			return false;
		}
		
	}
	

}
