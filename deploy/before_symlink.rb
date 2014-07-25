composer_command = "php"
composer_command << " #{release_path}/composer.phar"
composer_command << " --no-interaction"
composer_command << " install"
composer_command << " --no-dev"
composer_command << " --optimize-autoloader"

run "cd #{release_path} && #{composer_command}"
