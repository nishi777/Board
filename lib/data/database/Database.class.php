<?php
/**
 * @author     SilexBB
 * @copyright  2011 - 2012 Silex Bulletin Board
 * @license    GPL version 3 or higher <http://www.gnu.org/licenses/gpl-3.0.html>
 */

/**
 * Returns a new PDO instance based on the settings of the configuration
 * @return PDO
 */
class Database {
	public static function GetDatabase() {
		$DBType = strtolower(CFG_DB_TYPE);
		$Port   = (string)CFG_DB_PORT;
		$Socket = (string)CFG_DB_SOCKET;

		if(!in_array($DBType, ['mysql', 'sqlite', 'pgsql', 'firebird', 'informix', 'oci', 'odbc', 'dblib', 'ibm']))
			throw new DatabaseException('The database type "'.CFG_DB_TYPE.'" is currently not supported');

		if($DBType == 'sqlite')
			return new PDO('sqlite:'.CFG_DB_DATABASE.'.db');

		// Generate DSN-String
		$DSN = $DBType.':';
		if(!empty($Socket))
			$DSN .= 'unix_socket='.CFG_DB_SOCKET.';';
		else {
			$DSN .= 'host='.CFG_DB_HOST.';';
			if(!empty($Port))
				$DSN .= 'port='.CFG_DB_PORT.';';
		}
		$DSN .= 'dbname='.CFG_DB_DATABASE;

		// Return PDO database with DSN strings
		if($DBType == 'pgsql')
			return new PDO($DSN.';user='.CFG_DB_USER.';password='.CFG_DB_PASSWORD);
		return new PDO($DSN, CFG_DB_USER, CFG_DB_PASSWORD);
	}

	/**
	 * Count the rows matching with the given query
	 * Examples:
	 *     $Query = 'SELECT COUNT(*) FROM `table` WHERE `column` = :column'
	 *     $Data  = [':column' => 'name']
	 *  or
	 *     $Query = 'SELECT COUNT(*) FROM `table` WHERE `column` = \'name\''
	 *  or
	 *     $Query = 'FROM `table` WHERE `column` = :column'  // Automatic complement
	 *     $Data  = [':column' => 'name']
	 *  or
	 *     $Query = 'FROM `table` WHERE `column` = \'name\'' // Automatic complement
	 *
	 * @param  string $Query
	 * @param  array  $Data
	 * @return int
	 */
	public static function Count($Query, array $Data = array()) {
		if(strpos($Query, 'SELECT COUNT(*)') === false)
			$Query = 'SELECT COUNT(*) '.$Query;

		$STMT = null;
		if(empty($Data))
			$STMT = SBB::DB()->query($Query);
		else {
			$STMT = SBB::DB()->prepare($Query);
			$STMT->execute($Data);
		}
		return (int)$STMT->fetch(PDO::FETCH_ASSOC)['COUNT(*)'];
	}
}
?>