namespace :deploy do
    desc "Kill all PHP processes"
    task :killall_php do
        on release_roles :all do
            execute "killall -q php || true"
            execute "rm -f /home/soundcharts/*.lock  || true"
        end
    end
end
