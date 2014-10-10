<?php
	namespace Bolt;

	class Permission extends Enum
	{
		// Base Level
		const GET = 1;
		const POST = 2;

		// ID Level
		const GETBYID = 4;
		const POSTBYID = 8;
		const PUTBYID = 16;
		const PATCHBYID = 32;
		const DELETEBYID = 64;

		// Full Control - Includes all possible permissions
		const FULL = 2147483647;
	}
?>
