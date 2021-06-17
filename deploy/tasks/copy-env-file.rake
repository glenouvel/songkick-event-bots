namespace :composer do
    desc "Copy env file"
    task :setenv do
        on release_roles :all do
            within release_path do
                execute :cp, ".env.#{fetch(:stage)}", ".env"
            end
        end
    end
end
