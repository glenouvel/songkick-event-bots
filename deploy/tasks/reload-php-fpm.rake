namespace :deploy do
    desc "Reload PHP FPM"
    task :reload_php_fpm do
        on release_roles :all do
            within release_path do
                execute "sudo /usr/sbin/service php7.2-fpm restart"
            end
        end
    end
end
