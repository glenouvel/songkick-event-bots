set :symfony_env, "prod"

set :ssh_user, "soundcharts"

set :pty, true

set :deploy_to, "/home/soundcharts/songkick-event-bots"

set :composer_install_flags, "--quiet --no-interaction --prefer-dist --optimize-autoloader"

before 'composer:run', 'deploy:killall_php'

after 'deploy:symlink:release', 'deploy:killall_php'

server "bots1.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots2.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots3.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots4.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots5.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots6.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots7.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots8.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots9.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots10.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots11.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots12.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots13.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots14.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots15.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots16.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots17.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots18.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots19.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots20.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots21.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots22.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots23.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots24.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots25.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots26.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots27.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots28.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots29.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots30.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots31.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots32.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots33.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots34.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots35.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots36.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots37.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots38.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots39.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots40.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots41.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots42.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots43.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots44.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}

server "bots45.soundcharts.com",
  user: "soundcharts",
  roles: %w{public app db}
