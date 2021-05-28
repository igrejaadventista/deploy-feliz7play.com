<?php

echo $_ENV['WP_DB_HOST'] ."\n";
echo $_ENV['WP_DB_NAME'] ."\n";
echo $_ENV['WP_DB_PASSWORD'] ."\n";
echo $_ENV['WP_DB_USER'] ."\n";
echo $_ENV['WP_S3_ACCESS_KEY'] ."\n";
echo $_ENV['WP_S3_SECRET_KEY'] ."\n";
echo $_ENV['WP_S3_BUCKET'] ."\n";

phpinfo(INFO_ENVIRONMENT);
phpinfo(INFO_VARIABLES);