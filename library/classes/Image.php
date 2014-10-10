<?php
	namespace Bolt;

	class Image
	{
		public $image;
		public $info;

		public function load($filename)
		{
			$this->info = getimagesize($filename);

			switch ($this->info['mime'])
			{
				case "image/jpeg":
					$this->image = imagecreatefromjpeg($filename);
					break;
				case "image/gif":
					$this->image = imagecreatefromgif($filename);
					break;
				case "image/png":
					$this->image = imagecreatefrompng($filename);
					break;
			}
		}

		public function save($filename, $permissions = null)
		{
			switch ($this->info['mime'])
			{
				case "image/jpeg":
					imagejpeg($this->image, $filename);
					break;
				case "image/gif":
					imagegif($this->image, $filename);
					break;
				case "image/png":
					imagepng($this->image, $filename);
					break;
			}

			if ($permissions != null)
			{
				chmod($filename, $permissions);
			}
		}

		public function newImage($width, $height, $mime)
		{
			$this->image = imagecreatetruecolor($width, $height);
			#imagealphablending($this->image, false);
			#imagesavealpha($this->image, true);
			#imagefill($this->image, 0, 0, imagecolorallocatealpha($this->image, 0, 0, 0, 127));

			$this->info[0] = $width;
			$this->info[1] = $height;
			$this->info['mime'] = $mime;
		}

		public function display()
		{
			header("Content-type: " . $this->info['mime']);

			switch ($this->info['mime'])
			{
				case "image/jpeg":
					imagejpeg($this->image, null, 100);
					break;
				case "image/gif":
					imagegif($this->image);
					break;
				case "image/png":
					imagealphablending($this->image, true);
					imagesavealpha($this->image, true);
					imagepng($this->image);
					break;
			}
		}

		public function resizeToWidth($width)
		{
			$ratio = $width / $this->getDimension("x");
			$height = $this->getDimension("y") * $ratio;
			$this->resize($width, $height);
		}

		public function resizeToHeight($height)
		{
			$ratio = $height / $this->getDimension("y");
			$width = $this->getDimension("x") * $ratio;
			$this->resize($width, $height);
		}

		public function ratioResize($width, $height)
		{
			$imageRatio = $this->ratio();
			$resizeRatio = $width / $height;

			if ($imageRatio >= $resizeRatio)
			{
				$this->resizeToWidth($width);
			}
			else
			{
				$this->resizeToHeight($height);
			}
		}

		public function resize($width, $height)
		{
			$resizedImage = imagecreatetruecolor($width, $height);

			imagealphablending($resizedImage, false);
			imagesavealpha($resizedImage, true);
			imagefill($resizedImage, 0, 0, imagecolorallocatealpha($resizedImage, 0, 0, 0, 127));
			imagecopyresampled($resizedImage, $this->image, 0, 0, 0, 0, $width, $height, $this->getDimension("x"), $this->getDimension("y"));
			$this->image = $resizedImage;

			$this->info[0] = $width;
			$this->info[1] = $height;
		}

		public function scale($percentage)
		{
			$width = $this->getDimension("x") * ($percentage / 100);
			$height = $this->getDimension("y") * ($percentage / 100);

			$this->resize($width, $height);
		}

		public function getDimension($type)
		{
			$result = ($type == "x") ? $this->info[0] : $this->info[1];
			return $result;
		}

		public function crop($top, $right, $bottom, $left, $colour = null)
		{
			$original = $this->image;
			$x = $this->getDimension("x");
			$y = $this->getDimension("y");

			$newX = $x + ($left + $right);
			$newY = $y + ($top + $bottom);

			$this->newImage($newX, $newY, $this->info['mime']);

			if ($colour === null)
			{
				$colour = imagecolorallocatealpha($this->image, 0, 0, 0, 127);
			}

			imagefill($this->image, 0, 0, $colour);
			imagecopy($this->image, $original, $left, $top, 0, 0, $x, $y);
		}

		public function ratio()
		{
			return $this->getDimension("x") / $this->getDimension("y");
		}
	}
?>
