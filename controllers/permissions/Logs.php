<?php
	namespace Controllers\Permissions;

	class Logs extends \Bolt\Permission
	{
		// Base / ID Levels and Full Control are specified in Permission
		// Action Levels (2^7+)
		const POSTSEARCH = 128;
	}
?>
