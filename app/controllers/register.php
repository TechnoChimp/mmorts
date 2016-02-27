<?php

use Phalcon\Mvc\Controller;

class RegisterController extends Controller
{

	public function indexAction()
	{
		
		$user = new Users();
		
		//Store and check for errors
		$success = $user->save($this->request->getPost(), array('username', 'password', 'email'));
		
		if ($success) {
			echo "Thanks for registering!";
		} else {
			echo "Sorry, the following problems were generated: ";
			foreach ($user->getMessages() as $message) {
				echo $message->getMessage(), "<br/>";
			}
		}
		
	}

}
