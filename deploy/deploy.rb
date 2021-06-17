# config valid for current version and patch releases of Capistrano
lock "~> 3.16.0"

set :application, "songkick-event-bots"
set :repo_url, "git@github.com:soundcharts/songkick-event-bots.git"

set :branch, ENV['BRANCH'] if ENV['BRANCH']

set :deploy_to, "/home/soundcharts/songkick-event-bots"

set :composer_install_flags, "--no-dev --quiet --no-interaction --prefer-dist --optimize-autoloader"

SSHKit.config.command_map[:composer] = "php -d memory_limit=-1 /usr/local/bin/composer2"

set :linked_dirs, [ 'var/log', 'public/bundles' ]

before 'composer:run', 'deploy:killall_php'
