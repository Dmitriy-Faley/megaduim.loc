<?php
class ModelToolDoc extends Model {
	public function docum($filename) {
		if (!is_file(DIR_DOC . $filename) || substr(str_replace('\\', '/', realpath(DIR_DOC . $filename)), 0, strlen(DIR_DOC)) != str_replace('\\', '/', DIR_DOC)) {
			return;
		}

		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		$image_old = $filename;
		$image_new = 'cache/' . $filename;

		if (!is_file(DIR_DOC . $image_new) || (filemtime(DIR_DOC . $image_old) > filemtime(DIR_DOC . $image_new))) {
			list($width_orig, $height_orig, $image_type) = getimagesize(DIR_DOC . $image_old);
				 
			if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) { 
				return DIR_DOC . $image_old;
			}
						
			$path = '';

			$directories = explode('/', dirname($image_new));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir(DIR_DOC . $path)) {
					@mkdir(DIR_DOC . $path, 0777);
				}
			}
		}
		
		$image_new = str_replace(' ', '%20', $image_new);  // fix bug when attach image on email (gmail.com). it is automatic changing space " " to +
		
		if ($this->request->server['HTTPS']) {
			return $this->config->get('config_ssl') . 'documents/' . $image_new;
		} else {
			return $this->config->get('config_url') . 'documents/' . $image_new;
		}
	}
}
