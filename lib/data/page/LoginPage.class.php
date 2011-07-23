<?php
/**
 * @author 		Nut
 * @copyright	© 2011 Silex Bulletin Board - Team
 * @license		GNU GENERAL PUBLIC LICENSE v3
 * @package		SilexBoard.DEV
 * @version		Revision: 8
 */

class LoginPage extends Page {
	protected static function Load() {
		// Falls eingeloggt, auf Startseite weiterleiten.	
		if(isset($_COOKIE['sbb_LoginHash']) || session::Read('UserID')) 
			header("Location: ?page=Home");
		
		switch($_POST['Register']) {
			case 1:
				$_SESSION['RegisterName'] = $_POST['Username'];
				$_SESSION['RegisterPass'] = $_POST['Password'];
				header("Location: ?page=Register");
				break;
			case 0:
				$Login = new login();
				$MSG = $Login->GetMSG();
				break;
		}
		Template::Assign(array('Page' => 'Login', 'LoginMessage' => $MSG));
	}
}
?>